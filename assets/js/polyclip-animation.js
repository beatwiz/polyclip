/**
 * Polyclip - Animation Controller
 *
 * Uses Intersection Observer to pause/resume clip-path animation
 * when the element is outside the viewport.
 */
(function () {
    'use strict';

    if (typeof IntersectionObserver === 'undefined') {
        return;
    }

    var observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                var clip = entry.target.querySelector(
                    '.polyclip__clip--animated'
                );
                if (!clip) return;

                if (entry.isIntersecting) {
                    clip.classList.remove('polyclip__clip--paused');
                } else {
                    clip.classList.add('polyclip__clip--paused');
                }
            });
        },
        { threshold: 0.05 }
    );

    // Observe all instances on the page.
    var elements = document.querySelectorAll('.polyclip');
    for (var i = 0; i < elements.length; i++) {
        observer.observe(elements[i]);
    }
})();
