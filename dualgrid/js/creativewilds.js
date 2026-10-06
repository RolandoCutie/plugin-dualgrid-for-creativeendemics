(function () {
    var runtime = window.dgCreativeWilds || {};

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
                    return;
                }

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
                            successBox.hidden = false;
                        }
                    })
                    .catch(function (error) {
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
        initServiceLinks();
        initGalleries();
        initContactForms();
    });
})();
