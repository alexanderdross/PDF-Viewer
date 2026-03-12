/**
 * @file
 * PDF Viewer functionality using PDF.js.
 */

(function (Drupal, drupalSettings, once) {
    'use strict';

    /**
     * PDF Viewer component.
     */
    Drupal.pdfEmbedSeo = Drupal.pdfEmbedSeo || {};

    /**
     * Initialize PDF viewer.
     */
    Drupal.behaviors.pdfEmbedSeoViewer = {
        attach: function (context, settings) {
            once('pdf-viewer', '.pdf-viewer-wrapper', context).forEach(
                function (wrapper) {
                    const viewer = new Drupal.pdfEmbedSeo.Viewer(wrapper, settings.pdfEmbedSeo || {});
                    wrapper.pdfViewer = viewer;
                }
            );
        }
    };

    /**
     * PDF Viewer class.
     *
     * @param {HTMLElement} wrapper - The viewer wrapper element.
     * @param {Object} options - Viewer options.
     */
    Drupal.pdfEmbedSeo.Viewer = function (wrapper, options) {
        this.wrapper = wrapper;
        this.options = options;
        this.pdfDoc = null;
        this.currentPage = 1;
        this.totalPages = 0;
        this.scale = 1;
        this.rendering = false;

        this.hasAcroForm = false;

        this.init();
    };

    /**
     * Initialize the viewer.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.init = function () {
        // Get elements.
        this.container = this.wrapper.querySelector('.pdf-viewer-container');
        this.canvas = this.wrapper.querySelector('.pdf-viewer-canvas');
        this.ctx = this.canvas.getContext('2d');
        this.annotationLayer = this.wrapper.querySelector('.pdf-viewer-annotation-layer');
        this.formToolbar = this.wrapper.querySelector('.pdf-viewer-form-toolbar');
        this.loading = this.wrapper.querySelector('.pdf-viewer-loading');
        this.error = this.wrapper.querySelector('.pdf-viewer-error');

        // Navigation elements.
        this.prevBtn = this.wrapper.querySelector('.pdf-viewer-prev');
        this.nextBtn = this.wrapper.querySelector('.pdf-viewer-next');
        this.pageInput = this.wrapper.querySelector('.pdf-viewer-page-input');
        this.pageCount = this.wrapper.querySelector('.pdf-viewer-page-count');

        // Zoom elements.
        this.zoomInBtn = this.wrapper.querySelector('.pdf-viewer-zoom-in');
        this.zoomOutBtn = this.wrapper.querySelector('.pdf-viewer-zoom-out');
        this.zoomLevel = this.wrapper.querySelector('.pdf-viewer-zoom-level');
        this.zoomSelect = this.wrapper.querySelector('.pdf-viewer-zoom-select');

        // Action buttons.
        this.printBtn = this.wrapper.querySelector('.pdf-viewer-print');
        this.downloadBtn = this.wrapper.querySelector('.pdf-viewer-download');
        this.fullscreenBtn = this.wrapper.querySelector('.pdf-viewer-fullscreen');

        // Bind events.
        this.bindEvents();

        // Load PDF.
        this.loadPdf();
    };

    /**
     * Bind event handlers.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.bindEvents = function () {
        const self = this;

        // Navigation.
        if (this.prevBtn) {
            this.prevBtn.addEventListener(
                'click', function () {
                    self.goToPage(self.currentPage - 1);
                }
            );
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener(
                'click', function () {
                    self.goToPage(self.currentPage + 1);
                }
            );
        }

        if (this.pageInput) {
            this.pageInput.addEventListener(
                'change', function () {
                    self.goToPage(parseInt(this.value, 10));
                }
            );

            this.pageInput.addEventListener(
                'keypress', function (e) {
                    if (e.key === 'Enter') {
                        self.goToPage(parseInt(this.value, 10));
                    }
                }
            );
        }

        // Zoom.
        if (this.zoomInBtn) {
            this.zoomInBtn.addEventListener(
                'click', function () {
                    self.setZoom(self.scale + 0.25);
                }
            );
        }

        if (this.zoomOutBtn) {
            this.zoomOutBtn.addEventListener(
                'click', function () {
                    self.setZoom(self.scale - 0.25);
                }
            );
        }

        if (this.zoomSelect) {
            this.zoomSelect.addEventListener(
                'change', function () {
                    const value = this.value;
                    if (value === 'page-fit' || value === 'page-width') {
                        self.setZoomMode(value);
                    } else {
                        self.setZoom(parseFloat(value));
                    }
                }
            );
        }

        // Actions.
        if (this.printBtn) {
            this.printBtn.addEventListener(
                'click', function () {
                    self.printPdf();
                }
            );
        }

        if (this.downloadBtn) {
            this.downloadBtn.addEventListener(
                'click', function () {
                    self.downloadPdf();
                }
            );
        }

        if (this.fullscreenBtn) {
            this.fullscreenBtn.addEventListener(
                'click', function () {
                    self.toggleFullscreen();
                }
            );
        }

        // Keyboard navigation.
        document.addEventListener(
            'keydown', function (e) {
                if (!self.wrapper.contains(document.activeElement) && document.activeElement !== document.body) {
                    return;
                }

                switch (e.key) {
                case 'ArrowLeft':
                case 'PageUp':
                    self.goToPage(self.currentPage - 1);
                    e.preventDefault();
                    break;

                case 'ArrowRight':
                case 'PageDown':
                    self.goToPage(self.currentPage + 1);
                    e.preventDefault();
                    break;

                case 'Home':
                    self.goToPage(1);
                    e.preventDefault();
                    break;

                case 'End':
                    self.goToPage(self.totalPages);
                    e.preventDefault();
                    break;
                }
            }
        );

        // Prevent right-click on canvas if download is disabled.
        if (!this.options.allowDownload) {
            this.canvas.addEventListener(
                'contextmenu', function (e) {
                    e.preventDefault();
                }
            );
        }

        // Form toolbar events.
        if (this.formToolbar) {
            var clearBtn = this.formToolbar.querySelector('.pdf-viewer-form-clear');
            if (clearBtn) {
                clearBtn.addEventListener(
                    'click', function () {
                        if (confirm(Drupal.t('Clear all form fields? This cannot be undone.'))) {
                            self.clearFormFields();
                        }
                    }
                );
            }

            var downloadBtn = this.formToolbar.querySelector('.pdf-viewer-form-download');
            if (downloadBtn) {
                downloadBtn.addEventListener(
                    'click', function () {
                        self.downloadFilledPdf();
                    }
                );
            }
        }
    };

    /**
     * Load the PDF document.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.loadPdf = function () {
        const self = this;
        const pdfUrl = this.options.pdfUrl;

        if (!pdfUrl) {
            this.showError('PDF URL not provided.');
            return;
        }

        this.showLoading();

        // Check if PDF.js is available.
        if (typeof pdfjsLib === 'undefined') {
            this.showError('PDF.js library not loaded.');
            return;
        }

        // Set worker source.
        pdfjsLib.GlobalWorkerOptions.workerSrc = drupalSettings.pdfEmbedSeo.workerSrc || '';

        // Load the PDF.
        pdfjsLib.getDocument(pdfUrl).promise.then(
            function (pdf) {
                self.pdfDoc = pdf;
                self.totalPages = pdf.numPages;
                self.updatePageCount();
                self.hideLoading();

                // Detect AcroForm fields.
                self.detectAcroForm(pdf);

                self.renderPage(1);

                // Trigger loaded event.
                self.wrapper.dispatchEvent(
                    new CustomEvent(
                        'pdfLoaded', {
                            detail: { totalPages: pdf.numPages }
                        }
                    )
                );
            }
        ).catch(
            function (error) {
                console.error('Error loading PDF:', error);
                self.showError('Failed to load PDF document.');
            }
        );
    };

    /**
     * Render a specific page.
     *
     * @param {number} pageNum - Page number to render.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.renderPage = function (pageNum) {
        const self = this;

        if (this.rendering) {
            return;
        }

        this.rendering = true;

        this.pdfDoc.getPage(pageNum).then(
            function (page) {
                const viewport = page.getViewport({ scale: self.scale });

                self.canvas.height = viewport.height;
                self.canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: self.ctx,
                    viewport: viewport
                };

                page.render(renderContext).promise.then(
                    function () {
                        self.rendering = false;
                        self.currentPage = pageNum;
                        self.updateNavigation();

                        // Render annotation layer for form fields.
                        if (self.hasAcroForm && self.annotationLayer) {
                            self.renderAnnotationLayer(page, viewport);
                        }

                        // Trigger page rendered event.
                        self.wrapper.dispatchEvent(
                            new CustomEvent(
                                'pageRendered', {
                                    detail: { page: pageNum, totalPages: self.totalPages }
                                }
                            )
                        );
                    }
                );
            }
        );
    };

    /**
     * Go to a specific page.
     *
     * @param {number} pageNum - Page number.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.goToPage = function (pageNum) {
        if (pageNum < 1 || pageNum > this.totalPages) {
            return;
        }

        this.renderPage(pageNum);
    };

    /**
     * Set zoom level.
     *
     * @param {number} scale - Zoom scale.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.setZoom = function (scale) {
        if (scale < 0.25 || scale > 4) {
            return;
        }

        this.scale = scale;
        this.updateZoomDisplay();
        this.renderPage(this.currentPage);
    };

    /**
     * Set zoom mode (page-fit or page-width).
     *
     * @param {string} mode - Zoom mode.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.setZoomMode = function (mode) {
        const self = this;

        this.pdfDoc.getPage(this.currentPage).then(
            function (page) {
                const containerWidth = self.container.clientWidth - 40;
                const containerHeight = self.container.clientHeight - 40;
                const viewport = page.getViewport({ scale: 1 });

                let scale;
                if (mode === 'page-width') {
                    scale = containerWidth / viewport.width;
                } else {
                    const scaleWidth = containerWidth / viewport.width;
                    const scaleHeight = containerHeight / viewport.height;
                    scale = Math.min(scaleWidth, scaleHeight);
                }

                self.setZoom(scale);
            }
        );
    };

    /**
     * Update navigation state.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.updateNavigation = function () {
        if (this.prevBtn) {
            this.prevBtn.disabled = (this.currentPage <= 1);
        }

        if (this.nextBtn) {
            this.nextBtn.disabled = (this.currentPage >= this.totalPages);
        }

        if (this.pageInput) {
            this.pageInput.value = this.currentPage;
            this.pageInput.max = this.totalPages;
        }
    };

    /**
     * Update page count display.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.updatePageCount = function () {
        if (this.pageCount) {
            this.pageCount.textContent = this.totalPages;
        }
    };

    /**
     * Update zoom level display.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.updateZoomDisplay = function () {
        const percentage = Math.round(this.scale * 100) + '%';

        if (this.zoomLevel) {
            this.zoomLevel.textContent = percentage;
        }

        if (this.zoomSelect) {
            // Update select if value matches.
            const options = this.zoomSelect.options;
            let found = false;
            for (let i = 0; i < options.length; i++) {
                if (parseFloat(options[i].value) === this.scale) {
                    this.zoomSelect.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                this.zoomSelect.selectedIndex = -1;
            }
        }
    };

    /**
     * Print the PDF.
     *
     * Opens the PDF in a new window for native browser printing.
     * This provides better quality on iOS/Safari and all platforms
     * compared to printing the canvas element directly.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.printPdf = function () {
        if (!this.options.allowPrint) {
            return;
        }

        var pdfUrl = this.options.pdfUrl;
        if (!pdfUrl) {
            console.warn('PDF URL not available for printing');
            return;
        }

        // Open PDF in new window for native printing (better quality, especially on iOS)
        var printWindow = window.open(pdfUrl, '_blank');
        if (printWindow) {
            printWindow.addEventListener(
                'load', function () {
                    // Delay for Safari/iOS compatibility
                    setTimeout(
                        function () {
                            printWindow.print();
                        }, 500
                    );
                }
            );
        } else {
            // Fallback if popup blocked - try direct print
            window.print();
        }
    };

    /**
     * Download the PDF.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.downloadPdf = function () {
        if (!this.options.allowDownload) {
            return;
        }

        const link = document.createElement('a');
        link.href = this.options.pdfUrl;
        link.download = this.options.documentTitle || 'document.pdf';
        link.click();
    };

    /**
     * Toggle fullscreen mode.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.toggleFullscreen = function () {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            this.wrapper.requestFullscreen();
        }
    };

    /**
     * Show loading state.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.showLoading = function () {
        if (this.loading) {
            this.loading.style.display = 'flex';
        }
        if (this.error) {
            this.error.style.display = 'none';
        }
    };

    /**
     * Hide loading state.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.hideLoading = function () {
        if (this.loading) {
            this.loading.style.display = 'none';
        }
    };

    /**
     * Show error state.
     *
     * @param {string} message - Error message.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.showError = function (message) {
        this.hideLoading();
        if (this.error) {
            this.error.style.display = 'flex';
            const p = this.error.querySelector('p');
            if (p) {
                p.textContent = message;
            }
        }
    };

    /**
     * Detect if the PDF contains AcroForm fields.
     *
     * @param {Object} pdf - The PDF document object.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.detectAcroForm = function (pdf) {
        const self = this;

        // Check first page for annotations with form widgets.
        pdf.getPage(1).then(
            function (page) {
                page.getAnnotations().then(
                    function (annotations) {
                        const formAnnotations = annotations.filter(
                            function (a) {
                                return a.subtype === 'Widget';
                            }
                        );

                        if (formAnnotations.length > 0) {
                            self.hasAcroForm = true;
                            if (self.formToolbar) {
                                self.formToolbar.style.display = 'flex';
                            }
                            // Re-render current page to show annotation layer.
                            self.rendering = false;
                            self.renderPage(self.currentPage);
                        }
                    }
                );
            }
        );
    };

    /**
     * Render the annotation layer for form fields.
     *
     * @param {Object} page - The PDF page object.
     * @param {Object} viewport - The viewport object.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.renderAnnotationLayer = function (page, viewport) {
        const self = this;

        // Clear existing annotation layer.
        self.annotationLayer.innerHTML = '';
        self.annotationLayer.style.width = viewport.width + 'px';
        self.annotationLayer.style.height = viewport.height + 'px';

        page.getAnnotations().then(
            function (annotations) {
                if (!annotations || annotations.length === 0) {
                    return;
                }

                // Check if AnnotationLayer is available in PDF.js.
                if (typeof pdfjsLib.AnnotationLayer !== 'undefined') {
                    pdfjsLib.AnnotationLayer.render(
                        {
                            viewport: viewport.clone({ dontFlip: true }),
                            div: self.annotationLayer,
                            annotations: annotations,
                            page: page,
                            renderInteractiveForms: true
                        }
                    );
                } else {
                    // Fallback: render form fields manually for older PDF.js versions.
                    self.renderFormFieldsFallback(annotations, viewport);
                }
            }
        );
    };

    /**
     * Fallback form field rendering for PDF.js versions without AnnotationLayer.
     *
     * @param {Array} annotations - The annotation objects.
     * @param {Object} viewport - The viewport object.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.renderFormFieldsFallback = function (annotations, viewport) {
        const self = this;

        annotations.forEach(
            function (annotation) {
                if (annotation.subtype !== 'Widget') {
                    return;
                }

                const rect = annotation.rect;
                const left = rect[0] * viewport.scale;
                const bottom = rect[1] * viewport.scale;
                const width = (rect[2] - rect[0]) * viewport.scale;
                const height = (rect[3] - rect[1]) * viewport.scale;
                const top = viewport.height - bottom - height;

                var element;

                switch (annotation.fieldType) {
                case 'Tx':
                    element = document.createElement('input');
                    element.type = 'text';
                    element.className = 'pdf-form-field pdf-form-text';
                    if (annotation.fieldValue) {
                        element.value = annotation.fieldValue;
                    }
                    if (annotation.maxLen) {
                        element.maxLength = annotation.maxLen;
                    }
                    break;

                case 'Btn':
                    if (annotation.checkBox) {
                        element = document.createElement('input');
                        element.type = 'checkbox';
                        element.className = 'pdf-form-field pdf-form-checkbox';
                        if (annotation.fieldValue && annotation.fieldValue !== 'Off') {
                            element.checked = true;
                        }
                    } else if (annotation.radioButton) {
                        element = document.createElement('input');
                        element.type = 'radio';
                        element.className = 'pdf-form-field pdf-form-radio';
                        element.name = annotation.fieldName;
                        if (annotation.fieldValue && annotation.fieldValue !== 'Off') {
                            element.checked = true;
                        }
                    }
                    break;

                case 'Ch':
                    element = document.createElement('select');
                    element.className = 'pdf-form-field pdf-form-select';
                    if (annotation.options) {
                        annotation.options.forEach(
                            function (opt) {
                                var option = document.createElement('option');
                                option.value = opt.exportValue || opt.displayValue;
                                option.textContent = opt.displayValue;
                                if (annotation.fieldValue === option.value) {
                                    option.selected = true;
                                }
                                element.appendChild(option);
                            }
                        );
                    }
                    break;
                }

                if (element) {
                    element.style.position = 'absolute';
                    element.style.left = left + 'px';
                    element.style.top = top + 'px';
                    element.style.width = width + 'px';
                    element.style.height = height + 'px';
                    element.setAttribute('data-field-name', annotation.fieldName || '');
                    self.annotationLayer.appendChild(element);
                }
            }
        );
    };

    /**
     * Clear all form fields in the annotation layer.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.clearFormFields = function () {
        if (!this.annotationLayer) {
            return;
        }

        var inputs = this.annotationLayer.querySelectorAll('input, select, textarea');
        inputs.forEach(
            function (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            }
        );
    };

    /**
     * Download the PDF with filled form data.
     */
    Drupal.pdfEmbedSeo.Viewer.prototype.downloadFilledPdf = function () {
        if (!this.options.allowDownload || !this.options.pdfUrl) {
            return;
        }

        // Download the original PDF (form data is client-side only).
        const link = document.createElement('a');
        link.href = this.options.pdfUrl;
        link.download = (this.options.documentTitle || 'document') + '-filled.pdf';
        link.click();
    };

})(Drupal, drupalSettings, once);
