(function () {
    var runtime = window.dgCreativeWilds || {};

    function normalizeLang(lang) {
        lang = String(lang || '').slice(0, 2).toLowerCase();
        return lang === 'es' || lang === 'en' ? lang : '';
    }

    function setLanguageCookie(lang) {
        var cookieName = runtime.langCookieName || 'dg_cw_lang';
        document.cookie = cookieName + '=' + encodeURIComponent(lang) + '; path=/; max-age=31536000; SameSite=Lax';
    }


    function redirectToLanguage(lang) {
        var normalized = normalizeLang(lang);
        if (!normalized) {
            return;
        }

        var currentLang = normalizeLang(runtime.currentLang);
        var url = new URL(window.location.href);
        var queryVar = runtime.langQueryVar || 'dg_cw_lang';

        if (currentLang === normalized && url.searchParams.get(queryVar) === normalized) {
            return;
        }

        url.searchParams.set(queryVar, normalized);
        setLanguageCookie(normalized);
        window.location.assign(url.toString());
    }

    function initReveal() {
        var items = document.querySelectorAll('.dg-cw-reveal');
        if (!items.length) {
            return;
        }

        var observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.3 });

        items.forEach(function (item) {
            observer.observe(item);
        });
    }

    function initHeroes() {
        document.querySelectorAll('[data-dg-cw-hero]').forEach(function (hero) {
            var slides = hero.querySelectorAll('.dg-cw-hero-slide');
            var interval = parseInt(hero.getAttribute('data-interval'), 10) || 6000;
            var index = 0;

            if (slides.length < 2) {
                return;
            }

            window.setInterval(function () {
                slides[index].classList.remove('is-active');
                index = (index + 1) % slides.length;
                slides[index].classList.add('is-active');
            }, interval);
        });
    }

    function selectService(slug, targetId) {
        if (!slug) {
            return;
        }

        var scope = document;
        if (targetId) {
            var section = document.getElementById(targetId);
            if (section) {
                scope = section;
            }
        }

        scope.querySelectorAll('[data-dg-cw-service-select]').forEach(function (select) {
            select.value = slug;
        });
    }

    function initServiceLinks() {
        document.querySelectorAll('[data-dg-cw-service]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                var slug = link.getAttribute('data-dg-cw-service');
                var targetId = link.getAttribute('data-dg-cw-target') || '';
                var section = targetId ? document.getElementById(targetId) : null;
                selectService(slug, targetId);

                if (!section) {
                    return;
                }

                event.preventDefault();
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    }

    function formatCounter(index, total) {
        var current = String(index + 1).padStart(2, '0');
        var max = String(total).padStart(2, '0');
        return current + ' / ' + max;
    }

    function initGalleries() {
        document.querySelectorAll('.dg-cw-gallery').forEach(function (gallery) {
            var items = Array.prototype.slice.call(gallery.querySelectorAll('[data-dg-cw-gallery-item]')).filter(function (item) {
                return !item.disabled;
            });
            var lightbox = gallery.querySelector('[data-dg-cw-lightbox]');
            if (!items.length || !lightbox) {
                return;
            }

            var body = document.body;
            var image = lightbox.querySelector('[data-dg-cw-lightbox-image]');
            var counter = lightbox.querySelector('[data-dg-cw-lightbox-counter]');
            var category = lightbox.querySelector('[data-dg-cw-lightbox-category]');
            var location = lightbox.querySelector('[data-dg-cw-lightbox-location]');
            var brand = lightbox.querySelector('[data-dg-cw-lightbox-brand]');
            var currentIndex = 0;

            function render(index) {
                var item = items[index];
                image.src = item.getAttribute('data-image-full');
                image.alt = item.getAttribute('data-category') || '';
                category.textContent = item.getAttribute('data-category') || '';
                location.textContent = item.getAttribute('data-location') || '';
                brand.textContent = item.getAttribute('data-brand') || '';
                counter.textContent = formatCounter(index, items.length);
            }

            function open(index) {
                currentIndex = index;
                render(currentIndex);
                lightbox.hidden = false;
                body.classList.add('dg-cw-lock-scroll');
            }

            function close() {
                lightbox.hidden = true;
                body.classList.remove('dg-cw-lock-scroll');
            }

            function next(step) {
                currentIndex = (currentIndex + step + items.length) % items.length;
                render(currentIndex);
            }

            items.forEach(function (item, index) {
                item.addEventListener('click', function () {
                    open(index);
                });
            });

            lightbox.querySelector('[data-dg-cw-lightbox-close]').addEventListener('click', close);
            lightbox.querySelector('[data-dg-cw-lightbox-prev]').addEventListener('click', function () {
                next(-1);
            });
            lightbox.querySelector('[data-dg-cw-lightbox-next]').addEventListener('click', function () {
                next(1);
            });

            lightbox.addEventListener('click', function (event) {
                if (event.target === lightbox) {
                    close();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (lightbox.hidden) {
                    return;
                }

                if (event.key === 'Escape') {
                    close();
                } else if (event.key === 'ArrowRight') {
                    next(1);
                } else if (event.key === 'ArrowLeft') {
                    next(-1);
                }
            });
        });
    }

    function initLanguageSwitchers() {
        var queryVar = runtime.langQueryVar || 'dg_cw_lang';
        var queryLang = normalizeLang(new URL(window.location.href).searchParams.get(queryVar));
        var currentLang = queryLang || normalizeLang(runtime.currentLang) || 'en';

        if (queryLang) {
            setLanguageCookie(queryLang);
        }

        document.querySelectorAll('[data-dg-cw-lang-switch]').forEach(function (trigger) {
            var triggerLang = normalizeLang(trigger.getAttribute('data-dg-cw-lang-switch'));
            if (!triggerLang) {
                return;
            }

            trigger.classList.toggle('is-active', triggerLang === currentLang);
            trigger.setAttribute('aria-pressed', triggerLang === currentLang ? 'true' : 'false');

            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                redirectToLanguage(triggerLang);
            });
        });
    }

    function initLanguageToggleAppearance() {
        var toggles = document.querySelectorAll('.dg-cw-language-toggle');
        if (!toggles.length) {
            return;
        }

        function syncAppearance() {
            var isSticky = window.scrollY > 10;

            toggles.forEach(function (toggle) {
                toggle.classList.toggle('is-sticky', isSticky);
            });
        }

        syncAppearance();
        window.addEventListener('scroll', syncAppearance, { passive: true });
    }

    function initStickyHeaderFix() {
        var stickyContainer = document.querySelector('.ct-sticky-container');
        if (!stickyContainer) {
            return;
        }
        var stickyRow = stickyContainer.querySelector('[data-row="middle"]');
        var stickyInnerContainer = stickyContainer.querySelector('[data-row="middle"] .ct-container');

        function applyCompactHeader() {
            var isMobile = window.matchMedia('(max-width: 767px)').matches;
            var isTablet = window.matchMedia('(max-width: 1024px)').matches;
            var stickyInner = stickyContainer.querySelector('[data-sticky="shrink"]');
            if (!stickyInner) {
                return;
            }
            var headerHeight = '184px';
            var headerPadding = '18px';

            if (isMobile) {
                headerHeight = '84px';
                headerPadding = '4px';
            } else if (isTablet) {
                headerHeight = '72px';
                headerPadding = '6px';
            }

            stickyContainer.style.height = headerHeight;
            stickyContainer.style.minHeight = headerHeight;
            stickyInner.style.minHeight = headerHeight;
            stickyInner.style.height = headerHeight;

            if (stickyRow) {
                stickyRow.style.minHeight = headerHeight;
                stickyRow.style.height = headerHeight;
            }

            if (stickyInnerContainer) {
                stickyInnerContainer.style.minHeight = headerHeight;
                stickyInnerContainer.style.height = headerHeight;
                stickyInnerContainer.style.paddingTop = headerPadding;
                stickyInnerContainer.style.paddingBottom = headerPadding;
            }
        }

        applyCompactHeader();
        window.addEventListener('resize', applyCompactHeader, { passive: true });
        window.addEventListener('load', applyCompactHeader, { passive: true });
    }

    function initContactForms() {
        document.querySelectorAll('[data-dg-cw-contact-form]').forEach(function (form) {
            var errorBox = form.parentElement.querySelector('[data-dg-cw-contact-error]');
            var successBox = form.parentElement.querySelector('[data-dg-cw-contact-success]');
            var resetButton = form.parentElement.querySelector('[data-dg-cw-contact-reset]');
            var submitLabel = form.querySelector('[data-dg-cw-submit-label]');
            var defaultLabel = form.getAttribute('data-send-label') || '';
            var sendingLabel = form.getAttribute('data-sending-label') || defaultLabel;

            function resetState() {
                form.hidden = false;
                form.parentElement.classList.remove('is-complete');
                form.classList.remove('is-submitting');
                if (submitLabel) {
                    submitLabel.textContent = defaultLabel;
                }
                if (errorBox) {
                    errorBox.hidden = true;
                    errorBox.textContent = '';
                }
                if (successBox) {
                    successBox.hidden = true;
                }
                form.reset();
            }

            if (resetButton) {
                resetButton.addEventListener('click', resetState);
            }

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                if (!runtime.ajaxUrl || !runtime.nonce) {
                    if (errorBox) {
                        errorBox.hidden = false;
                        errorBox.textContent = form.getAttribute('data-error-message') || 'Request failed';
                    }
                    return;
                }

                form.parentElement.classList.remove('is-complete');
                form.classList.add('is-submitting');
                if (submitLabel) {
                    submitLabel.textContent = sendingLabel;
                }
                if (errorBox) {
                    errorBox.hidden = true;
                    errorBox.textContent = '';
                }

                var formData = new FormData(form);
                formData.append('action', 'dg_cw_submit_contact');
                formData.append('nonce', runtime.nonce);

                fetch(runtime.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                    .then(function (response) {
                        return response.json().then(function (payload) {
                            return { ok: response.ok, payload: payload };
                        });
                    })
                    .then(function (result) {
                        form.classList.remove('is-submitting');
                        if (submitLabel) {
                            submitLabel.textContent = defaultLabel;
                        }

                        if (!result.ok || !result.payload.success) {
                            throw new Error(
                                result.payload && result.payload.data && result.payload.data.message
                                    ? result.payload.data.message
                                    : form.getAttribute('data-error-message') || 'Request failed'
                            );
                        }

                        form.hidden = true;
                        if (successBox) {
                            var title = successBox.querySelector('[data-dg-cw-success-title]');
                            var message = successBox.querySelector('[data-dg-cw-success-message]');
                            if (title && result.payload.data.title) {
                                title.textContent = result.payload.data.title;
                            }
                            if (message && result.payload.data.message) {
                                message.textContent = result.payload.data.message;
                            }
                            if (resetButton && result.payload.data.resetLabel) {
                                resetButton.textContent = result.payload.data.resetLabel;
                            }
                            successBox.hidden = false;
                        }
                        form.parentElement.classList.add('is-complete');
                    })
                    .catch(function (error) {
                        form.parentElement.classList.remove('is-complete');
                        form.classList.remove('is-submitting');
                        if (submitLabel) {
                            submitLabel.textContent = defaultLabel;
                        }
                        if (errorBox) {
                            errorBox.hidden = false;
                            errorBox.textContent = error.message;
                        }
                    });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initReveal();
        initHeroes();
        initLanguageSwitchers();
        initLanguageToggleAppearance();
        initStickyHeaderFix();
        initServiceLinks();
        initGalleries();
        initContactForms();

    });
})();
