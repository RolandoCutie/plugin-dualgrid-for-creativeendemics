/* ============================================================
   WILDENDEMICS — Modal y paso de datos a CF7
   ============================================================ */

(function(window, document) {
    'use strict';

    function getTourForm() {
        return document.querySelector('#we-cf7-tour-form form') || document.querySelector('.we-cf7-tour-form form');
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
    }

    window.weOpenModal = function(tourId, tourName) {
        var modal = document.getElementById('we-tour-modal');
        if (!modal) {
            return;
        }

        modal.style.display = 'flex';
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        document.body.classList.add('we-modal-open');

        var title = modal.querySelector('.we-modal__title');
        if (title) {
            title.textContent = tourName ? 'Solicitar Información — ' + tourName : 'Solicitar Información';
        }

        fillTourFields(tourId, tourName);
        window.setTimeout(function() {
            fillTourFields(tourId, tourName);
        }, 200);
    };

    window.weCloseModal = function() {
        var modal = document.getElementById('we-tour-modal');
        if (!modal) {
            return;
        }

        modal.style.display = 'none';
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        document.body.classList.remove('we-modal-open');
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.weCloseModal();
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('we-modal__overlay')) {
            window.weCloseModal();
        }
    });
})(window, document);
