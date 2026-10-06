/**
 * DualGrid - Tour Finder Wizard
 */
(function() {
    'use strict';

    const PROGRAMS = (window.dgWizardData && window.dgWizardData.programs) || {};

    let currentStep = 0;
    let answers = {};
    const totalSteps = 7;

    const QUESTIONS = [
        {
            id: 'focus',
            label: 'Which kind of program feels right for this trip?',
            sub: 'Your first choice defines the strongest routing signal.',
            type: "single",
            options: [
                { value: 'birding', label: 'Birding', desc: 'Endemics, migration and expert spotting' },
                { value: 'wildlife', label: 'Wildlife', desc: 'Mammals, reptiles and rainforest encounters' },
                { value: 'nature', label: 'Nature', desc: 'Balanced nature, soft adventure and scenery' },
                { value: 'hiking', label: 'Hiking', desc: 'Trails, elevation and active days outside' },
                { value: 'culture', label: 'Culture', desc: 'Communities, traditions and local life' },
                { value: 'photography', label: 'Photography', desc: 'Light, hides and image-focused travel' },
            ],
        },
        {
            id: 'interests',
            label: 'What do you want most from the experience?',
            sub: 'Select everything that should influence the recommendation.',
            type: "multi",
            options: [
                { value: 'birds', label: 'Birds' },
                { value: 'wildlife', label: 'Wildlife' },
                { value: 'rainforest', label: 'Rainforest & scenery' },
                { value: 'photography', label: 'Photography' },
                { value: 'culture', label: 'Culture' },
                { value: 'hiking', label: 'Hiking' },
                { value: 'comfort', label: 'Comfort & easy pacing' },
            ],
        },
        {
            id: 'level',
            label: 'How active do you want the trip to be?',
            sub: 'We use this to prioritise trail-heavy vs softer itineraries.',
            type: "single",
            options: [
                { value: 'easy', label: 'Easy', desc: 'Relaxed pace and easy logistics' },
                { value: 'moderate', label: 'Moderate', desc: 'Some effort, still comfortable' },
                { value: 'advanced', label: 'Advanced', desc: 'Longer days and more challenge' },
            ],
        },
        {
            id: 'days',
            label: 'How many days are you planning for?',
            sub: 'Trip length helps rank short breaks vs deeper itineraries.',
            type: "single",
            options: [
                { value: '3-5', label: '3-5 days', desc: 'Quick getaway' },
                { value: '6-8', label: '6-8 days', desc: 'Compact immersion' },
                { value: '9-12', label: '9-12 days', desc: 'Deeper trip' },
                { value: '13+', label: '13+ days', desc: 'Long-form journey' },
            ],
        },
        {
            id: 'people',
            label: 'How many people are travelling?',
            sub: 'Some programs perform better for private couples, others for small groups.',
            type: 'single',
            options: [
                { value: '1', label: '1 traveller', desc: 'Solo travel' },
                { value: '2', label: '2 travellers', desc: 'Couple or duo' },
                { value: '3-4', label: '3-4 travellers', desc: 'Small private party' },
                { value: '5+', label: '5+ travellers', desc: 'Larger group setup' },
            ],
        },
        {
            id: 'budget',
            label: 'What budget range do you have in mind per traveller?',
            sub: 'This keeps premium and entry-level programs in the right order.',
            type: 'single',
            options: [
                { value: 'under-2500', label: 'Under $2,500' },
                { value: '2500-4000', label: '$2,500-$4,000' },
                { value: '4000-6500', label: '$4,000-$6,500' },
                { value: '6500+', label: '$6,500+' },
            ],
        },
        {
            id: 'month',
            label: 'When would you like to travel?',
            sub: 'Preferred timing helps surface the best seasonal fit first.',
            type: "single",
            isMonths: true,
            options: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'].map((month) => ({ value: month, label: month })),
        },
    ];

    const SCORE_RULES = {
        birding: {
            focus: { birding: 9, photography: 3, wildlife: 2 },
            interests: { birds: 6, photography: 2, wildlife: 2, rainforest: 1 },
            level: { easy: 1, moderate: 2, advanced: 1 },
            days: { '6-8': 2, '9-12': 3, '13+': 3 },
            people: { '1': 2, '2': 2, '3-4': 1 },
            budget: { '2500-4000': 2, '4000-6500': 3, '6500+': 2 },
            month: { Jan: 2, Feb: 2, Mar: 2, Apr: 2, May: 1, Jun: 1, Jul: 1, Aug: 1, Sep: 2, Oct: 2, Nov: 2, Dec: 2 },
        },
        wildlife: {
            focus: { wildlife: 9, nature: 3, photography: 2 },
            interests: { wildlife: 6, rainforest: 3, photography: 2, comfort: 1 },
            level: { easy: 2, moderate: 2, advanced: 1 },
            days: { '3-5': 1, '6-8': 2, '9-12': 2 },
            people: { '2': 1, '3-4': 2, '5+': 1 },
            budget: { 'under-2500': 1, '2500-4000': 2, '4000-6500': 2 },
            month: { Jan: 1, Feb: 1, Mar: 1, Apr: 1, May: 2, Jun: 2, Jul: 2, Aug: 2, Sep: 2, Oct: 2, Nov: 1, Dec: 1 },
        },
        nature: {
            focus: { nature: 9, wildlife: 3, culture: 1 },
            interests: { rainforest: 4, wildlife: 3, comfort: 2, photography: 1, culture: 1 },
            level: { easy: 2, moderate: 3, advanced: 1 },
            days: { '3-5': 2, '6-8': 3, '9-12': 2 },
            people: { '2': 2, '3-4': 2, '5+': 1 },
            budget: { 'under-2500': 2, '2500-4000': 2, '4000-6500': 1 },
            month: { Jan: 1, Feb: 1, Mar: 1, Apr: 1, May: 1, Jun: 1, Jul: 1, Aug: 1, Sep: 1, Oct: 1, Nov: 1, Dec: 1 },
        },
        hiking: {
            focus: { hiking: 9, nature: 3, photography: 1 },
            interests: { hiking: 6, rainforest: 2, photography: 1 },
            level: { easy: 0, moderate: 2, advanced: 5 },
            days: { '3-5': 2, '6-8': 2, '9-12': 3, '13+': 3 },
            people: { '1': 1, '2': 2, '3-4': 2, '5+': 1 },
            budget: { '2500-4000': 1, '4000-6500': 2, '6500+': 1 },
            month: { Jan: 2, Feb: 2, Mar: 2, Apr: 2, May: 1, Jun: 1, Jul: 1, Aug: 1, Sep: 1, Oct: 1, Nov: 1, Dec: 2 },
        },
        culture: {
            focus: { culture: 9, nature: 2, wildlife: 1 },
            interests: { culture: 6, comfort: 2, photography: 1, rainforest: 1 },
            level: { easy: 3, moderate: 2, advanced: 0 },
            days: { '3-5': 2, '6-8': 2, '9-12': 1 },
            people: { '2': 2, '3-4': 2, '5+': 2 },
            budget: { 'under-2500': 1, '2500-4000': 2, '4000-6500': 1 },
            month: { Jan: 1, Feb: 1, Mar: 1, Apr: 1, May: 1, Jun: 2, Jul: 2, Aug: 2, Sep: 1, Oct: 1, Nov: 1, Dec: 2 },
        },
        photography: {
            focus: { photography: 9, birding: 3, wildlife: 3, nature: 2 },
            interests: { photography: 6, birds: 2, wildlife: 2, rainforest: 2 },
            level: { easy: 2, moderate: 2, advanced: 1 },
            days: { '6-8': 2, '9-12': 3, '13+': 2 },
            people: { '1': 2, '2': 2, '3-4': 1 },
            budget: { '2500-4000': 1, '4000-6500': 3, '6500+': 3 },
            month: { Jan: 2, Feb: 2, Mar: 2, Apr: 2, May: 1, Jun: 1, Jul: 1, Aug: 1, Sep: 1, Oct: 2, Nov: 2, Dec: 2 },
        },
    };

    function scoreProgram(programKey) {
        const program = PROGRAMS[programKey] || {};
        const rules = SCORE_RULES[programKey] || {};
        let score = 0;
        const reasons = [];

        function addReason(label, value) {
            if (!value) {
                return;
            }

            score += value;
            reasons.push({ label, value });
        }

        Object.keys(rules).forEach((dimension) => {
            const map = rules[dimension] || {};

            if (Array.isArray(answers[dimension])) {
                answers[dimension].forEach((value) => {
                    addReason(value, map[value] || 0);
                });
                return;
            }

            const selectedValue = answers[dimension];
            if (selectedValue) {
                addReason(selectedValue, map[selectedValue] || 0);
            }
        });

        const sortedReasons = reasons
            .filter((reason) => reason.value > 0)
            .sort((a, b) => b.value - a.value)
            .slice(0, 3)
            .map((reason) => humanizeReason(reason.label));

        return {
            key: programKey,
            score,
            matchCount: reasons.filter((reason) => reason.value > 0).length,
            reasons: sortedReasons,
            tours: Array.isArray(program.tours) ? program.tours : [],
            ...program,
        };
    }

    function recommendPrograms() {
        const ranked = Object.keys(PROGRAMS)
            .map(scoreProgram)
            .sort((a, b) => b.score - a.score || b.matchCount - a.matchCount || b.tours.length - a.tours.length);

        return ranked.filter((program) => program.score > 0 || program.matchCount > 0);
    }

    function buildSuggestedTours(programs) {
        return programs.slice(0, 3).flatMap((program, programIndex) => {
            return (program.tours || []).slice(0, 2).map((tour, tourIndex) => ({
                ...tour,
                programName: program.name,
                priorityOrder: programIndex,
                score: program.score,
                cardRank: programIndex + '-' + tourIndex,
            }));
        });
    }

    function humanizeReason(value) {
        const labels = {
            birding: 'birding focus',
            wildlife: 'wildlife encounters',
            nature: 'nature balance',
            hiking: 'active hiking',
            culture: 'cultural immersion',
            photography: 'photo opportunities',
            birds: 'birds',
            rainforest: 'rainforest scenery',
            comfort: 'easy pacing',
            easy: 'easy effort level',
            moderate: 'moderate effort level',
            advanced: 'advanced activity',
            '3-5': 'short trip length',
            '6-8': 'mid-length trip',
            '9-12': 'deeper trip length',
            '13+': 'long journey',
            '1': 'solo profile',
            '2': 'pair profile',
            '3-4': 'small private party',
            '5+': 'group profile',
            'under-2500': 'entry budget',
            '2500-4000': 'balanced budget',
            '4000-6500': 'premium budget',
            '6500+': 'luxury budget',
        };

        return labels[value] || value;
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function handleAnswer(questionId, value, isMulti) {
        if (isMulti) {
            let current = answers[questionId] || [];
            if (current.includes(value)) {
                answers[questionId] = current.filter(v => v !== value);
            } else {
                answers[questionId] = [...current, value];
            }
            renderQuestion();
        } else {
            answers[questionId] = value;
            renderQuestion();
            setTimeout(() => {
                if (currentStep < totalSteps - 1) { nextStep(); }
                else { showResultScreen(); }
            }, 300);
        }
    }

    function nextStep() { if (currentStep < totalSteps - 1) { currentStep++; renderQuestion(); } }
    function prevStep() { if (currentStep > 0) { currentStep--; renderQuestion(); } }

    function updateProgressBar() {
        const el = document.querySelector('.dg-progress-bar-fill');
        if (el) el.style.width = (((currentStep + 1) / (totalSteps + 1)) * 100) + '%';
    }

    function updateStepIndicator() {
        const el = document.querySelector('.dg-step-indicator');
        if (!el) return;
        if (currentStep < totalSteps) {
            el.textContent = `Tour Finder · ${String(currentStep + 1).padStart(2, '0')} / ${String(totalSteps).padStart(2, '0')}`;
            el.style.display = 'block';
        } else {
            el.style.display = 'none';
        }
    }

    function renderQuestion() {
        const q = QUESTIONS[currentStep];
        if (!q) return;
        updateProgressBar();
        updateStepIndicator();

        const container = document.getElementById('dgWizardContent');
        if (!container) return;

        let optionsHTML = q.options.map((opt) => {
            const isSelected = q.type === 'multi'
                ? (answers[q.id] || []).includes(opt.value)
                : answers[q.id] === opt.value;
            const safeValue = opt.value.replace(/'/g, "\\'");
            const safeId = q.id.replace(/'/g, "\\'");
            return `
                <div class="dg-option-card ${isSelected ? 'dg-selected' : ''}" onclick="window.dgHandleAnswer('${safeId}', '${safeValue}', ${q.type === 'multi'})">
                    <div class="dg-option-content">
                        <div class="dg-option-label">${opt.label}</div>
                        ${opt.desc ? `<div class="dg-option-desc">${opt.desc}</div>` : ''}
                    </div>
                    <div class="dg-check-circle">✓</div>
                </div>
            `;
        }).join('');

        const gridClass = q.isMonths ? 'dg-options-grid dg-months' : 'dg-options-grid';
        const backBtn = currentStep > 0 ? `<button class="dg-back-btn" onclick="window.dgPrevStep()">← Atrás</button>` : `<div></div>`;
        const continueBtn = q.type === 'multi' ? `<button class="dg-continue-btn" onclick="window.dgNextStep()">Continuar →</button>` : '<div></div>';

        container.innerHTML = `
            <div class="dg-question-screen">
                <h2 class="dg-question-title">${q.label}</h2>
                <p class="dg-question-sub">${q.sub}</p>
                <div class="${gridClass}">${optionsHTML}</div>
                <div class="dg-nav-container">${backBtn}${continueBtn}</div>
            </div>
        `;
    }

    function showResultScreen() {
        currentStep = totalSteps;
        updateProgressBar();
        updateStepIndicator();

        const rankedPrograms = recommendPrograms();
        const result = rankedPrograms[0] || null;
        const secondaryPrograms = rankedPrograms.slice(1, 3);
        const suggestedTours = buildSuggestedTours(rankedPrograms);
        const container = document.getElementById('dgWizardContent');
        if (!container || !result) return;

        const secondaryHtml = secondaryPrograms.map((program) => `
            <a href="${escapeHtml(program.link || '#')}" class="dg-result-program-card">
                <div class="dg-result-program-card__image"><img src="${escapeHtml(program.image)}" alt="${escapeHtml(program.name)}"></div>
                <div class="dg-result-program-card__body">
                    <span class="dg-result-pill">Score ${program.score}</span>
                    <h3>${escapeHtml(program.name)}</h3>
                    <p>${escapeHtml(program.description)}</p>
                </div>
            </a>
        `).join('');

        const tourHtml = suggestedTours.map((tour) => `
            <a href="${escapeHtml(tour.link || '#')}" class="dg-result-tour-card">
                <div class="dg-result-tour-card__image"><img src="${escapeHtml(tour.image)}" alt="${escapeHtml(tour.title)}"></div>
                <div class="dg-result-tour-card__body">
                    <span class="dg-result-tour-card__program">${escapeHtml(tour.programName)}</span>
                    <h3>${escapeHtml(tour.title)}</h3>
                    <p>${escapeHtml(tour.description || '')}</p>
                    <div class="dg-result-tour-card__meta">
                        <span>${escapeHtml(tour.duration || '')}</span>
                        <span>${escapeHtml(tour.groupSize || '')}</span>
                        <span>${escapeHtml(tour.difficulty || '')}</span>
                    </div>
                </div>
            </a>
        `).join('');

        const reasonsHtml = (result.reasons || []).map((reason) => `<span class="dg-result-pill">${escapeHtml(reason)}</span>`).join('');

        container.innerHTML = `
            <div class="dg-result-screen">
                <div class="dg-result-label">
                    <span class="dg-check-icon">✓</span>
                    <span>Your Expedition Match</span>
                </div>
                <h2 class="dg-result-title">Best match:<br><em>${escapeHtml(result.name)}</em></h2>
                <p class="dg-result-desc">${escapeHtml(result.description)}</p>
                <div class="dg-result-summary">${reasonsHtml}</div>
                <div class="dg-result-card">
                    <div class="dg-result-card-image"><img src="${escapeHtml(result.image)}" alt="${escapeHtml(result.name)}"></div>
                    <div class="dg-result-card-overlay"></div>
                    <div class="dg-result-card-info">
                        <div class="dg-result-card-coords">${escapeHtml(result.coords || '')}</div>
                        <div class="dg-result-card-meta">
                            <span>${escapeHtml(result.duration || '')}</span><span>·</span><span>${escapeHtml(result.groupSize || '')}</span><span>·</span><span>${escapeHtml(result.difficulty || '')}</span>
                        </div>
                    </div>
                </div>
                <div class="dg-result-actions">
                    <a href="${escapeHtml(result.link || '#')}" class="dg-btn-forest">Explore top match</a>
                    <button type="button" class="dg-btn-terracotta" onclick="window.dgOpenTailorMadeModal ? window.dgOpenTailorMadeModal() : null">Plan a tailor-made trip</button>
                </div>
                ${secondaryHtml ? `<div class="dg-result-priority"><h3 class="dg-result-section-title">Other strong matches</h3><div class="dg-result-program-grid">${secondaryHtml}</div></div>` : ''}
                ${tourHtml ? `<div class="dg-result-priority"><h3 class="dg-result-section-title">Suggested tours</h3><div class="dg-result-tour-grid">${tourHtml}</div></div>` : ''}
                <button class="dg-retake-btn" onclick="window.dgResetWizard()">Retake the questionnaire</button>
            </div>
        `;
    }

    // ═══════════════════════════════════════════════════
    // CONTROL DEL MODAL
    // ═══════════════════════════════════════════════════
    function resetWizard() {
        currentStep = 0;
        answers = {};
        renderQuestion();
    }

    function openWizard() {
        console.log('[Wizard] Intentando abrir...');
        const overlay = document.getElementById('dgWizardOverlay');
        if (!overlay) {
            console.error('[Wizard] ERROR: No existe #dgWizardOverlay en el DOM.');
            console.log('[Wizard] ¿Incluiste el shortcode [tour_wizard] en la página?');
            return;
        }
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        resetWizard();
        console.log('[Wizard] Abierto correctamente.');
    }

    function closeWizard() {
        const overlay = document.getElementById('dgWizardOverlay');
        if (overlay) overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
        console.log('[Wizard] Cerrado.');
    }

    // Exponer globales
    window.dgHandleAnswer = handleAnswer;
    window.dgNextStep = nextStep;
    window.dgPrevStep = prevStep;
    window.dgResetWizard = resetWizard;
    window.dgOpenWizard = openWizard;
    window.dgCloseWizard = closeWizard;

    // ═══════════════════════════════════════════════════
    // INICIALIZAR
    // ═══════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[Wizard] JS cargado. Buscando elementos...');

        // Botón propio del shortcode
        const openBtn = document.getElementById('openWizard');
        if (openBtn) {
            openBtn.addEventListener('click', openWizard);
            console.log('[Wizard] Botón #openWizard encontrado.');
        }

        // DELEGACIÓN DE EVENTOS: captura CUALQUIER click en .dg-wizard-trigger
        // Funciona aunque Elementor renderice el botón tarde
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.dg-wizard-trigger');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            console.log('[Wizard] Click detectado en .dg-wizard-trigger');
            openWizard();
        });

        // Cerrar con botón X
        const closeBtn = document.querySelector('.dg-wizard-close');
        if (closeBtn) closeBtn.addEventListener('click', closeWizard);

        // Cerrar con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeWizard();
        });

        // Pre-renderizar primera pregunta
        renderQuestion();
        console.log('[Wizard] Inicializado. Overlay listo.');
    });
})();