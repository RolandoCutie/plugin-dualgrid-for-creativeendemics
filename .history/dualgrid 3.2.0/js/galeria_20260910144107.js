(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const galleries = document.querySelectorAll('.dg-gallery-wrapper');

        galleries.forEach(function (gallery) {
            const filters    = gallery.querySelectorAll('.dg-filter-btn');
            const items      = gallery.querySelectorAll('.dg-gallery-item');
            const lightbox   = gallery.querySelector('.dg-lightbox');
            const lbImg      = lightbox.querySelector('.dg-lightbox-img');
            const lbTitle    = lightbox.querySelector('.dg-lightbox-title');
            const lbSubtitle = lightbox.querySelector('.dg-lightbox-subtitle');
            const closeBtn   = lightbox.querySelector('.dg-lightbox-close');
            const prevBtn    = lightbox.querySelector('.dg-lightbox-prev');
            const nextBtn    = lightbox.querySelector('.dg-lightbox-next');

            let currentIndex = 0;
            let visibleItems = [];

            /* ---------- Animación de entrada con stagger (para filtrado) ---------- */
            function revealItems(itemsToReveal) {
                itemsToReveal.forEach(function (item, index) {
                    item.classList.remove('dg-in-view');
                    // Forzar reflow para reiniciar animación
                    void item.offsetWidth;
                    // Aplicar delay escalonado
                    item.style.animationDelay = (index * 0.08) + 's';
                    item.classList.add('dg-in-view');
                });
            }

            /* ---------- Filtrado con re-animación ---------- */
            filters.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const filter = this.dataset.filter;

                    filters.forEach(function (b) { b.classList.remove('active'); });
                    this.classList.add('active');

                    // Primero ocultamos todo
                    items.forEach(function (item) {
                        item.classList.remove('dg-in-view');
                        item.classList.add('hidden');
                    });

                    // Pequeña pausa para que el "display:none" aplique visualmente
                    setTimeout(function () {
                        const filtered = Array.from(items).filter(function (item) {
                            const cats = item.dataset.categories || '';
                            return filter === 'all' || cats.split(' ').includes(filter);
                        });

                        // Mostrar filtrados
                        filtered.forEach(function (item) {
                            item.classList.remove('hidden');
                        });

                        // Animar entrada escalonada
                        revealItems(filtered);
                    }, 50);
                });
            });

            function updateVisible() {
                visibleItems = Array.from(items).filter(function (item) {
                    return !item.classList.contains('hidden');
                });
            }

            /* ---------- Lightbox ---------- */
            items.forEach(function (item) {
                item.addEventListener('click', function () {
                    updateVisible();
                    currentIndex = visibleItems.indexOf(this);
                    openLightbox(this);
                });
            });

            function openLightbox(item) {
                const nextSrc = item.dataset.full || item.querySelector('img').src;
                const nextTitle = item.dataset.title || '';
                const nextSubtitle = item.dataset.subtitle || '';

                lbImg.style.opacity = '0';
                lbImg.style.transform = 'scale(0.92) translateY(20px)';
                lbImg.removeAttribute('src');
                lbImg.alt = nextTitle;

                lbTitle.textContent = nextTitle;
                lbSubtitle.textContent = nextSubtitle;

                lightbox.classList.add('active');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';

                const preloader = new Image();

                preloader.onload = function () {
                    lbImg.src = nextSrc;

                    requestAnimationFrame(function () {
                        lbImg.style.opacity = '1';
                        lbImg.style.transform = 'scale(1) translateY(0)';
                    });
                };

                preloader.src = nextSrc;
            }

            function closeLightbox() {
                lightbox.classList.remove('active');
                lightbox.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                lbImg.style.opacity = '0';
                lbImg.style.transform = 'scale(0.92) translateY(20px)';
            }

            function showNext(e) {
                e.stopPropagation();
                if (visibleItems.length <= 1) return;
                currentIndex = (currentIndex + 1) % visibleItems.length;
                openLightbox(visibleItems[currentIndex]);
            }

            function showPrev(e) {
                e.stopPropagation();
                if (visibleItems.length <= 1) return;
                currentIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
                openLightbox(visibleItems[currentIndex]);
            }

            closeBtn.addEventListener('click', closeLightbox);
            nextBtn.addEventListener('click', showNext);
            prevBtn.addEventListener('click', showPrev);

            lightbox.addEventListener('click', function (e) {
                if (e.target === lightbox) closeLightbox();
            });

            document.addEventListener('keydown', function (e) {
                if (!lightbox.classList.contains('active')) return;
                if (e.key === 'Escape')     closeLightbox();
                if (e.key === 'ArrowRight') showNext(e);
                if (e.key === 'ArrowLeft')  showPrev(e);
            });
        });
    });
})();
