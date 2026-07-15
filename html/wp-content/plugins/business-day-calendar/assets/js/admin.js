(function () {
	'use strict';

	document.addEventListener('click', function (event) {
		var addButton = event.target.closest('.bdc-admin__add-date');
		if (addButton) {
			event.preventDefault();
			var list = addButton.closest('.bdc-admin__date-list');
			if (!list) {
				return;
			}
			var rows = list.querySelector('.bdc-admin__date-rows');
			var field = addButton.getAttribute('data-field');
			var row = document.createElement('div');
			row.className = 'bdc-admin__date-row';
			row.innerHTML =
				'<input type="date" name="' + field + '[]" value="">' +
				'<button type="button" class="button-link-delete bdc-admin__remove-date" aria-label="削除">削除</button>';
			rows.appendChild(row);
			row.querySelector('input').focus();
			return;
		}

		var removeButton = event.target.closest('.bdc-admin__remove-date');
		if (removeButton) {
			event.preventDefault();
			var dateRow = removeButton.closest('.bdc-admin__date-row');
			var container = removeButton.closest('.bdc-admin__date-rows');
			if (!dateRow || !container) {
				return;
			}
			if (container.querySelectorAll('.bdc-admin__date-row').length <= 1) {
				dateRow.querySelector('input').value = '';
				return;
			}
			dateRow.remove();
		}
	});
})();
