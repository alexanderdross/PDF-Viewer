/**
 * @file
 * Pro+ annotations support for PDF viewer.
 */

(function (Drupal, drupalSettings, once) {
  'use strict';

  /**
   * Initialize annotations toolbar and functionality.
   */
  Drupal.behaviors.pdfEmbedSeoAnnotations = {
    attach: function (context) {
      once('pro-plus-annotations', '.pdf-viewer-wrapper', context).forEach(
        function (wrapper) {
          var toolbar = wrapper.querySelector('.pdf-viewer-annotations-toolbar');
          if (!toolbar) {
            return;
          }

          var documentId = wrapper.querySelector('.pdf-viewer-data')?.getAttribute('data-document-id');
          if (!documentId) {
            return;
          }

          // Load annotations for the document.
          Drupal.pdfEmbedSeo.loadAnnotations(wrapper, documentId);

          // Bind annotation tool buttons.
          var tools = toolbar.querySelectorAll('[data-tool]');
          tools.forEach(function (tool) {
            tool.addEventListener('click', function () {
              var toolType = this.getAttribute('data-tool');
              Drupal.pdfEmbedSeo.selectAnnotationTool(wrapper, toolType);
            });
          });
        }
      );
    }
  };

  Drupal.pdfEmbedSeo = Drupal.pdfEmbedSeo || {};

  /**
   * Load annotations for a document via API.
   */
  Drupal.pdfEmbedSeo.loadAnnotations = function (wrapper, documentId) {
    fetch('/api/pdf-embed-seo/v1/documents/' + documentId + '/annotations')
      .then(function (response) { return response.json(); })
      .then(function (data) {
        if (data.annotations && data.annotations.length > 0) {
          Drupal.pdfEmbedSeo.renderAnnotations(wrapper, data.annotations);
        }
      })
      .catch(function (error) {
        console.warn('Failed to load annotations:', error);
      });
  };

  /**
   * Render annotations on the viewer.
   */
  Drupal.pdfEmbedSeo.renderAnnotations = function (wrapper, annotations) {
    var layer = wrapper.querySelector('.pdf-viewer-annotation-layer');
    if (!layer) {
      return;
    }

    annotations.forEach(function (annotation) {
      var el = document.createElement('div');
      el.className = 'pdf-annotation pdf-annotation-' + (annotation.type || 'comment');
      el.setAttribute('data-annotation-id', annotation.uuid || '');
      el.title = annotation.content || '';

      if (annotation.position) {
        el.style.left = (annotation.position.x || 0) + '%';
        el.style.top = (annotation.position.y || 0) + '%';
        el.style.width = (annotation.position.width || 5) + '%';
        el.style.height = (annotation.position.height || 2) + '%';
      }

      layer.appendChild(el);
    });
  };

  /**
   * Select an annotation tool.
   */
  Drupal.pdfEmbedSeo.selectAnnotationTool = function (wrapper, toolType) {
    var toolbar = wrapper.querySelector('.pdf-viewer-annotations-toolbar');
    if (!toolbar) {
      return;
    }

    // Deselect all tools.
    toolbar.querySelectorAll('[data-tool]').forEach(function (btn) {
      btn.classList.remove('active');
    });

    // Select the clicked tool.
    var selected = toolbar.querySelector('[data-tool="' + toolType + '"]');
    if (selected) {
      selected.classList.add('active');
    }

    // Store active tool on wrapper.
    wrapper.setAttribute('data-active-annotation-tool', toolType);
  };

})(Drupal, drupalSettings, once);
