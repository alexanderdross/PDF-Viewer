/**
 * PDF License Manager - Admin Scripts
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		// Auto-update site limit based on plan selection.
		var planSelect = document.getElementById('plan');
		var siteLimitInput = document.getElementById('site_limit');

		if (planSelect && siteLimitInput) {
			planSelect.addEventListener('change', function () {
				var limits = {
					starter: 1,
					professional: 5,
					agency: 0,
					enterprise: 0,
					dev: 1,
				};
				var limit = limits[this.value];
				if (limit !== undefined) {
					siteLimitInput.value = limit;
				}
			});
		}
	});
})();
