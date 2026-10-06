/**
 * DualGrid — Scroll Animations
 * Preguntas Frecuentes + Testimonios + Tours + Galería + Misión + Valores + Equipo
 */
(function () {
    'use strict';

    function initScrollAnimations() {
        const selectors = [
            '.dg-faqs-header',
            '.dg-faq-item',
            '.tp-inner',
            '.tour-header',
            '.tour-card',
            '.we-program-card',
            '.dg-gallery-item',
            '.mission-section',
            '.values-header',
            '.values-card',
            '.team-header',
            '.team-member'
        ];

        const allElements = [];
        selectors.forEach(function (sel) {
            const nodes = document.querySelectorAll(sel);
            nodes.forEach(function (node) {
                allElements.push({ el: node, selector: sel });
            });
        });

        if (allElements.length === 0) {
            console.log('[DualGrid Scroll] No hay elementos aún, reintentando...');
            return false;
        }

        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -60px 0px',
            threshold: 0.12
        };

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('dg-in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        allElements.forEach(function (item) {
            const el = item.el;

            // Añadir clase base para animación
            el.classList.add('dg-animate-on-scroll');

            // Clases especiales según el tipo de elemento
            if (item.selector === '.we-program-card') {
                const index = Array.from(el.parentNode.children).indexOf(el);
                if (index % 2 === 0) {
                    el.classList.add('we-slide-left');
                } else {
                    el.classList.add('we-slide-right');
                }
            }

            observer.observe(el);
        });

        return true;
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!initScrollAnimations()) {
            setTimeout(initScrollAnimations, 500);
            setTimeout(initScrollAnimations, 1500);
        }
    });

    window.addEventListener('load', function () {
        initScrollAnimations();
    });
})();
