(function(window) {
    'use strict';

    if (typeof window.weOpenModal === 'function' && typeof window.weCloseModal === 'function') {
        return;
    }

    window.weOpenModal = function() {};
    window.weCloseModal = function() {};
})(window);
