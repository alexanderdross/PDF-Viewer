/**
 * PDF Viewer scripts for PDF Embed & SEO Optimize.
 *
 * Uses Mozilla's PDF.js library to render PDFs.
 *
 * @package PDF_Viewer_2026
 */

(function($) {
    'use strict';

    /**
     * PDF Viewer Class
     */
    var PDFViewer = function(container) {
        this.container = $(container);
        this.canvas = this.container.find('.pdf-embed-seo-optimize-canvas')[0];
        this.ctx = this.canvas.getContext('2d');
        this.annotationLayer = this.container.find('.pdf-embed-seo-optimize-annotation-layer')[0];
        this.formToolbar = this.container.find('.pdf-embed-seo-optimize-form-toolbar');
        this.loading = this.container.find('.pdf-embed-seo-optimize-loading');
        this.pageInput = this.container.find('.pdf-embed-seo-optimize-page-input');
        this.totalPages = this.container.find('.pdf-embed-seo-optimize-total-pages');
        this.zoomLevel = this.container.find('.pdf-embed-seo-optimize-zoom-level');

        this.pdfDoc = null;
        this.currentPage = 1;
        this.numPages = 0;
        this.scale = 1.0;
        this.minScale = 0.25;
        this.maxScale = 4.0;
        this.rendering = false;
        this.pendingPage = null;
        this.pdfUrl = null;
        this.pdfTitle = '';
        this.hasAcroForm = false;

        this.init();
    };

    PDFViewer.prototype = {
        /**
         * Initialize the viewer
         */
        init: function() {
            this.bindEvents();
            this.loadPDF();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            var self = this;

            // Navigation buttons
            this.container.on('click', '.pdf-embed-seo-optimize-prev', function() {
                self.prevPage();
            });

            this.container.on('click', '.pdf-embed-seo-optimize-next', function() {
                self.nextPage();
            });

            // Page input
            this.pageInput.on('change', function() {
                var page = parseInt($(this).val(), 10);
                if (page >= 1 && page <= self.numPages) {
                    self.goToPage(page);
                } else {
                    $(this).val(self.currentPage);
                }
            });

            // Zoom buttons
            this.container.on('click', '.pdf-embed-seo-optimize-zoom-in', function() {
                self.zoomIn();
            });

            this.container.on('click', '.pdf-embed-seo-optimize-zoom-out', function() {
                self.zoomOut();
            });

            // Download button
            this.container.on('click', '.pdf-embed-seo-optimize-download', function() {
                self.download();
            });

            // Print button
            this.container.on('click', '.pdf-embed-seo-optimize-print', function() {
                self.print();
            });

            // Fullscreen button
            this.container.on('click', '.pdf-embed-seo-optimize-fullscreen', function() {
                self.toggleFullscreen();
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if (!self.container.is(':visible')) return;

                switch(e.key) {
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        self.prevPage();
                        e.preventDefault();
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                        self.nextPage();
                        e.preventDefault();
                        break;
                }
            });

            // Handle window resize
            var resizeTimeout;
            $(window).on('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (self.pdfDoc) {
                        self.renderPage(self.currentPage);
                    }
                }, 250);
            });

            // Escape fullscreen
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && self.container.hasClass('is-fullscreen')) {
                    self.toggleFullscreen();
                }
            });

            // Form toolbar buttons
            this.container.on('click', '.pdf-embed-seo-optimize-form-clear', function() {
                if (confirm('Clear all form fields? This cannot be undone.')) {
                    self.clearFormFields();
                }
            });

            this.container.on('click', '.pdf-embed-seo-optimize-form-download', function() {
                self.downloadFilledPdf();
            });
        },

        /**
         * Load PDF via REST API
         */
        loadPDF: function() {
            var self = this;

            // Get post ID from shortcode container or use global
            var postId = this.container.closest('.pdf-embed-seo-optimize-shortcode').data('post-id') || pdfEmbedSeo.postId;

            if (!postId) {
                console.error('PDF Viewer: No post ID found');
                self.showError('No PDF document ID specified.');
                return;
            }

            console.log('PDF Viewer: Loading PDF for post ID:', postId);

            // Use REST API endpoint (cache-friendly, no nonce required for public endpoints)
            var apiUrl = pdfEmbedSeo.restUrl + postId + '/data';

            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(function(data) {
                        throw new Error(data.message || 'HTTP ' + response.status);
                    });
                }
                return response.json();
            })
            .then(function(data) {
                console.log('PDF Viewer: REST API response:', data);
                self.pdfUrl = data.pdf_url;
                self.pdfTitle = data.filename ? data.filename.replace('.pdf', '') : 'document';
                console.log('PDF Viewer: PDF URL:', self.pdfUrl);
                self.initPDF();
            })
            .catch(function(error) {
                console.error('PDF Viewer: REST API error:', error);
                self.showError(error.message || pdfEmbedSeo.strings.error);
            });
        },

        /**
         * Initialize PDF.js with the PDF URL
         */
        initPDF: function() {
            var self = this;

            if (!this.pdfUrl) {
                console.error('PDF Viewer: No PDF URL provided');
                self.showError('No PDF file URL available.');
                return;
            }

            console.log('PDF Viewer: Initializing PDF.js with URL:', this.pdfUrl);

            // Check if pdfjsLib is available
            if (typeof pdfjsLib === 'undefined') {
                console.error('PDF Viewer: PDF.js library not loaded');
                self.showError('PDF.js library failed to load.');
                return;
            }

            // Load the PDF
            var loadingTask = pdfjsLib.getDocument(this.pdfUrl);

            loadingTask.promise.then(function(pdf) {
                console.log('PDF Viewer: PDF loaded successfully, pages:', pdf.numPages);
                self.pdfDoc = pdf;
                self.numPages = pdf.numPages;
                self.totalPages.text(self.numPages);
                self.pageInput.attr('max', self.numPages);

                // Detect AcroForm fields
                self.detectAcroForm(pdf);

                // Render first page
                self.renderPage(1);

                // Update navigation buttons
                self.updateNavigation();

            }).catch(function(error) {
                console.error('PDF Viewer: PDF.js loading error:', error);
                var errorMsg = pdfEmbedSeo.strings.error;
                if (error.message) {
                    errorMsg += ' (' + error.message + ')';
                }
                // Check for common errors
                if (error.name === 'MissingPDFException') {
                    errorMsg = 'PDF file not found or inaccessible.';
                } else if (error.message && error.message.indexOf('CORS') !== -1) {
                    errorMsg = 'PDF blocked by browser security (CORS).';
                }
                self.showError(errorMsg);
            });
        },

        /**
         * Render a specific page
         */
        renderPage: function(num) {
            var self = this;

            if (this.rendering) {
                this.pendingPage = num;
                return;
            }

            this.rendering = true;
            this.currentPage = num;

            this.pdfDoc.getPage(num).then(function(page) {
                // Calculate scale to fit container width
                var containerWidth = self.container.find('.pdf-embed-seo-optimize-viewer').width() - 40;
                var viewport = page.getViewport({ scale: 1 });
                var defaultScale = containerWidth / viewport.width;

                // Apply user scale
                var finalScale = defaultScale * self.scale;
                viewport = page.getViewport({ scale: finalScale });

                // Set canvas dimensions
                self.canvas.height = viewport.height;
                self.canvas.width = viewport.width;

                // Render
                var renderContext = {
                    canvasContext: self.ctx,
                    viewport: viewport
                };

                var renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    self.rendering = false;
                    self.loading.hide();

                    // Update UI
                    self.pageInput.val(num);
                    self.updateNavigation();

                    // Render annotation layer for form fields
                    if (self.hasAcroForm && self.annotationLayer) {
                        self.renderAnnotationLayer(page, viewport);
                    }

                    // Render pending page if any
                    if (self.pendingPage !== null) {
                        var pending = self.pendingPage;
                        self.pendingPage = null;
                        self.renderPage(pending);
                    }
                });

            }).catch(function(error) {
                console.error('Page render error:', error);
                self.rendering = false;
                self.showError(pdfEmbedSeo.strings.error);
            });
        },

        /**
         * Go to previous page
         */
        prevPage: function() {
            if (this.currentPage > 1) {
                this.goToPage(this.currentPage - 1);
            }
        },

        /**
         * Go to next page
         */
        nextPage: function() {
            if (this.currentPage < this.numPages) {
                this.goToPage(this.currentPage + 1);
            }
        },

        /**
         * Go to specific page
         */
        goToPage: function(num) {
            if (num >= 1 && num <= this.numPages && num !== this.currentPage) {
                this.renderPage(num);
            }
        },

        /**
         * Update navigation button states
         */
        updateNavigation: function() {
            var prevBtn = this.container.find('.pdf-embed-seo-optimize-prev');
            var nextBtn = this.container.find('.pdf-embed-seo-optimize-next');

            prevBtn.prop('disabled', this.currentPage <= 1);
            nextBtn.prop('disabled', this.currentPage >= this.numPages);
        },

        /**
         * Zoom in
         */
        zoomIn: function() {
            if (this.scale < this.maxScale) {
                this.scale = Math.min(this.scale + 0.25, this.maxScale);
                this.updateZoom();
            }
        },

        /**
         * Zoom out
         */
        zoomOut: function() {
            if (this.scale > this.minScale) {
                this.scale = Math.max(this.scale - 0.25, this.minScale);
                this.updateZoom();
            }
        },

        /**
         * Update zoom display and re-render
         */
        updateZoom: function() {
            var percentage = Math.round(this.scale * 100);
            this.zoomLevel.text(percentage + '%');
            this.renderPage(this.currentPage);
        },

        /**
         * Download PDF
         */
        download: function() {
            if (!pdfEmbedSeo.allowDownload) {
                return;
            }

            var link = document.createElement('a');
            link.href = this.pdfUrl;
            link.download = this.pdfTitle + '.pdf';
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        /**
         * Print PDF
         */
        print: function() {
            if (!pdfEmbedSeo.allowPrint) {
                return;
            }

            // Open PDF in new window for printing
            var printWindow = window.open(this.pdfUrl, '_blank');
            if (printWindow) {
                printWindow.addEventListener('load', function() {
                    printWindow.print();
                });
            }
        },

        /**
         * Toggle fullscreen mode
         */
        toggleFullscreen: function() {
            this.container.toggleClass('is-fullscreen');

            var icon = this.container.find('.pdf-embed-seo-optimize-fullscreen .dashicons');

            if (this.container.hasClass('is-fullscreen')) {
                icon.removeClass('dashicons-fullscreen-alt').addClass('dashicons-fullscreen-exit-alt');
                $('body').css('overflow', 'hidden');
            } else {
                icon.removeClass('dashicons-fullscreen-exit-alt').addClass('dashicons-fullscreen-alt');
                $('body').css('overflow', '');
            }

            // Re-render to fit new dimensions
            if (this.pdfDoc) {
                var self = this;
                setTimeout(function() {
                    self.renderPage(self.currentPage);
                }, 100);
            }
        },

        /**
         * Show error message
         */
        showError: function(message) {
            this.loading.hide();
            this.container.find('.pdf-embed-seo-optimize-viewer').html(
                '<div class="pdf-embed-seo-optimize-error">' + message + '</div>'
            );
        },

        /**
         * Detect if the PDF contains AcroForm fields
         */
        detectAcroForm: function(pdf) {
            var self = this;

            pdf.getPage(1).then(function(page) {
                page.getAnnotations().then(function(annotations) {
                    var formAnnotations = annotations.filter(function(a) {
                        return a.subtype === 'Widget';
                    });

                    if (formAnnotations.length > 0) {
                        self.hasAcroForm = true;
                        self.formToolbar.show();
                        // Re-render to show annotation layer
                        self.rendering = false;
                        self.renderPage(self.currentPage);
                    }
                });
            });
        },

        /**
         * Render the annotation layer for form fields
         */
        renderAnnotationLayer: function(page, viewport) {
            var self = this;
            var layer = $(self.annotationLayer);

            // Clear existing
            layer.empty();
            layer.css({
                width: viewport.width + 'px',
                height: viewport.height + 'px'
            });

            page.getAnnotations().then(function(annotations) {
                if (!annotations || annotations.length === 0) {
                    return;
                }

                // Use PDF.js AnnotationLayer if available
                if (typeof pdfjsLib !== 'undefined' && pdfjsLib.AnnotationLayer) {
                    pdfjsLib.AnnotationLayer.render({
                        viewport: viewport.clone({ dontFlip: true }),
                        div: self.annotationLayer,
                        annotations: annotations,
                        page: page,
                        renderInteractiveForms: true
                    });
                } else {
                    // Fallback: render form fields manually
                    self.renderFormFieldsFallback(annotations, viewport);
                }
            });
        },

        /**
         * Fallback form field rendering for older PDF.js versions
         */
        renderFormFieldsFallback: function(annotations, viewport) {
            var self = this;

            annotations.forEach(function(annotation) {
                if (annotation.subtype !== 'Widget') {
                    return;
                }

                var rect = annotation.rect;
                var left = rect[0] * viewport.scale;
                var bottom = rect[1] * viewport.scale;
                var width = (rect[2] - rect[0]) * viewport.scale;
                var height = (rect[3] - rect[1]) * viewport.scale;
                var top = viewport.height - bottom - height;
                var element;

                switch (annotation.fieldType) {
                    case 'Tx':
                        element = $('<input type="text" class="pdf-form-field pdf-form-text">');
                        if (annotation.fieldValue) element.val(annotation.fieldValue);
                        if (annotation.maxLen) element.attr('maxlength', annotation.maxLen);
                        break;

                    case 'Btn':
                        if (annotation.checkBox) {
                            element = $('<input type="checkbox" class="pdf-form-field pdf-form-checkbox">');
                            if (annotation.fieldValue && annotation.fieldValue !== 'Off') {
                                element.prop('checked', true);
                            }
                        } else if (annotation.radioButton) {
                            element = $('<input type="radio" class="pdf-form-field pdf-form-radio">');
                            element.attr('name', annotation.fieldName);
                            if (annotation.fieldValue && annotation.fieldValue !== 'Off') {
                                element.prop('checked', true);
                            }
                        }
                        break;

                    case 'Ch':
                        element = $('<select class="pdf-form-field pdf-form-select">');
                        if (annotation.options) {
                            annotation.options.forEach(function(opt) {
                                var option = $('<option>');
                                option.val(opt.exportValue || opt.displayValue);
                                option.text(opt.displayValue);
                                if (annotation.fieldValue === option.val()) {
                                    option.prop('selected', true);
                                }
                                element.append(option);
                            });
                        }
                        break;
                }

                if (element) {
                    element.css({
                        position: 'absolute',
                        left: left + 'px',
                        top: top + 'px',
                        width: width + 'px',
                        height: height + 'px'
                    });
                    element.attr('data-field-name', annotation.fieldName || '');
                    $(self.annotationLayer).append(element);
                }
            });
        },

        /**
         * Clear all form fields
         */
        clearFormFields: function() {
            $(this.annotationLayer).find('input, select, textarea').each(function() {
                if (this.type === 'checkbox' || this.type === 'radio') {
                    this.checked = false;
                } else {
                    this.value = '';
                }
            });
        },

        /**
         * Download PDF with filled form data
         */
        downloadFilledPdf: function() {
            if (!pdfEmbedSeo.allowDownload || !this.pdfUrl) {
                return;
            }

            var link = document.createElement('a');
            link.href = this.pdfUrl;
            link.download = (this.pdfTitle || 'document') + '-filled.pdf';
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };

    /**
     * Initialize all PDF viewers on page load
     */
    $(document).ready(function() {
        $('.pdf-embed-seo-optimize-container').each(function() {
            new PDFViewer(this);
        });
    });

    // Expose for potential external use
    window.PDFViewer2026 = PDFViewer;

})(jQuery);
