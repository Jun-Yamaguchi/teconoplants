(function () {
	'use strict';

	var config = window.bdcWidget || {};
	var restUrl = config.restUrl;

	if (!restUrl) {
		return;
	}

	document.addEventListener('click', function (event) {
		var button = event.target.closest('.bdc-calendar__nav');
		if (!button || button.disabled) {
			return;
		}

		event.preventDefault();

		var calendar = button.closest('.bdc-calendar');
		if (!calendar) {
			return;
		}

		var year = button.getAttribute('data-year');
		var month = button.getAttribute('data-month');
		if (!year || !month) {
			return;
		}

		var navButtons = calendar.querySelectorAll('.bdc-calendar__nav');
		navButtons.forEach(function (nav) {
			nav.disabled = true;
		});
		calendar.classList.add('bdc-calendar--loading');
		calendar.setAttribute('aria-busy', 'true');

		var url = restUrl + '?year=' + encodeURIComponent(year) + '&month=' + encodeURIComponent(month);

		fetch(url, {
			method: 'GET',
			credentials: 'same-origin',
			headers: {
				Accept: 'application/json',
			},
		})
			.then(function (response) {
				if (!response.ok) {
					throw new Error('Failed to load calendar');
				}
				return response.json();
			})
			.then(function (data) {
				if (!data || !data.html) {
					throw new Error('Invalid calendar response');
				}

				var wrapper = document.createElement('div');
				wrapper.innerHTML = data.html.trim();
				var newCalendar = wrapper.firstElementChild;

				if (!newCalendar) {
					throw new Error('Invalid calendar HTML');
				}

				calendar.replaceWith(newCalendar);
			})
			.catch(function () {
				calendar.classList.remove('bdc-calendar--loading');
				calendar.removeAttribute('aria-busy');
				navButtons.forEach(function (nav) {
					nav.disabled = false;
				});
			});
	});
})();
