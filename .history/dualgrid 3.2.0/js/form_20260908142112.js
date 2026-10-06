/* ============================================================
   WILDENDEMICS — Modal y paso de datos a CF7
   ============================================================ */

(function(window, document) {
    'use strict';

    function getTourForm() {
        return document.querySelector('#we-cf7-tour-form form') || document.querySelector('.we-cf7-tour-form form');
    }

    function getTailorMadeForm() {
        return document.querySelector('#dg-cf7-tailor-made-form form') || document.querySelector('.we-cf7-tailor-made-form form');
    }

    function ensureHiddenField(form, fieldName) {
        var field = form.querySelector('input[name="' + fieldName + '"]');
        if (field) {
            return field;
        }

        field = document.createElement('input');
        field.type = 'hidden';
        field.name = fieldName;
        form.appendChild(field);
        return field;
    }

    function fillTourFields(tourId, tourName) {
        var form = getTourForm();
        if (!form) {
            return;
        }

        ensureHiddenField(form, 'tour-id').value = tourId || '';
        ensureHiddenField(form, 'tour-name').value = tourName || '';
        ensureHiddenField(form, 'page-url').value = window.location.href;
        ensureHiddenField(form, 'inquiry-type').value = 'tour';
    }

    function fillTailorMadeFields() {
        var form = getTailorMadeForm();
        if (!form) {
            return;
        }

        ensureHiddenField(form, 'inquiry-type').value = 'tailor-made';
        ensureHiddenField(form, 'page-url').value = window.location.href;
        ensureHiddenField(form, 'page-title').value = document.title || '';
    }

    function setModalState(modal, isOpen) {
        if (!modal) {
            return;
        }

        modal.style.display = isOpen ? 'flex' : 'none';
        modal.classList.toggle('active', isOpen);
        modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');

        if (isOpen) {
            document.body.style.overflow = 'hidden';
            document.body.classList.add('we-modal-open');
        } else if (!document.querySelector('.we-modal.active')) {
            document.body.style.overflow = '';
            document.body.classList.remove('we-modal-open');
        }
    }

    window.weOpenModal = function(tourId, tourName) {
        var modal = document.getElementById('we-tour-modal');
        if (!modal) {
            return;
        }

        setModalState(modal, true);

        var title = modal.querySelector('.we-modal__title');
        if (title) {
            title.textContent = tourName ? 'Request Information - ' + tourName : 'Request Information';
        }

        fillTourFields(tourId, tourName);
        window.setTimeout(function() {
            fillTourFields(tourId, tourName);
        }, 200);
    };

    window.weCloseModal = function() {
        setModalState(document.getElementById('we-tour-modal'), false);
    };

    window.dgOpenTailorMadeModal = function() {
        var modal = document.getElementById('dg-tailor-made-modal');
        if (!modal) {
            return;
        }

        setModalState(modal, true);
        fillTailorMadeFields();
        window.setTimeout(fillTailorMadeFields, 200);
    };

    window.dgCloseTailorMadeModal = function() {
        setModalState(document.getElementById('dg-tailor-made-modal'), false);
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.weCloseModal();
            window.dgCloseTailorMadeModal();
        }
    });

    document.addEventListener('click', function(e) {
        var tailorTrigger = e.target.closest('.dg-tailor-made-trigger');
        if (tailorTrigger) {
            e.preventDefault();
            window.dgOpenTailorMadeModal();
            return;
        }

        if (e.target.classList.contains('we-modal__overlay')) {
            var modal = e.target.closest('.we-modal');
            if (modal && modal.id === 'dg-tailor-made-modal') {
                window.dgCloseTailorMadeModal();
                return;
            }

            window.weCloseModal();
        }
    });
})(window, document);
