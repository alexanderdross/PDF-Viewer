/**
 * PDF Embed & SEO Optimize Theme - Main JavaScript
 * Self-hosted, no external dependencies
 *
 * @package PDFViewer
 * @version 1.0.0
 */

(function() {
    'use strict';

    /**
     * DOM Ready Helper
     */
    function domReady(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    /**
     * Header Scroll Detection
     */
    function initHeaderScroll() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        let lastScrollY = 0;
        let ticking = false;

        function updateHeader() {
            const scrollY = window.scrollY;

            if (scrollY > 20) {
                header.classList.add('shadow-soft', 'is-scrolled');
            } else {
                header.classList.remove('shadow-soft', 'is-scrolled');
            }

            // Update nav padding
            const nav = header.querySelector('nav');
            if (nav) {
                if (scrollY > 20) {
                    nav.classList.remove('py-4');
                    nav.classList.add('py-2');
                } else {
                    nav.classList.remove('py-2');
                    nav.classList.add('py-4');
                }
            }

            lastScrollY = scrollY;
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }, { passive: true });

        // Initial check
        updateHeader();
    }

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuButton = document.querySelector('[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');

        if (!menuButton || !mobileMenu) return;

        let isOpen = false;

        function toggleMenu() {
            isOpen = !isOpen;

            menuButton.setAttribute('aria-expanded', isOpen);
            menuButton.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');

            // Toggle icons
            const menuIcon = menuButton.querySelector('svg:first-of-type');
            const closeIcon = menuButton.querySelector('svg:last-of-type');

            if (menuIcon && closeIcon) {
                menuIcon.style.display = isOpen ? 'none' : 'block';
                closeIcon.style.display = isOpen ? 'block' : 'none';
            }

            // Toggle menu visibility with animation
            if (isOpen) {
                mobileMenu.style.display = 'block';
                mobileMenu.style.opacity = '0';
                mobileMenu.style.transform = 'translateY(-8px)';

                requestAnimationFrame(function() {
                    mobileMenu.style.transition = 'opacity 200ms ease-out, transform 200ms ease-out';
                    mobileMenu.style.opacity = '1';
                    mobileMenu.style.transform = 'translateY(0)';
                });
            } else {
                mobileMenu.style.transition = 'opacity 150ms ease-in, transform 150ms ease-in';
                mobileMenu.style.opacity = '0';
                mobileMenu.style.transform = 'translateY(-8px)';

                setTimeout(function() {
                    mobileMenu.style.display = 'none';
                }, 150);
            }

            // Prevent body scroll when menu is open
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        function closeMenu() {
            if (isOpen) {
                toggleMenu();
            }
        }

        menuButton.addEventListener('click', toggleMenu);

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeMenu();
                menuButton.focus();
            }
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (isOpen && !mobileMenu.contains(e.target) && !menuButton.contains(e.target)) {
                closeMenu();
            }
        });

        // Close on link click
        mobileMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });

        // Initial state
        mobileMenu.style.display = 'none';
    }

    /**
     * Dropdown Menus
     */
    function initDropdowns() {
        var dropdowns = document.querySelectorAll('[data-dropdown]');
        var currentlyOpen = null;

        function closeDropdown(dropdown) {
            var menu = dropdown.querySelector('[data-dropdown-menu]');
            var trigger = dropdown.querySelector('[data-dropdown-trigger]');
            if (menu) {
                menu.style.display = 'none';
                menu.style.opacity = '0';
                menu.style.position = '';
                menu.style.top = '';
                menu.style.left = '';
                menu.style.right = '';
            }
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        }

        function closeAllDropdowns() {
            dropdowns.forEach(closeDropdown);
            currentlyOpen = null;
        }

        function openDropdown(dropdown) {
            var trigger = dropdown.querySelector('[data-dropdown-trigger]');
            var menu = dropdown.querySelector('[data-dropdown-menu]');
            if (!menu) return;

            currentlyOpen = dropdown;

            // Use fixed positioning to escape backdrop-filter stacking context
            // and Safari safe-area clipping (viewport-fit=cover on iPad)
            var triggerRect = trigger.getBoundingClientRect();
            var alignRight = menu.id && menu.id.indexOf('download') !== -1;

            menu.style.position = 'fixed';
            menu.style.top = (triggerRect.bottom + 6) + 'px';
            if (alignRight) {
                menu.style.right = (window.innerWidth - triggerRect.right) + 'px';
                menu.style.left = 'auto';
            } else {
                menu.style.left = triggerRect.left + 'px';
                menu.style.right = 'auto';
            }

            menu.style.display = 'block';
            menu.style.opacity = '1';
            menu.style.transform = 'scale(1)';

            if (trigger) {
                trigger.setAttribute('aria-expanded', 'true');
            }
        }

        dropdowns.forEach(function(dropdown) {
            var trigger = dropdown.querySelector('[data-dropdown-trigger]');
            var menu = dropdown.querySelector('[data-dropdown-menu]');

            if (!trigger || !menu) return;

            // Ensure menu starts hidden
            menu.style.display = 'none';

            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var wasOpen = (currentlyOpen === dropdown);
                closeAllDropdowns();

                if (!wasOpen) {
                    openDropdown(dropdown);
                }
            });

            // Keyboard support
            trigger.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    trigger.click();
                }
            });
        });

        // Close when clicking outside - return focus to trigger
        document.addEventListener('click', function() {
            if (currentlyOpen) {
                var trigger = currentlyOpen.querySelector('[data-dropdown-trigger]');
                closeAllDropdowns();
                if (trigger) trigger.focus();
            }
        });

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && currentlyOpen) {
                var trigger = currentlyOpen.querySelector('[data-dropdown-trigger]');
                closeAllDropdowns();
                if (trigger) trigger.focus();
            }
        });
    }

    /**
     * Accordion Component
     */
    function initAccordions() {
        const accordions = document.querySelectorAll('.accordion');

        accordions.forEach(function(accordion) {
            const items = accordion.querySelectorAll('.accordion-item');
            let openItem = null;

            items.forEach(function(item, index) {
                const trigger = item.querySelector('.accordion-trigger');
                const content = item.querySelector('.accordion-content');
                const icon = item.querySelector('.accordion-icon');

                if (!trigger || !content) return;

                // Initial state: height:0, overflow:hidden, transition set via CSS
                trigger.setAttribute('aria-expanded', 'false');

                trigger.addEventListener('click', function() {
                    const isCurrentlyOpen = openItem === index;

                    // Close currently open item
                    if (openItem !== null && openItem !== index) {
                        const prevItem = items[openItem];
                        const prevContent = prevItem.querySelector('.accordion-content');
                        const prevTrigger = prevItem.querySelector('.accordion-trigger');
                        const prevIcon = prevItem.querySelector('.accordion-icon');

                        prevContent.style.height = '0';
                        prevTrigger.setAttribute('aria-expanded', 'false');
                        if (prevIcon) prevIcon.style.transform = 'rotate(0deg)';
                    }

                    // Toggle current item
                    if (isCurrentlyOpen) {
                        content.style.height = '0';
                        trigger.setAttribute('aria-expanded', 'false');
                        if (icon) icon.style.transform = 'rotate(0deg)';
                        openItem = null;
                    } else {
                        content.style.height = content.scrollHeight + 'px';
                        trigger.setAttribute('aria-expanded', 'true');
                        if (icon) icon.style.transform = 'rotate(180deg)';
                        openItem = index;
                    }
                });
            });
        });
    }

    /**
     * Tab Component
     */
    function initTabs() {
        const tabGroups = document.querySelectorAll('[role="tablist"]');

        tabGroups.forEach(function(tabList) {
            const tabs = tabList.querySelectorAll('[role="tab"]');
            const panels = [];

            tabs.forEach(function(tab) {
                const panelId = tab.getAttribute('aria-controls');
                const panel = document.getElementById(panelId);
                if (panel) panels.push(panel);
            });

            function selectTab(selectedTab) {
                tabs.forEach(function(tab, index) {
                    const isSelected = tab === selectedTab;
                    tab.setAttribute('aria-selected', String(isSelected));
                    tab.classList.toggle('active', isSelected);
                    tab.tabIndex = isSelected ? 0 : -1;

                    // Swap utility classes for active/inactive styling
                    if (isSelected) {
                        tab.classList.remove('bg-muted', 'text-muted-foreground', 'hover:bg-muted/80');
                        tab.classList.add('bg-primary', 'text-primary-foreground');
                    } else {
                        tab.classList.remove('bg-primary', 'text-primary-foreground');
                        tab.classList.add('bg-muted', 'text-muted-foreground', 'hover:bg-muted/80');
                    }

                    if (panels[index]) {
                        panels[index].hidden = !isSelected;
                        panels[index].style.display = isSelected ? '' : 'none';
                        panels[index].classList.toggle('active', isSelected);
                    }
                });
            }

            // Set initial state: ensure only the selected tab's panel is visible
            tabs.forEach(function(tab, index) {
                var isSelected = tab.getAttribute('aria-selected') === 'true';
                if (panels[index]) {
                    panels[index].style.display = isSelected ? '' : 'none';
                }
            });

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    selectTab(tab);
                });

                tab.addEventListener('keydown', function(e) {
                    let newTab = null;
                    const currentIndex = Array.from(tabs).indexOf(tab);

                    switch (e.key) {
                        case 'ArrowLeft':
                        case 'ArrowUp':
                            e.preventDefault();
                            newTab = tabs[(currentIndex - 1 + tabs.length) % tabs.length];
                            break;
                        case 'ArrowRight':
                        case 'ArrowDown':
                            e.preventDefault();
                            newTab = tabs[(currentIndex + 1) % tabs.length];
                            break;
                        case 'Home':
                            e.preventDefault();
                            newTab = tabs[0];
                            break;
                        case 'End':
                            e.preventDefault();
                            newTab = tabs[tabs.length - 1];
                            break;
                    }

                    if (newTab) {
                        selectTab(newTab);
                        newTab.focus();
                    }
                });
            });
        });
    }

    /**
     * Cached header height (avoids repeated .offsetHeight forced reflows)
     */
    var cachedHeaderHeight = 0;
    function getHeaderHeight() {
        if (!cachedHeaderHeight) {
            var header = document.querySelector('.site-header');
            cachedHeaderHeight = header ? header.offsetHeight : 80;
        }
        return cachedHeaderHeight;
    }
    // Invalidate cache on resize
    window.addEventListener('resize', function() { cachedHeaderHeight = 0; }, { passive: true });

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#' || targetId === '#main-content') return;

                const target = document.querySelector(targetId);
                if (!target) return;

                e.preventDefault();

                const headerHeight = getHeaderHeight();
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });

                // Update URL without jumping
                history.pushState(null, '', targetId);

                // Focus the target for accessibility
                target.setAttribute('tabindex', '-1');
                target.focus({ preventScroll: true });
            });
        });
    }

    /**
     * Animate Elements on Scroll
     * Animations start paused via CSS (animation-play-state: paused).
     * JS only sets 'running' when the element enters the viewport.
     */
    function initScrollAnimations() {
        var animatedElements = document.querySelectorAll('.animate-fade-in, .animate-fade-in-up, .animate-slide-in-left, .animate-slide-in-right, .animate-scale-in');

        if (!animatedElements.length || !('IntersectionObserver' in window)) return;

        // Check for reduced motion preference
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            var sheet = document.createElement('style');
            sheet.textContent = '.animate-fade-in,.animate-fade-in-up,.animate-slide-in-left,.animate-slide-in-right,.animate-scale-in{animation:none!important;opacity:1!important}';
            document.head.appendChild(sheet);
            return;
        }

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // No per-element style writes needed — CSS handles the paused state
        animatedElements.forEach(function(el) {
            observer.observe(el);
        });
    }

    /**
     * Lazy Load Images
     * Batches DOM writes to avoid forced reflow from per-image read-write cycles
     */
    function initLazyLoading() {
        var lazyImages = document.querySelectorAll('img[loading="lazy"]');
        if (!lazyImages.length) return;

        // Read phase: classify images by load state
        var pending = [];
        lazyImages.forEach(function(img) {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                pending.push(img);
            }
        });

        // Write phase: batch all style writes in one frame
        if (pending.length) {
            requestAnimationFrame(function() {
                pending.forEach(function(img) {
                    img.style.opacity = '0';
                    img.style.transition = 'opacity 300ms ease-out';

                    img.addEventListener('load', function() {
                        img.style.opacity = '1';
                        img.classList.add('loaded');
                    });
                });
            });
        }
    }

    /**
     * Focus Management for Accessibility
     */
    function initFocusManagement() {
        // Add visible focus ring for keyboard navigation
        let isKeyboard = false;

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                isKeyboard = true;
                document.body.classList.add('keyboard-nav');
            }
        });

        document.addEventListener('mousedown', function() {
            isKeyboard = false;
            document.body.classList.remove('keyboard-nav');
        });
    }

    /**
     * Table Scroll Indicator
     */
    function initTableScroll() {
        var tableContainers = document.querySelectorAll('.table-scroll-container');
        if (!tableContainers.length) return;

        tableContainers.forEach(function(container) {
            var ticking = false;

            function checkScroll() {
                var isScrolledRight = container.scrollLeft + container.clientWidth >= container.scrollWidth - 5;
                container.classList.toggle('scrolled-right', isScrolledRight);
                ticking = false;
            }

            container.addEventListener('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(checkScroll);
                    ticking = true;
                }
            }, { passive: true });

            // Defer initial check to avoid forced reflow during init
            requestAnimationFrame(checkScroll);
        });
    }

    /**
     * Floating CTA Button (for Pro page)
     */
    function initFloatingCTA() {
        const floatingBtn = document.querySelector('.floating-cta');
        if (!floatingBtn) return;

        let ticking = false;

        function updateFloatingBtn() {
            const scrollY = window.scrollY;
            const showAfter = 500;

            if (scrollY > showAfter) {
                floatingBtn.classList.remove('opacity-0', 'pointer-events-none');
                floatingBtn.classList.add('opacity-100');
            } else {
                floatingBtn.classList.add('opacity-0', 'pointer-events-none');
                floatingBtn.classList.remove('opacity-100');
            }

            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateFloatingBtn);
                ticking = true;
            }
        }, { passive: true });
    }

    /**
     * FAQ Hash Navigation
     * Opens FAQ items based on URL hash and updates hash when FAQ is toggled
     */
    function initFaqHashNavigation() {
        const faqItems = document.querySelectorAll('details[id^="faq-"], details[id^="pro-faq-"]');

        if (!faqItems.length) return;

        // Open FAQ from URL hash on page load
        function openFaqFromHash() {
            const hash = window.location.hash;
            if (!hash) return;

            const targetFaq = document.querySelector(hash);
            if (targetFaq && targetFaq.tagName === 'DETAILS') {
                // Close any other open FAQs in the same container
                const container = targetFaq.closest('[role="list"]');
                if (container) {
                    container.querySelectorAll('details[open]').forEach(function(item) {
                        if (item !== targetFaq) item.removeAttribute('open');
                    });
                }

                // Open target FAQ
                targetFaq.setAttribute('open', '');

                // Scroll to FAQ with offset for fixed header
                const headerHeight = getHeaderHeight();
                const targetPosition = targetFaq.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

                setTimeout(function() {
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Highlight the FAQ briefly
                    targetFaq.classList.add('ring-2', 'ring-primary', 'ring-offset-2');
                    setTimeout(function() {
                        targetFaq.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
                    }, 2000);
                }, 100);
            }
        }

        // Update URL hash when FAQ is toggled
        faqItems.forEach(function(faq) {
            faq.addEventListener('toggle', function() {
                if (this.open) {
                    // Update URL hash without scrolling
                    history.replaceState(null, '', '#' + this.id);
                }
            });
        });

        // Handle initial hash on page load
        openFaqFromHash();

        // Handle hash changes (e.g., from browser back/forward)
        window.addEventListener('hashchange', openFaqFromHash);
    }

    /**
     * Tab Hash Navigation
     * Activates tabs based on URL hash and updates hash when tabs are clicked
     */
    function initTabHashNavigation() {
        const tabLists = document.querySelectorAll('[role="tablist"]');

        if (!tabLists.length) return;

        tabLists.forEach(function(tabList) {
            const tabs = tabList.querySelectorAll('[role="tab"]');

            // Update URL hash when tab is clicked
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    if (this.id) {
                        history.replaceState(null, '', '#' + this.id);
                    }
                });
            });
        });

        // Activate tab from URL hash on page load
        function activateTabFromHash() {
            const hash = window.location.hash;
            if (!hash) return;

            const targetTab = document.querySelector(hash);
            if (targetTab && targetTab.getAttribute('role') === 'tab') {
                targetTab.click();

                // Scroll to tab with offset for fixed header
                const headerHeight = getHeaderHeight();
                const tabList = targetTab.closest('[role="tablist"]');
                if (tabList) {
                    const targetPosition = tabList.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                    setTimeout(function() {
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }, 100);
                }
            }
        }

        // Handle initial hash on page load
        activateTabFromHash();

        // Handle hash changes
        window.addEventListener('hashchange', activateTabFromHash);
    }

    /**
     * Copy to Clipboard
     */
    function initCopyButtons() {
        document.querySelectorAll('[data-copy]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const text = this.getAttribute('data-copy');

                navigator.clipboard.writeText(text).then(function() {
                    const originalText = btn.textContent;
                    btn.textContent = 'Copied!';
                    btn.classList.add('text-primary');

                    setTimeout(function() {
                        btn.textContent = originalText;
                        btn.classList.remove('text-primary');
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy:', err);
                });
            });
        });
    }

    /**
     * Initialize All Components
     * Critical init runs immediately; non-critical deferred to next frame
     * to avoid forced reflow from interleaved DOM read/write cycles
     */
    function init() {
        // Critical: header, navigation, menus (above-the-fold interaction)
        initHeaderScroll();
        initMobileMenu();
        initDropdowns();
        initTabs();
        initTabHashNavigation();

        // Deferred: below-the-fold interactions and visual enhancements
        requestAnimationFrame(function() {
            initAccordions();
            initSmoothScroll();
            initScrollAnimations();
            initLazyLoading();
            initFocusManagement();
            initTableScroll();
            initFloatingCTA();
            initCopyButtons();
            initFaqHashNavigation();
        });
    }

    // Initialize when DOM is ready
    domReady(init);

    // Expose utility functions globally if needed
    window.PDFViewerTheme = {
        init: init,
        initAccordions: initAccordions,
        initTabs: initTabs
    };

})();
