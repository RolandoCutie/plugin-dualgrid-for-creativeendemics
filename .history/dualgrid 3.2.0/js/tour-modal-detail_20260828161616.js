/* === js/tour-modal.js === (CORREGIDO — v1.1.0-fixes)
   Modal de Detalles de Tour — lógica completa
   Cambios: geTourModal, setMapData dentro del IIFE,
            data-attributes del mapa seteados en renderTourModal,
            código suelto eliminado.
   ────────────────────────────────────────── */

(function() {
    'use strict';

    var tourModalConfig = window.geTourModal || {};

    var modal = document.getElementById('tour-details-modal');
    var galleryIndex = 0;
    var galleryImages = [];
    var tourMap = null;
    var tourMapMarkers = [];

    // Iconos SVG inline para reutilizar
    var ICONS = {
        mapPin: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
        calendar: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        users: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        mountain: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15.5c0 1.5-.5 2-2 2s-2-1-2-2 1-2 2-2 2 .5 2 2z"/><path d="M12 2L2 22h20L12 2z"/><path d="M12 2l-5 10h10L12 2z"/></svg>',
        tag: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
        clock: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        globe: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
        check: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>',
        xmark: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    };

    // ─── 1. Abrir modal ───
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.we-btn-details');
        if (!btn) return;
        e.preventDefault();
        var tourId = btn.getAttribute('data-tour-id');
        if (!tourId) return;

        fetchTourData(tourId).then(function(data) {
            renderTourModal(data);
            openTourModal();
        }).catch(function(err) {
            console.error('Error cargando tour:', err);
            alert('No se pudieron cargar los detalles del tour. Intenta de nuevo.');
        });
    });

    function fetchTourData(tourId) {
        return new Promise(function(resolve, reject) {
            if (!tourModalConfig.ajaxUrl || !tourModalConfig.nonce) {
                reject('Configuracion AJAX faltante para el modal de tours');
                return;
            }

            var formData = new FormData();
            formData.append('action', 'we_get_tour_details');
            formData.append('tour_id', tourId);
            formData.append('nonce', tourModalConfig.nonce);

            fetch(tourModalConfig.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(r) { return r.json(); })
            .then(function(json) {
                if (json.success) {
                    resolve(json.data);
                } else {
                    reject(json.data || 'Error desconocido');
                }
            })
            .catch(reject);
        });
    }

    // ─── 2. Renderizar modal ───
    function renderTourModal(data) {
        // Header
        document.getElementById('tour-breadcrumb-name').textContent = data.name || 'Tour';
        document.getElementById('tour-header-coords').textContent = data.coords || '';
        document.getElementById('tour-modal-title').textContent = data.name || '';
        document.getElementById('tour-modal-short-desc').textContent = data.short_description || '';

        // Atributos
        var attrs = [
            { icon: ICONS.mapPin, label: 'Location', value: data.city || '', link: '#section-locations', linkText: 'Show on map' },
            { icon: ICONS.calendar, label: 'Duration', value: data.duration || '' },
            { icon: ICONS.users, label: 'Group size', value: data.max_people ? 'Up to ' + data.max_people + ' people' : '' },
            { icon: ICONS.mountain, label: 'Difficulty', value: data.difficulty || '' },
            { icon: ICONS.tag, label: 'Price', value: data.price || '' },
            { icon: ICONS.clock, label: 'Next departure', value: data.next_date || '' },
            { icon: ICONS.users, label: 'Tour type', value: data.tour_type || '' },
            { icon: ICONS.mountain, label: 'Pace', value: data.pace || '' },
            { icon: ICONS.globe, label: 'Language', value: data.language || '' },
            { icon: ICONS.mapPin, label: 'Base city', value: data.city || '' },
        ];
        var attrsHtml = attrs.map(function(a) {
            if (!a.value) return '';
            var val = a.link ? a.value + ' <a href="' + a.link + '">' + a.linkText + '</a>' : a.value;
            return '<div class="tour-attribute">' + a.icon +
                '<div><span class="tour-attribute-label">' + a.label + '</span><span class="tour-attribute-value">' + val + '</span></div></div>';
        }).join('');
        document.getElementById('tour-attributes-list').innerHTML = attrsHtml;

        // Galería
        galleryImages = Array.isArray(data.gallery) ? data.gallery.filter(function(u) { return u; }) : [];
        if (data.main_image && galleryImages.indexOf(data.main_image) === -1) {
            galleryImages.unshift(data.main_image);
        }
        if (galleryImages.length === 0 && data.main_image) {
            galleryImages = [data.main_image];
        }
        galleryIndex = 0;
        updateGallery();

        // Thumbnails
        var thumbsHtml = galleryImages.map(function(img, i) {
            return '<div class="gallery-thumb ' + (i === 0 ? 'active' : '') + '" data-index="' + i + '"><img src="' + img + '" alt=""></div>';
        }).join('');
        document.getElementById('gallery-thumbnails').innerHTML = thumbsHtml;

        // Gallery grid
        var gridHtml = galleryImages.map(function(img) {
            return '<img src="' + img + '" alt="" loading="lazy">';
        }).join('');
        document.getElementById('tour-gallery-grid').innerHTML = gridHtml;

        // About
        var aboutHtml = '';
        if (Array.isArray(data.about)) {
            aboutHtml = data.about.map(function(p) {
                return '<p>' + escapeHtml(p) + '</p>';
            }).join('');
        }
        document.getElementById('tour-about-content').innerHTML = aboutHtml;

        // Wildlife
        document.getElementById('tour-wildlife-label-text').textContent = data.wildlife_label || 'Wildlife you can spot';
        var wildlifeHtml = '';
        if (Array.isArray(data.wildlife)) {
            wildlifeHtml = data.wildlife.map(function(w) {
                var img = w.image || (galleryImages[0] || '');
                var badge = w.is_target === '1' || w.is_target === true ? '<span class="tour-wildlife-badge">Target</span>' : '';
                return '<div class="tour-wildlife-item">' +
                    '<img src="' + img + '" alt="" class="tour-wildlife-thumb">' +
                    '<div class="tour-wildlife-info">' +
                    '<span class="tour-wildlife-name">' + escapeHtml(w.name) + '</span>' +
                    '<span class="tour-wildlife-scientific">' + escapeHtml(w.scientific) + '</span>' +
                    badge + '</div></div>';
            }).join('');
        }
        document.getElementById('tour-wildlife-grid').innerHTML = wildlifeHtml;

        // Organizer
        var orgHtml = '';
        if (data.organizer && data.organizer.name) {
            var logo = data.organizer.logo || '';
            var logoHtml = logo ?
                '<img src="' + logo + '" alt="" class="tour-org-logo">' :
                '<div class="tour-org-logo-placeholder">' + initials(data.organizer.name) + '</div>';
            orgHtml = logoHtml +
                '<div><div class="tour-org-name">' + escapeHtml(data.organizer.name) + '</div>' +
                '<div class="tour-org-role">' + escapeHtml(data.organizer.role) + '</div></div>';
        }
        document.getElementById('tour-organizer-content').innerHTML = orgHtml;

        // Guides
        var guidesHtml = '';
        if (Array.isArray(data.guides)) {
            guidesHtml = data.guides.map(function(g) {
                return '<div class="tour-guide-item">' +
                    '<div class="tour-guide-name">' + escapeHtml(g.name) + '</div>' +
                    '<div class="tour-guide-lang">' + escapeHtml(g.language) + '</div>' +
                    '<div class="tour-guide-role">' + escapeHtml(g.role) + '</div>' +
                    '</div>';
            }).join('');
        }
        document.getElementById('tour-guides-content').innerHTML = guidesHtml;

        // Itinerary
        var itinCount = Array.isArray(data.itinerary) ? data.itinerary.length : 0;
        document.getElementById('itinerary-toggle-text').textContent = itinCount + ' days · Click to view full itinerary';
        var itinHtml = '';
        if (Array.isArray(data.itinerary)) {
            itinHtml = data.itinerary.map(function(day) {
                return '<div class="tour-itinerary-day">' +
                    '<div class="tour-day-label">' + escapeHtml(day.day) + '</div>' +
                    '<div><div class="tour-day-title">' + escapeHtml(day.title) + '</div>' +
                    '<div class="tour-day-desc">' + escapeHtml(day.description) + '</div></div></div>';
            }).join('');
        }
        document.getElementById('itinerary-content').innerHTML = itinHtml;
        document.getElementById('itinerary-content').classList.remove('open');
        var itinToggleIcon = document.querySelector('#itinerary-toggle .toggle-icon');
        if (itinToggleIcon) itinToggleIcon.classList.remove('rotated');

        // Locations
        var locHtml = '';
        if (Array.isArray(data.locations)) {
            locHtml = data.locations.map(function(loc) {
                return '<div class="tour-location-item">' + ICONS.mapPin +
                    '<div><div class="tour-location-name">' + escapeHtml(loc.name) + ' — ' + escapeHtml(loc.region) + '</div>' +
                    '<div class="tour-location-desc">' + escapeHtml(loc.description) + '</div></div></div>';
            }).join('');
        }
        document.getElementById('tour-locations-list').innerHTML = locHtml;

        // === FIX CRÍTICO: setear data-attributes del mapa ===
        var mapEl = document.getElementById('tour-map');
        if (mapEl) {
            mapEl.setAttribute('data-lat', data.coords_lat || '9.58');
            mapEl.setAttribute('data-lng', data.coords_lng || '-83.81');
            mapEl.setAttribute('data-locations', JSON.stringify(data.locations || []));
        }

        // Availability
        var availHtml = '';
        if (Array.isArray(data.availability)) {
            availHtml = data.availability.map(function(a) {
                var statusClass = (a.status && (a.status.toLowerCase().indexOf('left') !== -1 || a.status.toLowerCase().indexOf('ahead') !== -1)) ?
                    'tour-availability-badge--urgent' : 'tour-availability-badge--open';
                return '<div class="tour-availability-item">' +
                    '<div class="tour-availability-date">' + ICONS.calendar + escapeHtml(a.date) + '</div>' +
                    '<span class="tour-availability-badge ' + statusClass + '">' + escapeHtml(a.status) + '</span></div>';
            }).join('');
        }
        document.getElementById('tour-availability-list').innerHTML = availHtml;
        document.getElementById('tour-price-value').textContent = data.price || '';

        // Includes
        var incHtml = '';
        if (Array.isArray(data.includes)) {
            incHtml = data.includes.map(function(item) {
                return '<div class="tour-check-item"><span class="tour-check-circle">' + ICONS.check + '</span><span>' + escapeHtml(item) + '</span></div>';
            }).join('');
        }
        document.getElementById('tour-includes-list').innerHTML = incHtml;

        // Excludes
        var excHtml = '';
        if (Array.isArray(data.excludes)) {
            excHtml = data.excludes.map(function(item) {
                return '<div class="tour-check-item"><span class="tour-check-circle tour-check-circle--exclude">' + ICONS.xmark + '</span><span>' + escapeHtml(item) + '</span></div>';
            }).join('');
        }
        document.getElementById('tour-excludes-list').innerHTML = excHtml;

        // Transport
        document.getElementById('tour-transport-value').textContent = data.transport || '';
        document.getElementById('tour-accommodation-value').textContent = data.accommodation || '';
        document.getElementById('tour-pace-value').textContent = data.pace || '';

        // Policies
        document.getElementById('tour-policies-text').innerHTML = data.policies ? '<p>' + escapeHtml(data.policies).replace(/\n/g, '</p><p>') + '</p>' : '';

        // FAQ
        var faqHtml = '';
        if (Array.isArray(data.faq)) {
            faqHtml = data.faq.map(function(f, i) {
                return '<div class="tour-faq-item">' +
                    '<button class="faq-item-toggle" data-index="' + i + '">' +
                    '<span>' + escapeHtml(f.question) + '</span>' +
                    '<svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>' +
                    '</button>' +
                    '<div class="faq-item-content"><div>' + escapeHtml(f.answer).replace(/\n/g, '<br>') + '</div></div>' +
                    '</div>';
            }).join('');
        }
        document.getElementById('tour-faq-list').innerHTML = faqHtml;

        // Inquiry links
        var inquiryLink = document.getElementById('tour-btn-inquiry-header');
        if (inquiryLink) inquiryLink.onclick = function() {
            document.getElementById('section-inquiry').scrollIntoView({ behavior: 'smooth' });
        };
        var priceLink = document.getElementById('tour-btn-inquiry-price');
        if (priceLink) priceLink.href = '#section-inquiry';
    }

    // ─── 3. Galería ───
    function updateGallery() {
        if (galleryImages.length === 0) return;
        var idx = (galleryIndex % galleryImages.length + galleryImages.length) % galleryImages.length;
        var img = document.getElementById('gallery-main-img');
        if (img) img.src = galleryImages[idx];
        var counter = document.getElementById('gallery-counter');
        if (counter) counter.textContent = (idx + 1) + ' / ' + galleryImages.length;
        document.querySelectorAll('.gallery-thumb').forEach(function(thumb, i) {
            thumb.classList.toggle('active', i === idx);
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('#gallery-prev')) {
            galleryIndex--;
            updateGallery();
        }
        if (e.target.closest('#gallery-next')) {
            galleryIndex++;
            updateGallery();
        }
        var thumb = e.target.closest('.gallery-thumb');
        if (thumb) {
            galleryIndex = parseInt(thumb.getAttribute('data-index'), 10);
            updateGallery();
        }
    });

    // ─── 4. Abrir / Cerrar modal ───
    function openTourModal() {
        if (!modal) return;
        modal.style.display = 'block';
        modal.scrollTop = 0;
        document.body.style.overflow = 'hidden';
        setTimeout(function() {
            initTourMap();
        }, 150);
    }

    function closeTourModal() {
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
        if (tourMap) {
            tourMap.remove();
            tourMap = null;
        }
    }

    var closeBtn = document.getElementById('tour-modal-close');
    if (closeBtn) closeBtn.addEventListener('click', closeTourModal);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeTourModal();
    });

    // ─── 5. Mapa Leaflet ───
    function initTourMap() {
        var mapEl = document.getElementById('tour-map');
        if (!mapEl || typeof L === 'undefined') return;
        if (tourMap) { tourMap.remove(); tourMap = null; }

        var lat = parseFloat(mapEl.getAttribute('data-lat')) || 9.58;
        var lng = parseFloat(mapEl.getAttribute('data-lng')) || -83.81;
        var locs = [];
        try {
            locs = JSON.parse(mapEl.getAttribute('data-locations') || '[]');
        } catch(e) {}

        tourMap = L.map('tour-map', { scrollWheelZoom: false }).setView([lat, lng], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(tourMap);

        locs.forEach(function(loc) {
            if (loc.lat && loc.lng) {
                L.marker([parseFloat(loc.lat), parseFloat(loc.lng)]).addTo(tourMap)
                    .bindPopup('<strong>' + escapeHtml(loc.name) + '</strong><br>' + escapeHtml(loc.region));
            }
        });
    }

    // ─── 6. Jump To Pills ───
    document.addEventListener('click', function(e) {
        var pill = e.target.closest('.tour-jump-to-pill');
        if (!pill) return;
        var sectionId = 'section-' + pill.getAttribute('data-section');
        var section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        document.querySelectorAll('.tour-jump-to-pill').forEach(function(p) { p.classList.remove('active'); });
        pill.classList.add('active');
    });

    // ─── 7. Scroll tracking ───
    if (modal) {
        modal.addEventListener('scroll', function() {
            var scrollTop = modal.scrollTop + 120;
            var sections = modal.querySelectorAll('[id^="section-"]');
            var currentId = null;
            sections.forEach(function(sec) {
                if (sec.offsetTop <= scrollTop) {
                    currentId = sec.id.replace('section-', '');
                }
            });
            if (currentId) {
                document.querySelectorAll('.tour-jump-to-pill').forEach(function(p) {
                    p.classList.toggle('active', p.getAttribute('data-section') === currentId);
                });
            }
        });
    }

    // ─── 8. Itinerary accordion ───
    document.addEventListener('click', function(e) {
        var toggle = e.target.closest('#itinerary-toggle');
        if (!toggle) return;
        var content = document.getElementById('itinerary-content');
        var icon = toggle.querySelector('.toggle-icon');
        if (content.classList.contains('open')) {
            content.classList.remove('open');
            if (icon) icon.classList.remove('rotated');
        } else {
            content.classList.add('open');
            if (icon) icon.classList.add('rotated');
            setTimeout(function() { if(tourMap) tourMap.invalidateSize(); }, 400);
        }
    });

    // ─── 9. FAQ accordion ───
    document.addEventListener('click', function(e) {
        var toggle = e.target.closest('.faq-item-toggle');
        if (!toggle) return;
        var content = toggle.nextElementSibling;
        var icon = toggle.querySelector('.toggle-icon');
        var isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

        // Cerrar todos
        document.querySelectorAll('.faq-item-content').forEach(function(c) { c.style.maxHeight = null; });
        document.querySelectorAll('.faq-item-toggle .toggle-icon').forEach(function(i) { i.classList.remove('rotated'); });

        if (!isOpen) {
            content.style.maxHeight = content.scrollHeight + 'px';
            if (icon) icon.classList.add('rotated');
        }
    });

    // ─── Helpers ───
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function initials(name) {
        return name.split(' ').map(function(w) { return w[0]; }).join('').substring(0, 2).toUpperCase();
    }

    // Exponer funciones globales para el mapa (por si acaso)
    window.geTourModal = tourModalConfig;
    window.geTourModal.setMapData = function(lat, lng, locations) {
        var mapEl = document.getElementById('tour-map');
        if (mapEl) {
            mapEl.setAttribute('data-lat', lat);
            mapEl.setAttribute('data-lng', lng);
            mapEl.setAttribute('data-locations', JSON.stringify(locations));
        }
    };
})();
