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
    var currentTourContext = {
        id: '',
        name: '',
        permalink: ''
    };

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
        currentTourContext.id = tourId;

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
        currentTourContext.name = data.name || '';
        currentTourContext.permalink = data.permalink || '';

        var aboutItems = Array.isArray(data.about) ? data.about.filter(hasText) : [];
        var wildlifeItems = Array.isArray(data.wildlife) ? data.wildlife.filter(function(item) {
            return item && (hasText(item.name) || hasText(item.scientific) || hasText(item.image));
        }) : [];
        var guideItems = Array.isArray(data.guides) ? data.guides.filter(function(item) {
            return item && (hasText(item.name) || hasText(item.language) || hasText(item.role) || hasText(item.photo));
        }) : [];
        var itineraryItems = Array.isArray(data.itinerary) ? data.itinerary.filter(function(item) {
            return item && (hasText(item.day) || hasText(item.title) || hasText(item.description));
        }) : [];
        var locationItems = Array.isArray(data.locations) ? data.locations.filter(function(item) {
            return item && (hasText(item.name) || hasText(item.region) || hasText(item.description) || hasText(item.lat) || hasText(item.lng));
        }) : [];
        var availabilityItems = Array.isArray(data.availability) ? data.availability.filter(function(item) {
            return item && (hasText(item.date) || hasText(item.status));
        }) : [];
        var includeItems = Array.isArray(data.includes) ? data.includes.filter(hasText) : [];
        var excludeItems = Array.isArray(data.excludes) ? data.excludes.filter(hasText) : [];
        var faqItems = Array.isArray(data.faq) ? data.faq.filter(function(item) {
            return item && (hasText(item.question) || hasText(item.answer));
        }) : [];
        var organizer = data.organizer || {};
        var hasOrganizer = hasText(organizer.name) || hasText(organizer.role) || hasText(organizer.logo);
        var hasGuides = guideItems.length > 0;
        var hasLocations = locationItems.length > 0;
        var hasPrice = hasText(data.price);

        var breadcrumbName = document.getElementById('tour-breadcrumb-name');
        if (breadcrumbName) {
            breadcrumbName.textContent = currentTourContext.name || 'Tour';
            breadcrumbName.href = currentTourContext.permalink || breadcrumbName.getAttribute('href') || '#';
        }

        var coordsEl = document.getElementById('tour-header-coords');
        if (coordsEl) {
            coordsEl.textContent = data.coords || '';
            setElementVisibility(coordsEl, hasText(data.coords));
        }

        document.getElementById('tour-modal-title').textContent = currentTourContext.name || '';

        var shortDescEl = document.getElementById('tour-modal-short-desc');
        if (shortDescEl) {
            shortDescEl.textContent = data.short_description || '';
            setElementVisibility(shortDescEl, hasText(data.short_description));
        }

        var attrs = [
            { icon: ICONS.mapPin, label: 'Ubicación', value: data.city || '', link: hasLocations ? '#section-locations' : '', linkText: 'Ver en el mapa' },
            { icon: ICONS.calendar, label: 'Duración', value: data.duration || '' },
            { icon: ICONS.users, label: 'Grupo', value: hasText(data.max_people) ? 'Hasta ' + data.max_people + ' personas' : '' },
            { icon: ICONS.mountain, label: 'Dificultad', value: data.difficulty || '' },
            { icon: ICONS.tag, label: 'Precio', value: data.price || '' },
            { icon: ICONS.clock, label: 'Próxima salida', value: data.next_date || '' },
            { icon: ICONS.users, label: 'Tipo de tour', value: data.tour_type || '' },
            { icon: ICONS.mountain, label: 'Ritmo', value: data.pace || '' },
            { icon: ICONS.globe, label: 'Idioma', value: data.language || '' },
            { icon: ICONS.mapPin, label: 'Ciudad base', value: data.city || '' }
        ];

        var attrsHtml = attrs.map(function(attr) {
            if (!hasText(attr.value)) {
                return '';
            }

            var valueHtml = escapeHtml(attr.value);

            if (attr.link) {
                valueHtml += ' <a href="' + attr.link + '">' + escapeHtml(attr.linkText) + '</a>';
            }

            return '<div class="tour-attribute">' + attr.icon +
                '<div><span class="tour-attribute-label">' + escapeHtml(attr.label) + '</span><span class="tour-attribute-value">' + valueHtml + '</span></div></div>';
        }).join('');

        document.getElementById('tour-attributes-list').innerHTML = attrsHtml;
        setElementVisibility(document.getElementById('tour-attributes-list'), !!attrsHtml);

        galleryImages = Array.isArray(data.gallery) ? data.gallery.filter(hasText) : [];
        if (hasText(data.main_image) && galleryImages.indexOf(data.main_image) === -1) {
            galleryImages.unshift(data.main_image);
        }
        if (galleryImages.length === 0 && hasText(data.main_image)) {
            galleryImages = [data.main_image];
        }

        galleryIndex = 0;
        updateGallery();

        var headerRight = modal ? modal.querySelector('.tour-header-right') : null;
        var headerGrid = modal ? modal.querySelector('.tour-header-grid') : null;
        setElementVisibility(headerRight, galleryImages.length > 0);
        if (headerGrid) {
            headerGrid.classList.toggle('tour-header-grid--single', galleryImages.length === 0);
        }

        var thumbsHtml = galleryImages.map(function(img, i) {
            var thumbAlt = currentTourContext.name ? 'Vista ' + (i + 1) + ' de ' + currentTourContext.name : 'Vista de la expedición';
            return '<div class="gallery-thumb ' + (i === 0 ? 'active' : '') + '" data-index="' + i + '"><img src="' + img + '" alt="' + escapeAttribute(thumbAlt) + '"></div>';
        }).join('');
        document.getElementById('gallery-thumbnails').innerHTML = thumbsHtml;
        setElementVisibility(document.getElementById('gallery-thumbnails'), galleryImages.length > 1);
        setElementVisibility(document.getElementById('gallery-prev'), galleryImages.length > 1, 'flex');
        setElementVisibility(document.getElementById('gallery-next'), galleryImages.length > 1, 'flex');
        setSectionVisibility('gallery', galleryImages.length > 0);

        var gridHtml = galleryImages.map(function(img, index) {
            var caption = currentTourContext.name ? currentTourContext.name + ' · Foto ' + (index + 1) : 'Imagen de la expedición';
            return '<img src="' + img + '" alt="' + escapeAttribute(caption) + '" loading="lazy" data-tour-lightbox="1" data-lightbox-src="' + img + '" data-lightbox-caption="' + escapeAttribute(caption) + '">';
        }).join('');
        document.getElementById('tour-gallery-grid').innerHTML = gridHtml;

        var aboutHtml = aboutItems.map(function(paragraph) {
            return '<p>' + escapeHtml(paragraph) + '</p>';
        }).join('');
        document.getElementById('tour-about-content').innerHTML = aboutHtml;
        setSectionVisibility('about', !!aboutHtml);

        document.getElementById('tour-wildlife-label-text').textContent = data.wildlife_label || 'Fauna que puedes observar';
        var wildlifeHtml = wildlifeItems.map(function(item) {
            var imageUrl = item.image || (galleryImages[0] || '');
            var captionParts = [];
            if (hasText(item.name)) {
                captionParts.push(item.name);
            }
            if (hasText(item.scientific)) {
                captionParts.push(item.scientific);
            }
            var badge = item.is_target === '1' || item.is_target === true ? '<span class="tour-wildlife-badge">Objetivo</span>' : '';
            var imageHtml = imageUrl
                ? '<img src="' + imageUrl + '" alt="' + escapeAttribute(item.name || 'Fauna de la expedición') + '" class="tour-wildlife-thumb" data-tour-lightbox="1" data-lightbox-src="' + imageUrl + '" data-lightbox-caption="' + escapeAttribute(captionParts.join(' · ') || 'Fauna de la expedición') + '">'
                : '<div class="tour-wildlife-thumb tour-wildlife-thumb--placeholder"></div>';

            return '<div class="tour-wildlife-item">' +
                imageHtml +
                '<div class="tour-wildlife-info">' +
                (hasText(item.name) ? '<span class="tour-wildlife-name">' + escapeHtml(item.name) + '</span>' : '') +
                (hasText(item.scientific) ? '<span class="tour-wildlife-scientific">' + escapeHtml(item.scientific) + '</span>' : '') +
                badge + '</div></div>';
        }).join('');
        document.getElementById('tour-wildlife-grid').innerHTML = wildlifeHtml;
        setSectionVisibility('wildlife', !!wildlifeHtml);

        var orgHtml = '';
        if (hasOrganizer) {
            var logoHtml = '';

            if (hasText(organizer.logo)) {
                logoHtml = '<img src="' + organizer.logo + '" alt="' + escapeAttribute(organizer.name || 'Organizador') + '" class="tour-org-logo" data-tour-lightbox="1" data-lightbox-src="' + organizer.logo + '" data-lightbox-caption="' + escapeAttribute(organizer.name || 'Organizador') + '">';
            } else if (hasText(organizer.name)) {
                logoHtml = '<div class="tour-org-logo-placeholder">' + initials(organizer.name) + '</div>';
            }

            var textHtml = '';
            if (hasText(organizer.name) || hasText(organizer.role)) {
                textHtml = '<div>' +
                    (hasText(organizer.name) ? '<div class="tour-org-name">' + escapeHtml(organizer.name) + '</div>' : '') +
                    (hasText(organizer.role) ? '<div class="tour-org-role">' + escapeHtml(organizer.role) + '</div>' : '') +
                    '</div>';
            }

            orgHtml = logoHtml + textHtml;
        }
        document.getElementById('tour-organizer-content').innerHTML = orgHtml;
        setElementVisibility(document.getElementById('tour-organizer-content').closest('.tour-org-card'), hasOrganizer);

        var guidesHtml = guideItems.map(function(item) {
            var guidePhoto = '';

            if (hasText(item.photo)) {
                guidePhoto = '<img src="' + item.photo + '" alt="' + escapeAttribute(item.name || 'Guía') + '" class="tour-guide-photo" data-tour-lightbox="1" data-lightbox-src="' + item.photo + '" data-lightbox-caption="' + escapeAttribute(item.name || 'Guía') + '">';
            } else if (hasText(item.name)) {
                guidePhoto = '<div class="tour-guide-photo tour-guide-photo--placeholder">' + initials(item.name) + '</div>';
            }

            return '<div class="tour-guide-item">' +
                guidePhoto +
                '<div class="tour-guide-copy">' +
                (hasText(item.name) ? '<div class="tour-guide-name">' + escapeHtml(item.name) + '</div>' : '') +
                (hasText(item.language) ? '<div class="tour-guide-lang">' + escapeHtml(item.language) + '</div>' : '') +
                (hasText(item.role) ? '<div class="tour-guide-role">' + escapeHtml(item.role) + '</div>' : '') +
                '</div>' +
                '</div>';
        }).join('');
        document.getElementById('tour-guides-content').innerHTML = guidesHtml;
        setElementVisibility(document.getElementById('tour-guides-content').closest('.tour-guides-card'), hasGuides);

        var organizerSection = document.getElementById('section-organizer');
        if (organizerSection) {
            var organizerGrid = organizerSection.querySelector('.tour-two-col');
            if (organizerGrid) {
                organizerGrid.classList.toggle('tour-two-col--single', !(hasOrganizer && hasGuides));
            }
        }
        setSectionVisibility('organizer', hasOrganizer || hasGuides);

        var itinCount = itineraryItems.length;
        document.getElementById('itinerary-toggle-text').textContent = itinCount > 0
            ? itinCount + (itinCount === 1 ? ' día · Haz clic para ver el itinerario completo' : ' días · Haz clic para ver el itinerario completo')
            : 'Haz clic para ver el itinerario completo';
        var itinHtml = itineraryItems.map(function(day) {
            return '<div class="tour-itinerary-day">' +
                '<div class="tour-day-label">' + escapeHtml(day.day || 'Día') + '</div>' +
                '<div>' +
                (hasText(day.title) ? '<div class="tour-day-title">' + escapeHtml(day.title) + '</div>' : '') +
                (hasText(day.description) ? '<div class="tour-day-desc">' + escapeHtml(day.description) + '</div>' : '') +
                '</div></div>';
        }).join('');
        document.getElementById('itinerary-content').innerHTML = itinHtml;
        document.getElementById('itinerary-content').classList.remove('open');
        var itinToggleIcon = document.querySelector('#itinerary-toggle .toggle-icon');
        if (itinToggleIcon) {
            itinToggleIcon.classList.remove('rotated');
        }
        setSectionVisibility('itinerary', !!itinHtml);

        var locHtml = locationItems.map(function(loc) {
            var locationTitle = hasText(loc.region)
                ? escapeHtml(loc.name || '') + ' — ' + escapeHtml(loc.region)
                : escapeHtml(loc.name || '');

            return '<div class="tour-location-item">' + ICONS.mapPin +
                '<div>' +
                (hasText(locationTitle) ? '<div class="tour-location-name">' + locationTitle + '</div>' : '') +
                (hasText(loc.description) ? '<div class="tour-location-desc">' + escapeHtml(loc.description) + '</div>' : '') +
                '</div></div>';
        }).join('');
        document.getElementById('tour-locations-list').innerHTML = locHtml;

        var mapEl = document.getElementById('tour-map');
        if (mapEl) {
            mapEl.setAttribute('data-lat', data.coords_lat || '9.58');
            mapEl.setAttribute('data-lng', data.coords_lng || '-83.81');
            mapEl.setAttribute('data-locations', JSON.stringify(locationItems));
        }
        setElementVisibility(mapEl, hasLocations);
        setSectionVisibility('locations', hasLocations);

        var availHtml = availabilityItems.map(function(item) {
            var statusClass = isUrgentStatus(item.status) ? 'tour-availability-badge--urgent' : 'tour-availability-badge--open';
            return '<div class="tour-availability-item">' +
                '<div class="tour-availability-date">' + ICONS.calendar + escapeHtml(item.date || 'Fecha por confirmar') + '</div>' +
                (hasText(item.status) ? '<span class="tour-availability-badge ' + statusClass + '">' + escapeHtml(item.status) + '</span>' : '') + '</div>';
        }).join('');
        document.getElementById('tour-availability-list').innerHTML = availHtml;
        document.getElementById('tour-price-value').textContent = data.price || '';
        setElementVisibility(document.querySelector('#section-availability .tour-price-box'), hasPrice, 'flex');
        setSectionVisibility('availability', !!availHtml || hasPrice);

        var incHtml = includeItems.map(function(item) {
            return '<div class="tour-check-item"><span class="tour-check-circle">' + ICONS.check + '</span><span>' + escapeHtml(item) + '</span></div>';
        }).join('');
        document.getElementById('tour-includes-list').innerHTML = incHtml;

        var excHtml = excludeItems.map(function(item) {
            return '<div class="tour-check-item"><span class="tour-check-circle tour-check-circle--exclude">' + ICONS.xmark + '</span><span>' + escapeHtml(item) + '</span></div>';
        }).join('');
        document.getElementById('tour-excludes-list').innerHTML = excHtml;

        var includeBlock = document.getElementById('tour-includes-list').parentElement;
        var excludeBlock = document.getElementById('tour-excludes-list').parentElement;
        setElementVisibility(includeBlock, !!incHtml);
        setElementVisibility(excludeBlock, !!excHtml);

        var includeSection = document.getElementById('section-includes');
        if (includeSection) {
            var includeGrid = includeSection.querySelector('.tour-two-col');
            if (includeGrid) {
                includeGrid.classList.toggle('tour-two-col--single', (!!incHtml) !== (!!excHtml));
            }
        }
        setSectionVisibility('includes', !!incHtml || !!excHtml);

        var transportEl = document.getElementById('tour-transport-value');
        var accommodationEl = document.getElementById('tour-accommodation-value');
        var paceEl = document.getElementById('tour-pace-value');
        transportEl.textContent = data.transport || '';
        accommodationEl.textContent = data.accommodation || '';
        paceEl.textContent = data.pace || '';

        setElementVisibility(transportEl.closest('.tour-transport-block'), hasText(data.transport));
        setElementVisibility(accommodationEl.closest('.tour-transport-block'), hasText(data.accommodation));
        setElementVisibility(paceEl.closest('.tour-transport-block'), hasText(data.pace));
        setSectionVisibility('transport', hasText(data.transport) || hasText(data.accommodation) || hasText(data.pace));

        var policiesHtml = hasText(data.policies) ? '<p>' + escapeHtml(data.policies).replace(/\n/g, '</p><p>') + '</p>' : '';
        document.getElementById('tour-policies-text').innerHTML = policiesHtml;
        setElementVisibility(document.querySelector('#section-policies .tour-policies-box'), !!policiesHtml);

        var faqHtml = faqItems.map(function(item, index) {
            return '<div class="tour-faq-item">' +
                '<button class="faq-item-toggle" data-index="' + index + '">' +
                '<span>' + escapeHtml(item.question || 'Pregunta frecuente') + '</span>' +
                '<svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>' +
                '</button>' +
                '<div class="faq-item-content"><div>' + escapeHtml(item.answer || '').replace(/\n/g, '<br>') + '</div></div>' +
                '</div>';
        }).join('');
        document.getElementById('tour-faq-list').innerHTML = faqHtml;
        setElementVisibility(document.getElementById('tour-faq-list'), !!faqHtml, 'flex');
        setSectionVisibility('policies', !!policiesHtml || !!faqHtml);

        var inquiryHeaderButton = document.getElementById('tour-btn-inquiry-header');
        if (inquiryHeaderButton) {
            inquiryHeaderButton.onclick = function(e) {
                e.preventDefault();
                openInquiryModal();
            };
        }

        var inquiryPriceButton = document.getElementById('tour-btn-inquiry-price');
        if (inquiryPriceButton) {
            inquiryPriceButton.onclick = function(e) {
                e.preventDefault();
                openInquiryModal();
            };
        }

        var inquiryCtaButton = document.getElementById('tour-btn-inquiry-cta');
        if (inquiryCtaButton) {
            inquiryCtaButton.onclick = function(e) {
                e.preventDefault();
                openInquiryModal();
            };
        }
    }

    // ─── 3. Galería ───
    function updateGallery() {
        if (galleryImages.length === 0) return;
        var idx = (galleryIndex % galleryImages.length + galleryImages.length) % galleryImages.length;
        var img = document.getElementById('gallery-main-img');
        if (img) {
            var caption = currentTourContext.name ? currentTourContext.name + ' · Foto ' + (idx + 1) : 'Imagen de la expedición';
            img.src = galleryImages[idx];
            img.alt = caption;
            img.setAttribute('data-tour-lightbox', '1');
            img.setAttribute('data-lightbox-src', galleryImages[idx]);
            img.setAttribute('data-lightbox-caption', caption);
        }
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
        closeImageLightbox();
        if (document.getElementById('section-locations') && document.getElementById('section-locations').style.display !== 'none') {
            setTimeout(function() {
                initTourMap();
            }, 150);
        }
    }

    function closeTourModal() {
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
        closeImageLightbox();
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
        if (!mapEl || mapEl.style.display === 'none' || typeof L === 'undefined') return;
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

    document.addEventListener('click', function(e) {
        var zoomable = e.target.closest('[data-tour-lightbox="1"]');
        if (zoomable) {
            openImageLightbox(
                zoomable.getAttribute('data-lightbox-src') || zoomable.getAttribute('src'),
                zoomable.getAttribute('data-lightbox-caption') || zoomable.getAttribute('alt') || ''
            );
            return;
        }

        if (e.target.id === 'tour-image-lightbox' || e.target.closest('#tour-image-lightbox-close')) {
            closeImageLightbox();
        }
    });

    // ─── Helpers ───
    function hasText(value) {
        return typeof value === 'string'
            ? value.trim() !== ''
            : value !== null && value !== undefined && value !== false;
    }

    function setElementVisibility(element, visible, displayValue) {
        if (!element) return;
        element.style.display = visible ? (displayValue || '') : 'none';
        element.setAttribute('aria-hidden', visible ? 'false' : 'true');
    }

    function setSectionVisibility(sectionName, visible) {
        setElementVisibility(document.getElementById('section-' + sectionName), visible);
        document.querySelectorAll('.tour-jump-to-pill[data-section="' + sectionName + '"]').forEach(function(pill) {
            setElementVisibility(pill, visible, 'inline-flex');
        });
    }

    function isUrgentStatus(status) {
        if (!hasText(status)) {
            return false;
        }

        var normalized = String(status).toLowerCase();
        return normalized.indexOf('left') !== -1 ||
            normalized.indexOf('ahead') !== -1 ||
            normalized.indexOf('cupo') !== -1 ||
            normalized.indexOf('quedan') !== -1 ||
            normalized.indexOf('ultim') !== -1;
    }

    function openInquiryModal() {
        if (typeof window.weOpenModal === 'function') {
            closeTourModal();
            window.weOpenModal(currentTourContext.id, currentTourContext.name);
            return;
        }

        var inquirySection = document.getElementById('section-inquiry');
        if (inquirySection) {
            inquirySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function openImageLightbox(src, caption) {
        var lightbox = document.getElementById('tour-image-lightbox');
        var lightboxImg = document.getElementById('tour-image-lightbox-img');
        var lightboxCaption = document.getElementById('tour-image-lightbox-caption');

        if (!lightbox || !lightboxImg || !hasText(src)) {
            return;
        }

        lightboxImg.src = src;
        lightboxImg.alt = caption || currentTourContext.name || 'Imagen ampliada';
        lightbox.classList.add('is-open');
        lightbox.setAttribute('aria-hidden', 'false');

        if (lightboxCaption) {
            lightboxCaption.textContent = caption || '';
            setElementVisibility(lightboxCaption, hasText(caption));
        }
    }

    function closeImageLightbox() {
        var lightbox = document.getElementById('tour-image-lightbox');
        var lightboxImg = document.getElementById('tour-image-lightbox-img');
        var lightboxCaption = document.getElementById('tour-image-lightbox-caption');

        if (!lightbox) {
            return;
        }

        lightbox.classList.remove('is-open');
        lightbox.setAttribute('aria-hidden', 'true');

        if (lightboxImg) {
            lightboxImg.src = '';
            lightboxImg.alt = '';
        }

        if (lightboxCaption) {
            lightboxCaption.textContent = '';
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function escapeAttribute(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
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
