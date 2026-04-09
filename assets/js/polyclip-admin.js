/**
 * Polyclip - Admin Configurator
 *
 * Handles wp.media integration, live shortcode builder,
 * and live preview with smooth animated clip-path.
 */
(function ($) {
    'use strict';

    var imageData = {
        images: [],
        desktop: [],
        tablet: [],
        mobile: []
    };

    var previewDevice = 'auto';

    /* -------------------------------------------------------
     * Initialization
     * ----------------------------------------------------- */

    $(document).ready(function () {
        // Initialize native WP postbox toggle/drag.
        if (typeof postboxes !== 'undefined' && polyclipAdmin.screenId) {
            postboxes.add_postbox_toggles(polyclipAdmin.screenId);
        }

        bindEvents();
        buildShortcode();
        toggleDependentBoxes();
    });

    function bindEvents() {
        // Add image buttons.
        $('.polyclip-add-image').on('click', function () {
            var group = $(this).closest('.polyclip-image-group').data('group');
            openMediaModal(group);
        });

        // Settings changes — rebuild shortcode and preview.
        $('#polyclip-preset, #polyclip-animate, #polyclip-duration, #polyclip-safe-zone, #polyclip-aspect, #polyclip-aspect-desktop, #polyclip-aspect-tablet, #polyclip-aspect-mobile, #polyclip-position, #polyclip-alt, #polyclip-vertices')
            .on('change input', function () { buildShortcode(); updatePreview(); });

        // Copy shortcode.
        $('#polyclip-copy-btn').on('click', copyShortcode);

        // Preview device tabs.
        $('.polyclip-preview-tab').on('click', function () {
            $('.polyclip-preview-tab').removeClass('active');
            $(this).addClass('active');
            previewDevice = $(this).data('device');
            updatePreview();
        });

        // Make image lists sortable.
        $('.polyclip-image-list').sortable({
            placeholder: 'polyclip-image-placeholder',
            update: function () {
                syncImageDataFromDOM($(this).closest('.polyclip-image-group').data('group'));
                buildShortcode();
            }
        });
    }

    /* -------------------------------------------------------
     * Show/hide Preview & Shortcode boxes
     * ----------------------------------------------------- */

    function hasAnyImages() {
        return imageData.images.length > 0 ||
               imageData.desktop.length > 0 ||
               imageData.tablet.length > 0 ||
               imageData.mobile.length > 0;
    }

    function toggleDependentBoxes() {
        var show = hasAnyImages();
        $('#polyclip-preview').toggle(show);
        $('#polyclip-shortcode').toggle(show);
    }

    /* -------------------------------------------------------
     * Media Library
     * ----------------------------------------------------- */

    function openMediaModal(group) {
        var frame = wp.media({
            title: polyclipAdmin.i18n.selectImages,
            multiple: true,
            library: { type: 'image' },
            button: { text: polyclipAdmin.i18n.useSelected }
        });

        frame.on('select', function () {
            var attachments = frame.state().get('selection').toJSON();
            attachments.forEach(function (att) {
                var thumb = att.sizes && att.sizes.thumbnail
                    ? att.sizes.thumbnail.url
                    : att.url;

                imageData[group].push({
                    id: att.id,
                    url: att.url,
                    thumb: thumb
                });
            });
            renderImageList(group);
            buildShortcode();
            updatePreview();
            toggleDependentBoxes();
        });

        frame.open();
    }

    function renderImageList(group) {
        var $list = $('.polyclip-image-group[data-group="' + group + '"] .polyclip-image-list');
        $list.empty();

        imageData[group].forEach(function (img, index) {
            var $item = $('<div class="polyclip-image-item" data-index="' + index + '">'
                + '<img src="' + img.thumb + '" alt="">'
                + '<button type="button" class="polyclip-remove-image" title="Remove">&times;</button>'
                + '</div>');

            $item.find('.polyclip-remove-image').on('click', function () {
                imageData[group].splice(index, 1);
                renderImageList(group);
                buildShortcode();
                updatePreview();
                toggleDependentBoxes();
            });

            $list.append($item);
        });
    }

    function syncImageDataFromDOM(group) {
        var $list = $('.polyclip-image-group[data-group="' + group + '"] .polyclip-image-list');
        var newOrder = [];
        $list.find('.polyclip-image-item').each(function () {
            var idx = parseInt($(this).data('index'), 10);
            if (imageData[group][idx]) {
                newOrder.push(imageData[group][idx]);
            }
        });
        imageData[group] = newOrder;
    }

    /* -------------------------------------------------------
     * Shortcode Builder
     * ----------------------------------------------------- */

    function buildShortcode() {
        var parts = ['[polyclip'];

        // Images (as attachment IDs).
        var universalIds = imageData.images.map(function (img) { return img.id; });
        var desktopIds = imageData.desktop.map(function (img) { return img.id; });
        var tabletIds = imageData.tablet.map(function (img) { return img.id; });
        var mobileIds = imageData.mobile.map(function (img) { return img.id; });

        // If only universal images and just one, use 'image' attribute.
        if (universalIds.length === 1 && !desktopIds.length && !tabletIds.length && !mobileIds.length) {
            parts.push('image="' + universalIds[0] + '"');
        } else if (universalIds.length > 1) {
            parts.push('images="' + universalIds.join(',') + '"');
        } else if (universalIds.length === 1) {
            parts.push('image="' + universalIds[0] + '"');
        }

        if (desktopIds.length) {
            parts.push('desktop="' + desktopIds.join(',') + '"');
        }
        if (tabletIds.length) {
            parts.push('tablet="' + tabletIds.join(',') + '"');
        }
        if (mobileIds.length) {
            parts.push('mobile="' + mobileIds.join(',') + '"');
        }

        // Settings (only if non-default).
        var preset = $('#polyclip-preset').val();
        if (preset && preset !== 'default') {
            parts.push('preset="' + preset + '"');
        }

        var animate = $('#polyclip-animate').is(':checked');
        if (!animate) {
            parts.push('animate="false"');
        }

        var duration = $('#polyclip-duration').val();
        if (duration && duration !== '8') {
            parts.push('duration="' + duration + '"');
        }

        var safeZone = $('#polyclip-safe-zone').val();
        if (safeZone && safeZone !== '0') {
            parts.push('safe_zone="' + safeZone + '"');
        }

        var aspect = $('#polyclip-aspect').val();
        if (aspect && aspect !== '1800:780') {
            parts.push('aspect="' + aspect + '"');
        }

        var aspectDesktop = $('#polyclip-aspect-desktop').val();
        if (aspectDesktop) {
            parts.push('aspect_desktop="' + aspectDesktop + '"');
        }
        var aspectTablet = $('#polyclip-aspect-tablet').val();
        if (aspectTablet) {
            parts.push('aspect_tablet="' + aspectTablet + '"');
        }
        var aspectMobile = $('#polyclip-aspect-mobile').val();
        if (aspectMobile) {
            parts.push('aspect_mobile="' + aspectMobile + '"');
        }

        var position = $('#polyclip-position').val();
        if (position && position !== 'center') {
            parts.push('image_position="' + position + '"');
        }

        var alt = $('#polyclip-alt').val();
        if (alt) {
            parts.push('alt="' + alt + '"');
        }

        var vertices = $('#polyclip-vertices').val().trim();
        if (vertices) {
            parts.push('vertices="' + vertices + '"');
        }

        parts.push(']');

        var shortcode = parts.join(' ');
        $('#polyclip-shortcode-output').text(shortcode);
    }

    /* -------------------------------------------------------
     * Clipboard
     * ----------------------------------------------------- */

    function copyShortcode() {
        var text = $('#polyclip-shortcode-output').text();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function () {
                showNotice(polyclipAdmin.i18n.copied, 'success');
            });
        } else {
            var $temp = $('<textarea>').val(text).appendTo('body').select();
            document.execCommand('copy');
            $temp.remove();
            showNotice(polyclipAdmin.i18n.copied, 'success');
        }
    }

    /* -------------------------------------------------------
     * Live Preview — Smooth animated clip-path
     * ----------------------------------------------------- */

    var previewRafId = null;

    function updatePreview() {
        var $clip  = $('#polyclip-preview-clip');
        var $img   = $('#polyclip-preview-img');

        var firstImg = getFirstPreviewImage();
        if (!firstImg) {
            stopPreviewAnim();
            return;
        }

        $img.attr('src', firstImg);
        $img.css('object-position', $('#polyclip-position').val() || 'center');
        $clip.show();

        // Max-width based on device tab.
        var $wrap = $('#polyclip-preview-wrap');
        var maxWidths = { auto: '100%', desktop: '100%', tablet: '768px', mobile: '375px' };
        $wrap.css({ 'max-width': maxWidths[previewDevice] || '100%', 'margin': '0 auto' });

        // Aspect ratio — use device-specific if set, otherwise default.
        var aspectField = previewDevice !== 'auto' ? $('#polyclip-aspect-' + previewDevice).val() : '';
        var aspect = aspectField || $('#polyclip-aspect').val() || '1800:780';
        var parts = aspect.split(':');
        var w = parseInt(parts[0], 10) || 1800;
        var h = parseInt(parts[1], 10) || 780;
        var pct = (h / w) * 100;
        $('#polyclip-preview-fallback').css('padding-bottom', pct + '%');

        // Resolve points: custom vertices override preset.
        var customVertices = $('#polyclip-vertices').val().trim();
        var points, keyframes;

        if (customVertices) {
            points = parseVerticesString(customVertices);
            keyframes = generateKeyframesFromPoints(points);
        } else {
            var preset = $('#polyclip-preset').val() || 'default';
            var pData = polyclipAdmin.presetsData[preset] || polyclipAdmin.presetsData['default'];
            points = pData.points;
            keyframes = pData.keyframes;
        }

        // Apply safe zone clamping.
        var safeZone = parseFloat($('#polyclip-safe-zone').val()) || 0;
        if (safeZone > 0) {
            keyframes = applySafeZone(keyframes, safeZone);
            points = applySafeZone([points], safeZone)[0];
        }

        // Apply static clip-path.
        $clip.css('clip-path', 'polygon(' + points.join(', ') + ')');

        // Animation.
        var animate = $('#polyclip-animate').is(':checked');
        stopPreviewAnim();

        if (animate && keyframes && keyframes.length > 1) {
            var duration = (parseFloat($('#polyclip-duration').val()) || 8) * 1000;
            startPreviewAnim($clip[0], keyframes, duration);
        }
    }

    function pickRandom(arr) {
        return arr[Math.floor(Math.random() * arr.length)];
    }

    function getImageFromGroup(group) {
        if (!imageData[group] || imageData[group].length === 0) return '';
        var img = imageData[group].length === 1 ? imageData[group][0] : pickRandom(imageData[group]);
        return img.url || img.thumb || '';
    }

    function getFirstPreviewImage() {
        if (previewDevice !== 'auto') {
            var url = getImageFromGroup(previewDevice);
            return url || getImageFromGroup('images');
        }

        var groups = ['images', 'desktop', 'tablet', 'mobile'];
        for (var i = 0; i < groups.length; i++) {
            var url = getImageFromGroup(groups[i]);
            if (url) return url;
        }
        return '';
    }

    function parseVerticesString(input) {
        var pairs = input.trim().split(/\s+/);
        var points = [];
        var aspect = ($('#polyclip-aspect').val() || '1800:780').split(':');
        var aw = parseInt(aspect[0], 10) || 1800;
        var ah = parseInt(aspect[1], 10) || 780;

        pairs.forEach(function (pair) {
            var coords = pair.split(',');
            if (coords.length !== 2) return;
            var x = coords[0].trim();
            var y = coords[1].trim();

            if (x.indexOf('%') !== -1 && y.indexOf('%') !== -1) {
                points.push(x + ' ' + y);
            } else {
                var px = clamp((parseFloat(x) / aw) * 100).toFixed(2);
                var py = clamp((parseFloat(y) / ah) * 100).toFixed(2);
                points.push(px + '% ' + py + '%');
            }
        });

        return points;
    }

    function generateKeyframesFromPoints(basePoints) {
        var nudgeSets = [
            [-2, 3, 1, -2, 2, -1, -3, 1, 2, -2, 1, -3],
            [1, -2, -3, 2, -1, 3, 2, -3, -1, 3, -2, 1],
            [-1, 1, 2, -3, 3, -2, -2, 2, -3, 1, 3, -1]
        ];

        var keyframes = [basePoints];

        nudgeSets.forEach(function (nudges) {
            var kf = [];
            var ni = 0;
            basePoints.forEach(function (pt) {
                var parsed = parsePoint(pt);
                if (!parsed) { kf.push(pt); return; }
                var nx = clamp(parsed[0] + (nudges[ni % nudges.length] || 0));
                var ny = clamp(parsed[1] + (nudges[(ni + 1) % nudges.length] || 0));
                kf.push(nx.toFixed(2) + '% ' + ny.toFixed(2) + '%');
                ni += 2;
            });
            keyframes.push(kf);
        });

        return keyframes;
    }

    function parsePoint(str) {
        var m = str.trim().match(/^([\d.]+)%\s+([\d.]+)%$/);
        if (!m) return null;
        return [parseFloat(m[1]), parseFloat(m[2])];
    }

    function clamp(v) {
        return Math.max(0, Math.min(100, v));
    }

    function clampToSafeZone(x, y, sz) {
        if (sz <= 0) return [x, y];

        var cx = 50, cy = 50;
        var dx = x - cx, dy = y - cy;
        var dist = Math.sqrt(dx * dx + dy * dy);

        if (dist >= sz) return [x, y];
        if (dist < 0.01) { dx = 1; dy = 0; dist = 1; }

        var scale = sz / dist;
        var nx = cx + dx * scale;
        var ny = cy + dy * scale;

        if (nx < 0 || nx > 100 || ny < 0 || ny > 100) {
            var maxScale = 1000;
            if (dx > 0) maxScale = Math.min(maxScale, (100 - cx) / dx);
            else if (dx < 0) maxScale = Math.min(maxScale, -cx / dx);
            if (dy > 0) maxScale = Math.min(maxScale, (100 - cy) / dy);
            else if (dy < 0) maxScale = Math.min(maxScale, -cy / dy);
            nx = cx + dx * maxScale;
            ny = cy + dy * maxScale;
        }

        return [clamp(nx), clamp(ny)];
    }

    function applySafeZone(keyframes, sz) {
        sz = Math.max(0, Math.min(70, sz));
        if (sz <= 0) return keyframes;

        var dampen = 1.0 - (sz / 70.0) * 0.8;
        var base = keyframes[0] || [];

        return keyframes.map(function (frame, fi) {
            return frame.map(function (point, pi) {
                var parsed = parsePoint(point);
                if (!parsed) return point;

                var x = parsed[0], y = parsed[1];

                if (fi > 0 && base[pi]) {
                    var bp = parsePoint(base[pi]);
                    if (bp) {
                        x = bp[0] + (x - bp[0]) * dampen;
                        y = bp[1] + (y - bp[1]) * dampen;
                    }
                }

                var clamped = clampToSafeZone(x, y, sz);
                return clamped[0].toFixed(2) + '% ' + clamped[1].toFixed(2) + '%';
            });
        });
    }

    function generateRandomTarget(basePoints, nudgeRange) {
        return basePoints.map(function (pt) {
            var parsed = parsePoint(pt);
            if (!parsed) return pt;
            var nx = clamp(parsed[0] + (Math.random() * 2 - 1) * nudgeRange);
            var ny = clamp(parsed[1] + (Math.random() * 2 - 1) * nudgeRange);
            return nx.toFixed(2) + '% ' + ny.toFixed(2) + '%';
        });
    }

    function lerpPolygon(fromPts, toPts, t) {
        var result = [];
        for (var i = 0; i < fromPts.length; i++) {
            var a = parsePoint(fromPts[i]);
            var b = parsePoint(toPts[i]);
            if (!a || !b) {
                result.push(fromPts[i]);
                continue;
            }
            var x = clamp(a[0] + (b[0] - a[0]) * t);
            var y = clamp(a[1] + (b[1] - a[1]) * t);
            result.push(x.toFixed(2) + '% ' + y.toFixed(2) + '%');
        }
        return result;
    }

    function startPreviewAnim(el, keyframes, duration) {
        var numKeyframes = keyframes.length;
        var segmentDuration = duration / numKeyframes;
        var nudgeRange = 3;

        var sequence = keyframes.slice();
        for (var i = keyframes.length - 2; i >= 1; i--) {
            sequence.push(keyframes[i]);
        }

        var totalSegments = sequence.length;
        var currentSegment = 0;
        var fromPts = sequence[0];
        var toPts = generateRandomTarget(sequence[1 % totalSegments], nudgeRange);
        var segmentStart = performance.now();

        function tick(now) {
            var elapsed = now - segmentStart;
            var t = Math.min(elapsed / segmentDuration, 1);

            var eased = t < 0.5
                ? 2 * t * t
                : 1 - Math.pow(-2 * t + 2, 2) / 2;

            var interpolated = lerpPolygon(fromPts, toPts, eased);
            el.style.clipPath = 'polygon(' + interpolated.join(', ') + ')';

            if (t >= 1) {
                currentSegment = (currentSegment + 1) % totalSegments;
                fromPts = toPts;
                var nextBase = sequence[(currentSegment + 1) % totalSegments];
                toPts = generateRandomTarget(nextBase, nudgeRange);
                segmentStart = now;
            }

            previewRafId = requestAnimationFrame(tick);
        }

        previewRafId = requestAnimationFrame(tick);
    }

    function stopPreviewAnim() {
        if (previewRafId) {
            cancelAnimationFrame(previewRafId);
            previewRafId = null;
        }
    }

    /* -------------------------------------------------------
     * Helpers
     * ----------------------------------------------------- */

    function showNotice(message, type) {
        var cls = type === 'error' ? 'notice-error' : 'notice-success';
        var $notice = $('<div class="notice ' + cls + ' is-dismissible"><p>' + escHtml(message) + '</p></div>');
        $('.polyclip-admin h1').after($notice);
        setTimeout(function () { $notice.fadeOut(300, function () { $(this).remove(); }); }, 3000);
    }

    function escHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

})(jQuery);
