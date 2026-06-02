(function () {
	'use strict';

	var sliders = document.querySelectorAll('.hero-slider');
	if (!sliders.length) {
		return;
	}

	sliders.forEach(function (slider) {
		var slides = slider.querySelectorAll('.hero-slider__slide');
		var dots = slider.querySelectorAll('.hero-slider__dot');
		var prev = slider.querySelector('.hero-slider__arrow--prev');
		var next = slider.querySelector('.hero-slider__arrow--next');
		var interval = parseInt(slider.getAttribute('data-autoplay'), 10) || 6000;
		var current = 0;
		var timer = null;
		var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function goTo(index) {
			if (!slides.length) {
				return;
			}

			current = (index + slides.length) % slides.length;

			slides.forEach(function (slide, i) {
				var active = i === current;
				slide.classList.toggle('is-active', active);
				slide.setAttribute('aria-hidden', active ? 'false' : 'true');
			});

			dots.forEach(function (dot, i) {
				var active = i === current;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-selected', active ? 'true' : 'false');
			});
		}

		function nextSlide() {
			goTo(current + 1);
		}

		function prevSlide() {
			goTo(current - 1);
		}

		function startAutoplay() {
			if (reducedMotion || slides.length < 2) {
				return;
			}
			stopAutoplay();
			timer = window.setInterval(nextSlide, interval);
		}

		function stopAutoplay() {
			if (timer) {
				window.clearInterval(timer);
				timer = null;
			}
		}

		if (prev) {
			prev.addEventListener('click', function () {
				prevSlide();
				startAutoplay();
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				nextSlide();
				startAutoplay();
			});
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				var index = parseInt(dot.getAttribute('data-slide'), 10);
				if (!isNaN(index)) {
					goTo(index);
					startAutoplay();
				}
			});
		});

		slider.addEventListener('mouseenter', stopAutoplay);
		slider.addEventListener('mouseleave', startAutoplay);
		slider.addEventListener('focusin', stopAutoplay);
		slider.addEventListener('focusout', startAutoplay);

		startAutoplay();
	});
})();
