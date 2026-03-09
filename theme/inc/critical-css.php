<?php
/**
 * Critical CSS for Above-the-Fold Content
 * Inlines critical CSS to speed up initial render
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate Critical CSS
 * Inlines minimal CSS needed for above-the-fold content
 */
function pdfviewer_critical_css() {
    ?>
    <style id="pdfviewer-critical-css">
        /* Critical CSS - Above the fold styles for fast first paint */

        /* CSS Reset - Critical */
        *,*::before,*::after{box-sizing:border-box}
        body{margin:0;font-family:'Inter',system-ui,-apple-system,sans-serif;line-height:1.6;-webkit-font-smoothing:antialiased}

        /* CSS Variables - Core */
        :root{
            --color-primary:#1a5a8a;
            --color-primary-foreground:#ffffff;
            --color-accent:#c55a11;
            --color-background:#f0f2f5;
            --color-foreground:#1a1d24;
            --color-card:#ffffff;
            --color-muted:#e8eaed;
            --color-muted-foreground:#5c6370;
            --color-border:rgba(0,0,0,0.1);
            --font-heading:'Outfit',system-ui,-apple-system,sans-serif;
            --shadow-soft:0 2px 8px rgba(0,0,0,0.08);
        }

        /* Layout - Critical */
        .container{width:100%;max-width:1400px;margin:0 auto;padding-left:1rem;padding-right:1rem}

        /* Header - Critical (Always visible) */
        .header{position:fixed;top:0;left:0;right:0;z-index:50;background:rgba(255,255,255,0.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--color-border);transition:all 0.3s ease}
        .header-scrolled{box-shadow:var(--shadow-soft)}
        .header-inner{display:flex;align-items:center;justify-content:space-between;height:4.5rem;padding:0 1rem}

        /* Logo - Critical */
        .header-logo{display:flex;align-items:center;gap:0.75rem;text-decoration:none;color:var(--color-foreground)}
        .header-logo-icon{width:2.5rem;height:2.5rem;display:flex;align-items:center;justify-content:center;border-radius:0.5rem}
        .header-logo-text{font-family:var(--font-heading);font-weight:700;font-size:1.25rem}

        /* Navigation - Critical */
        .nav-desktop{display:none}
        @media(min-width:1024px){.nav-desktop{display:flex;align-items:center;gap:0.5rem}}
        .nav-link{padding:0.5rem 1rem;font-size:0.875rem;font-weight:500;color:var(--color-muted-foreground);text-decoration:none;border-radius:0.375rem;transition:all 0.2s}
        .nav-link:hover{color:var(--color-foreground);background:var(--color-muted)}

        /* Mobile Menu Button - Critical */
        .mobile-menu-toggle{display:flex;align-items:center;justify-content:center;width:2.5rem;height:2.5rem;border:none;background:transparent;cursor:pointer;border-radius:0.375rem}
        @media(min-width:1024px){.mobile-menu-toggle{display:none}}

        /* Buttons - Critical */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.625rem 1.25rem;font-size:0.875rem;font-weight:500;border-radius:0.5rem;cursor:pointer;text-decoration:none;transition:all 0.2s;border:none}
        .btn-primary{background:var(--color-primary);color:var(--color-primary-foreground)}
        .btn-outline{background:transparent;border:1px solid var(--color-border);color:var(--color-foreground)}
        .btn-sm{padding:0.375rem 0.75rem;font-size:0.75rem}

        /* Hero Section - Critical */
        .hero-section{position:relative;padding:6rem 0 4rem;overflow:hidden}
        @media(min-width:1024px){.hero-section{padding:8rem 0 6rem}}

        /* Skip Link - Accessibility */
        .skip-link{position:absolute;top:-40px;left:0;background:var(--color-primary);color:var(--color-primary-foreground);padding:0.5rem 1rem;z-index:100;transition:top 0.3s}
        .skip-link:focus{top:0}

        /* Typography - Critical */
        h1,h2,h3,h4,h5,h6{font-family:var(--font-heading);font-weight:700;line-height:1.2;margin:0}
        .text-center{text-align:center}
        .text-4xl{font-size:2.25rem}
        .text-5xl{font-size:3rem}
        @media(min-width:768px){.text-5xl{font-size:3rem}}
        @media(min-width:1024px){.text-6xl{font-size:3.75rem}}

        /* Spacing - Critical */
        .mx-auto{margin-left:auto;margin-right:auto}
        .mb-4{margin-bottom:1rem}
        .mb-6{margin-bottom:1.5rem}
        .mb-8{margin-bottom:2rem}
        .py-4{padding-top:1rem;padding-bottom:1rem}
        .px-4{padding-left:1rem;padding-right:1rem}

        /* Flex - Critical */
        .flex{display:flex}
        .flex-col{flex-direction:column}
        .items-center{align-items:center}
        .justify-center{justify-content:center}
        .gap-2{gap:0.5rem}
        .gap-4{gap:1rem}

        /* Colors - Critical */
        .text-primary{color:var(--color-primary)}
        .text-muted-foreground{color:var(--color-muted-foreground)}
        .bg-card{background-color:var(--color-card)}

        /* Gradients - Critical */
        .gradient-hero{background:linear-gradient(135deg,hsl(200 75% 30%) 0%,hsl(200 75% 40%) 100%)}
        .text-gradient{background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-accent) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}

        /* Animation - Critical (reduced motion support) */
        @media(prefers-reduced-motion:reduce){*{animation-duration:0.01ms!important;animation-iteration-count:1!important;transition-duration:0.01ms!important}}

        /* Font Loading */
        .fonts-loading body{font-family:system-ui,-apple-system,sans-serif}
        .fonts-loaded body{font-family:'Inter',system-ui,-apple-system,sans-serif}
    </style>
    <?php
}
add_action('wp_head', 'pdfviewer_critical_css', 1);

/**
 * Add Preload for Critical Resources
 */
function pdfviewer_preload_critical_resources() {
    $theme_uri = PDFVIEWER_THEME_URI;
    ?>
    <!-- Preload critical fonts -->
    <link rel="preload" href="<?php echo esc_url($theme_uri); ?>/assets/fonts/inter/inter-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo esc_url($theme_uri); ?>/assets/fonts/outfit/outfit-bold.woff2" as="font" type="font/woff2" crossorigin>
    <!-- Preload main stylesheet -->
    <link rel="preload" href="<?php echo esc_url($theme_uri); ?>/assets/css/main.css?ver=<?php echo PDFVIEWER_THEME_VERSION; ?>" as="style">
    <?php
}
add_action('wp_head', 'pdfviewer_preload_critical_resources', 0);

/**
 * Add Font Loading Detection
 */
function pdfviewer_font_loading_script() {
    ?>
    <script>
    (function(){
        if('fonts'in document){
            document.documentElement.classList.add('fonts-loading');
            Promise.all([
                document.fonts.load('400 1em Inter'),
                document.fonts.load('700 1em Outfit')
            ]).then(function(){
                document.documentElement.classList.remove('fonts-loading');
                document.documentElement.classList.add('fonts-loaded');
            }).catch(function(){
                document.documentElement.classList.remove('fonts-loading');
            });
        }
    })();
    </script>
    <?php
}
add_action('wp_head', 'pdfviewer_font_loading_script', 2);

/**
 * Add No-JS Class Handling
 */
function pdfviewer_nojs_class() {
    ?>
    <script>document.documentElement.classList.remove('no-js');document.documentElement.classList.add('js');</script>
    <?php
}
add_action('wp_head', 'pdfviewer_nojs_class', 0);
