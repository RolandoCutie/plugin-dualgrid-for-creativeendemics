/**
 * DualGrid - Tour Finder Wizard
 */
(function() {
    'use strict';

    const DATA = window.dgWizardData || {};
    const TOURS = Array.isArray(DATA.tours) ? DATA.tours : [];
    const MAIN_FOCUS_OPTIONS = Array.isArray(DATA.mainFocusOptions) ? DATA.mainFocusOptions : [];
    const REGION_OPTIONS = Array.isArray(DATA.regionOptions) ? DATA.regionOptions : [];
    const DURATION_OPTIONS = Array.isArray(DATA.durationOptions) ? DATA.durationOptions : [];
    const STATUS_OPTIONS = Array.isArray(DATA.statusOptions) ? DATA.statusOptions : [];

    let currentStep = 0;
    let validationMessage = '';
    let answers = {
        mainFocus: [],
        regions: [],
        duration: '',
    };

    const QUESTIONS = [
        {
            id: 'mainFocus',
            label: 'What is the main focus of this trip?',
            sub: 'Choose one or more interests. Tour priorities decide which matches appear first.',
            type: 'multi',
            options: MAIN_FOCUS_OPTIONS.map(function(option) {
                return { value: String(option.id), label: option.label };
            }),
        },
        {
            id: 'regions',
            label: 'Which region would you like to explore?',
            sub: 'Select one or more regions to narrow the results.',
            type: 'multi',
            options: REGION_OPTIONS.map(function(option) {
                return { value: String(option.id), label: option.label };
            }),
        },
        {
            id: 'duration',
            label: 'How long should the tour be?',
            sub: 'Choose the duration band that best matches this trip.',
            type: 'single',
            options: DURATION_OPTIONS.map(function(option) {
                return { value: String(option.id), label: option.label };
            }),
        },
    ];

    const totalSteps = QUESTIONS.length;

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getFocusLabel(focusId) {
        const match = MAIN_FOCUS_OPTIONS.find(function(option) {
            return Number(option.id) === Number(focusId);
        });
        return match ? match.label : 'Main Focus';
    }

    function getRegionLabel(regionId) {
        const match = REGION_OPTIONS.find(function(option) {
            return Number(option.id) === Number(regionId);
        });
        return match ? match.label : 'Region';
    }

    function getDurationLabel(durationId) {
        const match = DURATION_OPTIONS.find(function(option) {
            return Number(option.id) === Number(durationId);
        });
        return match ? match.label : 'Duration';
    }

    function getStatusLabel(statusValue) {
        const match = STATUS_OPTIONS.find(function(option) {
            return option.value === statusValue;
        });
        return match ? match.label : 'Available';
    }

    function getPriorityStars(priority) {
        if (Number(priority) === 1) {
            return '***';
        }
        if (Number(priority) === 2) {
            return '**';
        }
        return '*';
    }

    function getPriorityWeight(priority) {
        if (Number(priority) === 1) {
            return 100;
        }
        if (Number(priority) === 2) {
            return 75;
        }
        if (Number(priority) === 3) {
            return 45;
        }
        return 60;
    }

    function getStatusWeight(status) {
        if (status === 'available') {
            return 12;
        }
        if (status === 'spots') {
            return 6;
        }
        return -30;
    }

    function validateCurrentStep() {
        const question = QUESTIONS[currentStep];
        if (!question) {
            return true;
        }

        if (question.type === 'multi' && (!Array.isArray(answers[question.id]) || answers[question.id].length === 0)) {
            validationMessage = 'Please select at least one option to continue.';
            return false;
        }

        if (question.type === 'single' && !answers[question.id]) {
            validationMessage = 'Please choose one option to continue.';
            return false;
        }

        validationMessage = '';
        return true;
    }

    function scoreTour(tour) {
        let score = 0;
        let exactMatches = 0;
        const reasons = [];
        let matchedFocusCount = 0;

        (answers.mainFocus || []).forEach(function(focusId) {
            const match = (tour.mainFocus || []).find(function(item) {
                return Number(item.categoryId) === Number(focusId);
            });

            if (!match) {
                return;
            }

            score += getPriorityWeight(match.priority);
            if (tour.programSlug === MAIN_FOCUS_OPTIONS.find(function(option) { return Number(option.id) === Number(focusId); })?.slug) {
                score += 10;
            }
            exactMatches += 1;
            matchedFocusCount += 1;
            reasons.push(getFocusLabel(focusId) + ' ' + getPriorityStars(match.priority));
        });

        if (matchedFocusCount > 1) {
            score += (matchedFocusCount - 1) * 35;
        }

        (answers.regions || []).forEach(function(regionId) {
            const numericRegionId = Number(regionId);
            if (!(tour.regions || []).includes(numericRegionId)) {
                return;
            }

            if (numericRegionId === 1) {
                score += 20;
            } else {
                score += 70;
                exactMatches += 1;
            }

            reasons.push(getRegionLabel(regionId));
        });

        if (answers.duration && Number(tour.durationBand) === Number(answers.duration)) {
            score += 80;
            exactMatches += 1;
            reasons.push(getDurationLabel(answers.duration));
        }

        score += getStatusWeight(tour.status);
        score -= Math.max(0, ((tour.mainFocus || []).length - 1) * 6);

        return {
            ...tour,
            score: score,
            exactMatches: exactMatches,
            reasons: reasons.slice(0, 4),
            statusLabel: tour.statusLabel || getStatusLabel(tour.status),
        };
    }

    function getRankedTours() {
        return getRankedToursForAnswers(answers);
    }

    function getRankedToursForAnswers(inputAnswers) {
        const previousAnswers = answers;
        answers = {
            mainFocus: Array.isArray(inputAnswers.mainFocus) ? inputAnswers.mainFocus.map(String) : [],
            regions: Array.isArray(inputAnswers.regions) ? inputAnswers.regions.map(String) : [],
            duration: inputAnswers.duration ? String(inputAnswers.duration) : '',
        };

        const rankedTours = TOURS
            .map(scoreTour)
            .filter(function(tour) {
                return tour.score > 0;
            })
            .sort(function(a, b) {
                if (b.score !== a.score) {
                    return b.score - a.score;
                }

                if (a.primaryPriority !== b.primaryPriority) {
                    return a.primaryPriority - b.primaryPriority;
                }

                if ((a.catalogOrder || 0) !== (b.catalogOrder || 0)) {
                    return (a.catalogOrder || 0) - (b.catalogOrder || 0);
                }

                return a.title.localeCompare(b.title);
            });

    answers = previousAnswers;
    return rankedTours;
    }

    function handleAnswer(questionId, value, isMulti) {
        validationMessage = '';

        if (isMulti) {
            const current = Array.isArray(answers[questionId]) ? answers[questionId] : [];
            answers[questionId] = current.includes(value)
                ? current.filter(function(item) { return item !== value; })
                : current.concat([value]);
            renderQuestion();
            return;
        }

        answers[questionId] = value;
        renderQuestion();

        window.setTimeout(function() {
            if (currentStep < totalSteps - 1) {
                nextStep();
                return;
            }

            showResultScreen();
        }, 200);
    }

    function nextStep() {
        if (!validateCurrentStep()) {
            renderQuestion();
            return;
        }

        if (currentStep < totalSteps - 1) {
            currentStep += 1;
            renderQuestion();
            return;
        }

        showResultScreen();
    }

    function prevStep() {
        validationMessage = '';
        if (currentStep > 0) {
            currentStep -= 1;
            renderQuestion();
        }
    }

    function updateProgressBar() {
        const el = document.querySelector('.dg-progress-bar-fill');
        if (el) {
            if (currentStep >= totalSteps) {
                el.style.width = '100%';
                return;
            }

            el.style.width = (((currentStep + 1) / totalSteps) * 100) + '%';
        }
    }

    function updateStepIndicator() {
        const el = document.querySelector('.dg-step-indicator');
        if (!el) {
            return;
        }

        if (currentStep < totalSteps) {
            el.textContent = 'Tour Finder · ' + String(currentStep + 1).padStart(2, '0') + ' / ' + String(totalSteps).padStart(2, '0');
            el.style.display = 'block';
            return;
        }

        el.style.display = 'none';
    }

    function renderQuestion() {
        const question = QUESTIONS[currentStep];
        if (!question) {
            return;
        }

        updateProgressBar();
        updateStepIndicator();

        const container = document.getElementById('dgWizardContent');
        if (!container) {
            return;
        }

        const optionsHTML = question.options.map(function(option) {
            const isSelected = question.type === 'multi'
                ? (answers[question.id] || []).includes(option.value)
                : answers[question.id] === option.value;

            return '<div class="dg-option-card ' + (isSelected ? 'dg-selected' : '') + '" data-question-id="' + escapeHtml(question.id) + '" data-option-value="' + escapeHtml(option.value) + '" data-is-multi="' + (question.type === 'multi' ? '1' : '0') + '">' +
                '<div class="dg-option-content">' +
                '<div class="dg-option-label">' + escapeHtml(option.label) + '</div>' +
                (option.desc ? '<div class="dg-option-desc">' + escapeHtml(option.desc) + '</div>' : '') +
                '</div>' +
                '<div class="dg-check-circle">✓</div>' +
                '</div>';
        }).join('');

        const backBtn = currentStep > 0
            ? '<button class="dg-back-btn" onclick="window.dgPrevStep()">← Back</button>'
            : '<div></div>';

        const continueBtn = question.type === 'multi'
            ? '<button class="dg-continue-btn" onclick="window.dgNextStep()">Continue →</button>'
            : '<div></div>';

        const allToursBtn = '<button class="dg-back-btn" onclick="window.dgShowAllTours()">Show all tours</button>';

        container.innerHTML = '<div class="dg-question-screen">' +
            '<h2 class="dg-question-title">' + escapeHtml(question.label) + '</h2>' +
            '<p class="dg-question-sub">' + escapeHtml(question.sub) + '</p>' +
            '<div class="dg-options-grid">' + optionsHTML + '</div>' +
            (validationMessage ? '<p class="dg-question-error">' + escapeHtml(validationMessage) + '</p>' : '') +
            '<div class="dg-nav-container">' + backBtn + continueBtn + '</div>' +
            '<div class="dg-nav-container" style="margin-top:8px; justify-content:flex-end;">' + allToursBtn + '</div>' +
            '</div>';
    }

    function showAllTours() {
        validationMessage = '';
        answers = {
            mainFocus: [],
            regions: [],
            duration: '',
        };
        showResultScreen();
    }

    function showResultScreen() {
        currentStep = totalSteps;
        updateProgressBar();
        updateStepIndicator();

        const container = document.getElementById('dgWizardContent');
        if (!container) {
            return;
        }

        const rankedTours = getRankedTours();

        if (rankedTours.length === 0) {
            container.innerHTML = '<div class="dg-result-screen">' +
                '<div class="dg-result-label"><span class="dg-check-icon">!</span><span>No direct match found</span></div>' +
                '<h2 class="dg-result-title">Try a <em>tailor-made journey</em></h2>' +
                '<p class="dg-result-desc">We could not find a tour that matches all selected filters. Use the tailor-made form and we will prepare custom options.</p>' +
                '<div class="dg-result-actions"><button type="button" class="dg-btn-terracotta" onclick="window.dgOpenTailorMadeModal ? window.dgOpenTailorMadeModal() : null">Plan a tailor-made trip</button></div>' +
                '<button class="dg-retake-btn" onclick="window.dgResetWizard()">Retake the questionnaire</button>' +
                '</div>';
            return;
        }

        const topTour = rankedTours[0];
        const otherTours = rankedTours.slice(1, 5);

        const reasonsHtml = topTour.reasons.map(function(reason) {
            return '<span class="dg-result-pill">' + escapeHtml(reason) + '</span>';
        }).join('');

        const toursHtml = otherTours.map(function(tour) {
            return '<a href="' + escapeHtml(tour.link || '#') + '" class="dg-result-tour-card">' +
                '<div class="dg-result-tour-card__image"><img src="' + escapeHtml(tour.image || '') + '" alt="' + escapeHtml(tour.title) + '"></div>' +
                '<div class="dg-result-tour-card__body">' +
                '<span class="dg-result-tour-card__program">' + escapeHtml(tour.programName) + '</span>' +
                '<h3>' + escapeHtml(tour.title) + '</h3>' +
                '<p>' + escapeHtml(tour.description || '') + '</p>' +
                '<div class="dg-result-tour-card__meta"><span>' + escapeHtml(tour.duration || '') + '</span><span>' + escapeHtml(tour.statusLabel || '') + '</span></div>' +
                '</div>' +
                '</a>';
        }).join('');

        container.innerHTML = '<div class="dg-result-screen">' +
            '<div class="dg-result-label"><span class="dg-check-icon">✓</span><span>Your best match</span></div>' +
            '<h2 class="dg-result-title">' + escapeHtml(topTour.title) + '</h2>' +
            '<p class="dg-result-desc">' + escapeHtml(topTour.description || '') + '</p>' +
            '<div class="dg-result-summary">' + reasonsHtml + '</div>' +
            '<div class="dg-result-card">' +
            '<div class="dg-result-card-image"><img src="' + escapeHtml(topTour.image || '') + '" alt="' + escapeHtml(topTour.title) + '"></div>' +
            '<div class="dg-result-card-overlay"></div>' +
            '<div class="dg-result-card-info">' +
            '<div class="dg-result-card-coords">' + escapeHtml(topTour.programName) + ' · ' + escapeHtml(topTour.statusLabel || '') + '</div>' +
            '<div class="dg-result-card-meta"><span>' + escapeHtml(topTour.duration || getDurationLabel(topTour.durationBand)) + '</span><span>·</span><span>' + escapeHtml((topTour.regionLabels || []).join(' · ')) + '</span></div>' +
            '</div></div>' +
            '<div class="dg-result-actions">' +
            '<a href="' + escapeHtml(topTour.link || '#') + '" class="dg-btn-forest">View this tour</a>' +
            '<button type="button" class="dg-btn-terracotta" onclick="window.dgOpenTailorMadeModal ? window.dgOpenTailorMadeModal() : null">Plan a tailor-made trip</button>' +
            '</div>' +
            (toursHtml ? '<div class="dg-result-priority"><h3 class="dg-result-section-title">Other suggested tours</h3><div class="dg-result-tour-grid">' + toursHtml + '</div></div>' : '') +
            '<button class="dg-retake-btn" onclick="window.dgResetWizard()">Retake the questionnaire</button>' +
            '</div>';
    }

    function resetWizard() {
        currentStep = 0;
        validationMessage = '';
        answers = {
            mainFocus: [],
            regions: [],
            duration: '',
        };
        renderQuestion();
    }

    function openWizard() {
        const overlay = document.getElementById('dgWizardOverlay');
        if (!overlay) {
            return;
        }

        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        resetWizard();
    }

    function closeWizard() {
        const overlay = document.getElementById('dgWizardOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
        document.body.style.overflow = 'auto';
    }

    window.dgHandleAnswer = handleAnswer;
    window.dgNextStep = nextStep;
    window.dgPrevStep = prevStep;
    window.dgResetWizard = resetWizard;
    window.dgShowAllTours = showAllTours;
    window.dgOpenWizard = openWizard;
    window.dgCloseWizard = closeWizard;
    window.dgWizardDebug = {
        runScenario: function(inputAnswers, limit) {
            const rows = getRankedToursForAnswers(inputAnswers || {}).slice(0, Number(limit) || 5);
            return rows.map(function(row) {
                return {
                    title: row.title,
                    slug: row.slug,
                    score: row.score,
                    duration: row.duration,
                    program: row.programName,
                };
            });
        },
        runDefaultSuite: function() {
            const suite = [
                { id: 'T1', answers: { mainFocus: [1], regions: [2], duration: 1 }, expectTop: 'zapata-wetland-birding-tour' },
                { id: 'T2', answers: { mainFocus: [1], regions: [2], duration: 2 }, expectTop: 'cuba-birding-tour-endemics-migration' },
                { id: 'T3', answers: { mainFocus: [1], regions: [2], duration: 3 }, expectTop: 'grand-birding-tour-cuban-endemics-habitats' },
                { id: 'T4', answers: { mainFocus: [3], regions: [3], duration: 3 }, expectTop: 'herp-watching-in-eastern-cuba' },
                { id: 'T5', answers: { mainFocus: [3], regions: [2], duration: 3 }, expectTop: 'wild-cuba-reptiles-amphibians-tour' },
                { id: 'T6', answers: { mainFocus: [2], regions: [3], duration: 2 }, expectTop: 'polymita-photography-expedition-cubas-painted-tree-snails' },
                { id: 'T7', answers: { mainFocus: [6], regions: [3], duration: 2 }, expectTop: 'polymita-photography-expedition-cubas-painted-tree-snails' },
                { id: 'T8', answers: { mainFocus: [6], regions: [2], duration: 2 }, expectTop: 'cuba-land-snails-photography-workshop' },
                { id: 'T9', answers: { mainFocus: [4], regions: [3], duration: 2 }, expectTop: 'eastern-cuba-hiking-trails-heritage' },
                { id: 'T10', answers: { mainFocus: [4], regions: [3], duration: 3 }, expectTop: 'eastern-cuba-trek-rainforest-peaks' },
                { id: 'T11', answers: { mainFocus: [4], regions: [2], duration: 3 }, expectTop: 'cuba-grand-trek-mountains-valleys' },
                { id: 'T12', answers: { mainFocus: [5], regions: [3], duration: 3 }, expectTop: 'eastern-cuba-discovery-natural-wonders' },
                { id: 'T13', answers: { mainFocus: [5], regions: [2], duration: 2 }, expectTop: 'cuban-nature-adventure-birds-waterfalls' },
                { id: 'T16', answers: { mainFocus: [1, 2, 3, 4, 5, 6], regions: [1, 2, 3], duration: 3 }, expectTop: 'cuba-wild-odyssey-tour' },
                { id: 'T17', answers: { mainFocus: [], regions: [], duration: '' }, expectAny: true },
                { id: 'T-DEF', answers: { mainFocus: [1], regions: [3], duration: 3 }, expectAny: true },
            ];

            return suite.map(function(test) {
                const ranked = getRankedToursForAnswers(test.answers);
                const top = ranked[0] || null;
                const topSlug = top ? top.slug : '';
                const passed = test.expectAny ? ranked.length > 0 : topSlug === test.expectTop;

                return {
                    test: test.id,
                    passed: passed,
                    topSlug: topSlug,
                    expectedTop: test.expectTop || '(any)',
                    results: ranked.length,
                };
            });
        },
    };

    document.addEventListener('DOMContentLoaded', function() {
        const openBtn = document.getElementById('openWizard');
        if (openBtn) {
            openBtn.addEventListener('click', openWizard);
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.dg-wizard-trigger');
            if (!btn) {
            } else {
                e.preventDefault();
                e.stopPropagation();
                openWizard();
                return;
            }

            const optionCard = e.target.closest('.dg-option-card');
            if (optionCard && optionCard.dataset.questionId) {
                e.preventDefault();
                handleAnswer(optionCard.dataset.questionId, optionCard.dataset.optionValue || '', optionCard.dataset.isMulti === '1');
            }
        });

        const closeBtn = document.querySelector('.dg-wizard-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeWizard);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWizard();
            }
        });

        renderQuestion();
    });
})();
