(function () {
    var runtime = window.dgCreativeWildsAdmin || {};

    function getTabRoot(wrapper) {
        return wrapper.closest('[data-dg-cw-tab-root]') || wrapper.parentNode || document;
    }

    function setActiveTab(wrapper, tab, syncUrl) {
        var trigger = wrapper.querySelector('[data-dg-cw-tab-trigger="' + tab + '"]');
        if (!trigger) {
            return;
        }
        var tabRoot = getTabRoot(wrapper);

        wrapper.querySelectorAll('[data-dg-cw-tab-trigger]').forEach(function (button) {
            button.classList.toggle('is-active', button === trigger);
        });

        tabRoot.querySelectorAll('[data-dg-cw-tab-panel]').forEach(function (panel) {
            panel.classList.toggle('is-active', panel.getAttribute('data-dg-cw-tab-panel') === tab);
        });

        if (syncUrl && window.history && window.history.replaceState) {
            var url = new URL(window.location.href);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url.toString());
        }
    }

    function updateRowTitle(row) {
        var source = row.querySelector('[data-dg-cw-title-source]');
        var target = row.querySelector('[data-dg-cw-row-title]');
        if (!source || !target) {
            return;
        }

        target.textContent = source.value.trim() || target.textContent;
    }

    function setupTabs() {
        var wrapper = document.querySelector('[data-dg-cw-tabs]');
        if (!wrapper) {
            return;
        }

        var requestedTab = new URLSearchParams(window.location.search).get('tab');
        if (requestedTab) {
            setActiveTab(wrapper, requestedTab, false);
        }

        wrapper.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-dg-cw-tab-trigger]');
            if (!trigger) {
                return;
            }

            var tab = trigger.getAttribute('data-dg-cw-tab-trigger');
            setActiveTab(wrapper, tab, true);
        });
    }

    function bindImageField(container) {
        if (container.getAttribute('data-dg-cw-image-bound') === 'true') {
            return;
        }

        var input = container.querySelector('[data-dg-cw-image-input]');
        var preview = container.querySelector('[data-dg-cw-image-preview]');
        var openButton = container.querySelector('[data-dg-cw-media-open]');
        var clearButton = container.querySelector('[data-dg-cw-media-clear]');
        var frame = null;

        if (!input || !preview) {
            return;
        }

        container.setAttribute('data-dg-cw-image-bound', 'true');

        function getPlaceholder() {
            return preview.getAttribute('data-placeholder') || 'No image selected';
        }

        function setPreview(id, url) {
            input.value = id || '';
            if (url) {
                preview.classList.remove('is-empty');
                preview.innerHTML = '<img src="' + url + '" alt="">';
            } else {
                preview.classList.add('is-empty');
                preview.innerHTML = '<span>' + getPlaceholder() + '</span>';
            }
        }

        if (openButton) {
            openButton.addEventListener('click', function () {
                if (!window.wp || !wp.media) {
                    return;
                }

                if (!frame) {
                    frame = wp.media({
                        title: 'Seleccionar imagen',
                        button: { text: 'Usar esta imagen' },
                        multiple: false
                    });

                    frame.on('select', function () {
                        var attachment = frame.state().get('selection').first().toJSON();
                        setPreview(attachment.id, attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url);
                        frame.close();
                    });
                }

                frame.open();
            });
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                setPreview('', '');
            });
        }
    }

    function bindRow(row) {
        if (row.getAttribute('data-dg-cw-row-bound') === 'true') {
            return;
        }

        row.setAttribute('data-dg-cw-row-bound', 'true');
        row.querySelectorAll('[data-dg-cw-image-field]').forEach(bindImageField);
        row.querySelectorAll('[data-dg-cw-title-source]').forEach(function (input) {
            input.addEventListener('input', function () {
                updateRowTitle(row);
            });
        });
        updateRowTitle(row);

        var remove = row.querySelector('[data-dg-cw-remove-row]');
        if (remove) {
            remove.addEventListener('click', function () {
                row.remove();
            });
        }

        var up = row.querySelector('[data-dg-cw-move-up]');
        if (up) {
            up.addEventListener('click', function () {
                var prev = row.previousElementSibling;
                if (prev) {
                    row.parentNode.insertBefore(row, prev);
                }
            });
        }

        var down = row.querySelector('[data-dg-cw-move-down]');
        if (down) {
            down.addEventListener('click', function () {
                var next = row.nextElementSibling;
                if (next) {
                    row.parentNode.insertBefore(next, row);
                }
            });
        }
    }

    function setupRepeaters() {
        document.querySelectorAll('[data-dg-cw-repeater]').forEach(function (repeater) {
            repeater.querySelectorAll('[data-dg-cw-row]').forEach(bindRow);
        });

        document.querySelectorAll('[data-dg-cw-add-row]').forEach(function (button) {
            button.addEventListener('click', function () {
                var type = button.getAttribute('data-dg-cw-add-row');
                var repeater = document.querySelector('[data-dg-cw-repeater="' + type + '"]');
                var template = document.getElementById('dg-cw-' + type + '-template');
                if (!repeater || !template) {
                    return;
                }

                var index = repeater.querySelectorAll('[data-dg-cw-row]').length;
                var wrapper = document.createElement('div');
                wrapper.innerHTML = template.innerHTML.replace(/__INDEX__/g, index);
                var row = wrapper.firstElementChild;
                repeater.appendChild(row);
                bindRow(row);
            });
        });

        document.querySelectorAll('[data-dg-cw-image-field]').forEach(bindImageField);
    }

    function syncTopLevelLink() {
        if (!runtime.dashboardUrl) {
            return;
        }

        var parentMenuLink = document.querySelector('#toplevel_page_dualgrid-creativewilds > a');
        if (parentMenuLink) {
            parentMenuLink.setAttribute('href', runtime.dashboardUrl);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        syncTopLevelLink();
        setupTabs();
        setupRepeaters();
    });
})();

