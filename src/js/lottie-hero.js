/**
 * Lottie Hero Sequence
 *
 * Finds every .c-lottie-hero element on the page, loads its animations,
 * then plays them in sequence — crossfading between each one — and loops
 * back to the start once all have played.
 *
 * Data attributes on .c-lottie-hero:
 *   data-lottie-files  JSON array of absolute animation file URLs
 *   data-fade          Crossfade duration in milliseconds (default: 600)
 */

import lottie from 'lottie-web';

window.addEventListener('load', function () {

  var heroes = document.querySelectorAll('.c-lottie-hero');

  heroes.forEach(function (hero) {

    // ── Parse data attributes ─────────────────────────────────────────────
    var filesJson = hero.dataset.lottieFiles;
    var fadeMs    = parseInt(hero.dataset.fade, 10) || 600;

    if (!filesJson) return;

    var files  = JSON.parse(filesJson);
    var slides = hero.querySelectorAll('.c-lottie-hero__slide');

    if (!slides.length || files.length !== slides.length) return;

    // Pass fade duration into CSS so the transition property matches
    hero.style.setProperty('--lottie-fade', (fadeMs / 1000) + 's');

    // ── Load all animations up-front ──────────────────────────────────────
    var animations   = [];
    var currentIndex = 0;

    slides.forEach(function (slide, i) {
      var anim = lottie.loadAnimation({
        container: slide,
        renderer:  'svg',
        loop:      false,
        autoplay:  false,
        path:      files[i],
      });

      animations.push(anim);

      // Each animation's complete handler advances to the next slide
      anim.addEventListener('complete', function () {
        // Guard: only act if this is actually the current animation
        if (animations[currentIndex] !== anim) return;

        var nextIndex = (currentIndex + 1) % animations.length;

        // Crossfade simultaneously
        slides[currentIndex].classList.remove('is-active');
        slides[nextIndex].classList.add('is-active');

        // Wait for fade to finish, then play the next animation
        setTimeout(function () {
          currentIndex = nextIndex;
          animations[currentIndex].goToAndPlay(0, true);
        }, fadeMs);
      });
    });

    // ── Kick off the first animation ─────────────────────────────────────
    slides[0].classList.add('is-active');
    animations[0].play();

  });

});
