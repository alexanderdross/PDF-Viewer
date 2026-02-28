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

		// Copy license key to clipboard.
		var copyBtn = document.querySelector('.plm-copy-key');
		if (copyBtn) {
			copyBtn.addEventListener('click', function () {
				var target = document.querySelector(this.getAttribute('data-clipboard-target'));
				if (!target) return;

				var text = target.textContent.trim();
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(text).then(function () {
						copyBtn.textContent = plmAdmin.copied;
						copyBtn.classList.add('plm-copied');
						setTimeout(function () {
							copyBtn.textContent = plmAdmin.copy;
							copyBtn.classList.remove('plm-copied');
						}, 2000);
					});
				} else {
					// Fallback for older browsers.
					var range = document.createRange();
					range.selectNodeContents(target);
					var selection = window.getSelection();
					selection.removeAllRanges();
					selection.addRange(range);
					document.execCommand('copy');
					selection.removeAllRanges();
					copyBtn.textContent = plmAdmin.copied;
					copyBtn.classList.add('plm-copied');
					setTimeout(function () {
						copyBtn.textContent = plmAdmin.copy;
						copyBtn.classList.remove('plm-copied');
					}, 2000);
				}
			});
		}

		// Revoke license confirmation with localized string.
		var revokeForm = document.querySelector('.plm-revoke-form');
		if (revokeForm) {
			revokeForm.addEventListener('submit', function (e) {
				if (!confirm(plmAdmin.confirmRevoke)) {
					e.preventDefault();
				}
			});
		}
	});
})();
