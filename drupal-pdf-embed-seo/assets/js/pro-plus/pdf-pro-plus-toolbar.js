/**
 * @file
 * Pro+ Enterprise toolbar with feature dropdown menu.
 */

(function (Drupal, drupalSettings, once) {
  'use strict';

  /**
   * Initialize the Pro+ toolbar dropdown.
   */
  Drupal.behaviors.pdfEmbedSeoProPlusToolbar = {
    attach: function (context) {
      once('pro-plus-toolbar', '.pdf-viewer-wrapper', context).forEach(
        function (wrapper) {
          var dropdown = wrapper.querySelector('.pdf-viewer-pro-plus-dropdown');
          if (!dropdown) {
            return;
          }

          var toggleBtn = dropdown.querySelector('.pdf-viewer-pro-plus-toggle');
          var menu = dropdown.querySelector('.pdf-viewer-pro-plus-menu');

          if (!toggleBtn || !menu) {
            return;
          }

          // Toggle dropdown on click.
          toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = menu.style.display === 'block';
            menu.style.display = isOpen ? 'none' : 'block';
            toggleBtn.setAttribute('aria-expanded', !isOpen);
          });

          // Close on outside click.
          document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target)) {
              menu.style.display = 'none';
              toggleBtn.setAttribute('aria-expanded', 'false');
            }
          });

          // Close on Escape.
          document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
              menu.style.display = 'none';
              toggleBtn.setAttribute('aria-expanded', 'false');
            }
          });

          // Handle menu item clicks.
          var menuItems = menu.querySelectorAll('[data-action]');
          menuItems.forEach(function (item) {
            item.addEventListener('click', function (e) {
              e.preventDefault();
              var action = this.getAttribute('data-action');
              menu.style.display = 'none';
              toggleBtn.setAttribute('aria-expanded', 'false');

              // Dispatch custom event for the action.
              wrapper.dispatchEvent(new CustomEvent('proPlusAction', {
                detail: { action: action }
              }));

              // Handle built-in actions.
              switch (action) {
                case 'annotations':
                  Drupal.pdfEmbedSeo.toggleAnnotations(wrapper);
                  break;

                case 'versions':
                  Drupal.pdfEmbedSeo.showVersionHistory(wrapper);
                  break;

                case 'audit-log':
                  Drupal.pdfEmbedSeo.showAuditLog(wrapper);
                  break;

                case 'heatmap':
                  Drupal.pdfEmbedSeo.toggleHeatmap(wrapper);
                  break;

                case 'compliance':
                  Drupal.pdfEmbedSeo.showCompliance(wrapper);
                  break;
              }
            });
          });
        }
      );
    }
  };

  /**
   * Pro+ toolbar action handlers.
   */
  Drupal.pdfEmbedSeo = Drupal.pdfEmbedSeo || {};

  /**
   * Toggle annotations panel.
   */
  Drupal.pdfEmbedSeo.toggleAnnotations = function (wrapper) {
    var panel = wrapper.querySelector('.pdf-viewer-annotations-panel');
    if (panel) {
      var isVisible = panel.style.display !== 'none';
      panel.style.display = isVisible ? 'none' : 'block';
    }
  };

  /**
   * Show version history in a dialog/panel.
   */
  Drupal.pdfEmbedSeo.showVersionHistory = function (wrapper) {
    var documentId = wrapper.querySelector('.pdf-viewer-data')?.getAttribute('data-document-id');
    if (documentId) {
      window.open('/pdf/' + documentId + '/versions', '_blank');
    }
  };

  /**
   * Show audit log.
   */
  Drupal.pdfEmbedSeo.showAuditLog = function () {
    window.open('/admin/reports/pdf-audit-log', '_blank');
  };

  /**
   * Toggle heatmap overlay.
   */
  Drupal.pdfEmbedSeo.toggleHeatmap = function (wrapper) {
    var overlay = wrapper.querySelector('.pdf-viewer-heatmap-overlay');
    if (overlay) {
      var isVisible = overlay.style.display !== 'none';
      overlay.style.display = isVisible ? 'none' : 'block';
    }
  };

  /**
   * Show compliance panel.
   */
  Drupal.pdfEmbedSeo.showCompliance = function () {
    window.open('/admin/config/content/pdf-embed-seo/pro-plus', '_blank');
  };

})(Drupal, drupalSettings, once);
