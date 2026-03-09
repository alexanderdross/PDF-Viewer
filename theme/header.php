<?php
/**
 * Theme Header
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php // Favicon and app icons - optimized sizes ?>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(PDFVIEWER_THEME_URI . '/assets/images/favicon-32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(PDFVIEWER_THEME_URI . '/assets/images/favicon-16.png'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(PDFVIEWER_THEME_URI . '/assets/images/apple-touch-icon.png'); ?>">
    <meta name="theme-color" content="#1a5a8a">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php // Skip link for keyboard navigation ?>
<?php pdfviewer_skip_link(); ?>

<header
    class="site-header fixed top-0 left-0 right-0 z-50 glass transition-all duration-300"
    role="banner"
>
    <nav
        class="container mx-auto flex items-center justify-between px-4 lg:px-8 py-4 transition-all duration-300"
        aria-label="<?php esc_attr_e('Main navigation', 'pdfviewer'); ?>"
    >
        <?php // Logo ?>
        <a href="<?php echo esc_url(home_url('/')); ?>"
           class="flex items-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-lg"
           aria-label="<?php esc_attr_e('PDF Embed & SEO Optimize - Home', 'pdfviewer'); ?>"
           title="<?php esc_attr_e('Go to PDF Embed & SEO Optimize homepage', 'pdfviewer'); ?>">
            <?php
            $base_uri = PDFVIEWER_THEME_URI . '/assets/images/';
            $srcset_webp = esc_url($base_uri . 'logo-40.webp') . ' 40w, ' .
                           esc_url($base_uri . 'logo-80.webp') . ' 80w, ' .
                           esc_url($base_uri . 'logo-120.webp') . ' 120w, ' .
                           esc_url($base_uri . 'logo-160.webp') . ' 160w';
            echo pdfviewer_simple_picture(array(
                'webp'        => $base_uri . 'logo-40.webp',
                'fallback'    => $base_uri . 'logo.png',
                'alt'         => __('PDF Embed & SEO Optimize logo', 'pdfviewer'),
                'width'       => 40,
                'height'      => 40,
                'class'       => 'rounded-lg transition-all duration-300',
                'lazy'        => false,
                'title'       => __('PDF Embed & SEO Optimize - Free WordPress & Drupal Plugin', 'pdfviewer'),
                'srcset_webp' => $srcset_webp,
                'sizes'       => '40px',
            ));
            ?>
            <div class="flex flex-col">
                <span class="font-bold text-sm sm:text-lg leading-tight"><?php esc_html_e('PDF Embed & SEO', 'pdfviewer'); ?><span class="hidden xl:inline"><?php esc_html_e(' Optimize', 'pdfviewer'); ?></span></span>
                <span class="text-xs text-muted-foreground leading-tight"><?php esc_html_e('for WordPress, Drupal & React', 'pdfviewer'); ?></span>
            </div>
        </a>

        <?php // Tablet Landscape Navigation (1024-1279px) ?>
        <nav class="hidden lg:flex xl:hidden items-center justify-center gap-4 h-10" aria-label="<?php esc_attr_e('Tablet navigation', 'pdfviewer'); ?>">
            <a href="<?php echo esc_url(home_url('/#features')); ?>"
               class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors flex items-center h-full"
               title="<?php esc_attr_e('Explore features', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('Navigate to Features section', 'pdfviewer'); ?>">
                <?php esc_html_e('Features', 'pdfviewer'); ?>
            </a>
            <a href="<?php echo esc_url(home_url('/examples/')); ?>"
               class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors flex items-center h-full"
               title="<?php esc_attr_e('View examples', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('View live PDF embedding examples', 'pdfviewer'); ?>">
                <?php esc_html_e('Examples', 'pdfviewer'); ?>
            </a>
            <a href="<?php echo esc_url(home_url('/documentation/')); ?>"
               class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors flex items-center h-full"
               title="<?php esc_attr_e('Documentation', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('Read plugin documentation', 'pdfviewer'); ?>">
                <?php esc_html_e('Docs', 'pdfviewer'); ?>
            </a>
            <a href="<?php echo esc_url(home_url('/changelog/')); ?>"
               class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors flex items-center h-full"
               title="<?php esc_attr_e('Version history', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('View version history and changelog', 'pdfviewer'); ?>">
                <?php esc_html_e('Changelog', 'pdfviewer'); ?>
            </a>
            <a href="<?php echo esc_url(home_url('/enterprise/')); ?>"
               class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors flex items-center h-full"
               title="<?php esc_attr_e('Enterprise plans for regulated industries', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('View Enterprise plans and pricing', 'pdfviewer'); ?>">
                <?php esc_html_e('Enterprise', 'pdfviewer'); ?>
            </a>
            <?php // Dropdowns container with consistent spacing ?>
            <div class="flex items-center gap-2 h-full">
            <?php // Platforms dropdown ?>
            <div class="relative flex items-center h-full" data-dropdown>
                <button
                    id="platforms-menu-btn"
                    data-dropdown-trigger
                    class="btn btn-outline btn-sm gap-2 cursor-pointer"
                    aria-expanded="false"
                    aria-controls="platforms-menu"
                    aria-label="<?php esc_attr_e('Platforms menu - WordPress, Drupal and React options', 'pdfviewer'); ?>">
                    <?php esc_html_e('Platforms', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('chevron-down', 12); ?>
                </button>
                <div
                    id="platforms-menu"
                    data-dropdown-menu
                    class="z-50"
                    style="display: none;"
                >
                    <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/')); ?>"
                       title="<?php esc_attr_e('WordPress PDF Viewer Plugin', 'pdfviewer'); ?>"
                       aria-label="<?php esc_attr_e('View WordPress PDF Viewer plugin documentation', 'pdfviewer'); ?>">
                        <?php pdfviewer_wordpress_icon(16); ?>
                        <?php esc_html_e('WordPress', 'pdfviewer'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/')); ?>"
                       title="<?php esc_attr_e('Drupal PDF Viewer Module', 'pdfviewer'); ?>"
                       aria-label="<?php esc_attr_e('View Drupal PDF Viewer module documentation', 'pdfviewer'); ?>">
                        <?php pdfviewer_drupal_icon(16); ?>
                        <?php esc_html_e('Drupal', 'pdfviewer'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/')); ?>"
                       title="<?php esc_attr_e('React / Next.js PDF Components', 'pdfviewer'); ?>"
                       aria-label="<?php esc_attr_e('View React and Next.js PDF viewer components', 'pdfviewer'); ?>">
                        <?php pdfviewer_react_icon(16); ?>
                        <?php esc_html_e('React / Next.js', 'pdfviewer'); ?>
                    </a>
                </div>
            </div>
            <?php // Download Dropdown ?>
            <div class="relative flex items-center h-full" data-dropdown>
                <button
                    id="download-menu-btn"
                    data-dropdown-trigger
                    class="btn btn-outline btn-sm gap-2 cursor-pointer"
                    aria-expanded="false"
                    aria-controls="download-menu"
                    aria-label="<?php esc_attr_e('Download plugin - WordPress, Drupal and React options', 'pdfviewer'); ?>">
                    <?php echo pdfviewer_icon('download', 14); ?>
                    <?php esc_html_e('Download', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('chevron-down', 12); ?>
                </button>
                <div
                    id="download-menu"
                    data-dropdown-menu
                    class="z-50 dropdown-align-right"
                    style="display: none;"
                >
                    <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/#download-wordpress')); ?>"
                       title="<?php esc_attr_e('Download WordPress Plugin', 'pdfviewer'); ?>"
                       aria-label="<?php esc_attr_e('Download free WordPress plugin', 'pdfviewer'); ?>">
                        <?php pdfviewer_wordpress_icon(16); ?>
                        <?php esc_html_e('WordPress Plugin', 'pdfviewer'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/#download-drupal')); ?>"
                       title="<?php esc_attr_e('Download Drupal Module', 'pdfviewer'); ?>"
                       aria-label="<?php esc_attr_e('Download free Drupal module', 'pdfviewer'); ?>">
                        <?php pdfviewer_drupal_icon(16); ?>
                        <?php esc_html_e('Drupal Module', 'pdfviewer'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/#download-react')); ?>"
                       title="<?php esc_attr_e('Download React / Next.js Component', 'pdfviewer'); ?>"
                       aria-label="<?php esc_attr_e('Download React / Next.js PDF viewer component', 'pdfviewer'); ?>">
                        <?php pdfviewer_react_icon(16); ?>
                        <?php esc_html_e('React Component', 'pdfviewer'); ?>
                    </a>
                </div>
            </div>
            </div><?php // End dropdowns container ?>
        </nav>

        <?php // Desktop Navigation (1280px+) ?>
        <nav class="hidden xl:flex items-center gap-6" aria-label="<?php esc_attr_e('Primary navigation', 'pdfviewer'); ?>">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'flex items-center gap-6',
                    'container'      => false,
                    'walker'         => new PDFViewer_Nav_Walker(),
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'fallback_cb'    => false,
                ));
            } else {
                // Default navigation
                ?>
                <ul class="flex items-center gap-6">
                    <li>
                        <a href="<?php echo esc_url(home_url('/#features')); ?>"
                           class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                           title="<?php esc_attr_e('Explore key features', 'pdfviewer'); ?>"
                           aria-label="<?php esc_attr_e('Navigate to Features section', 'pdfviewer'); ?>">
                            <?php esc_html_e('Features', 'pdfviewer'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/examples/')); ?>"
                           class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                           title="<?php esc_attr_e('View live examples', 'pdfviewer'); ?>"
                           aria-label="<?php esc_attr_e('View live PDF embedding examples', 'pdfviewer'); ?>">
                            <?php esc_html_e('Examples', 'pdfviewer'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/documentation/')); ?>"
                           class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                           title="<?php esc_attr_e('Read documentation', 'pdfviewer'); ?>"
                           aria-label="<?php esc_attr_e('Read plugin documentation', 'pdfviewer'); ?>">
                            <?php esc_html_e('Docs', 'pdfviewer'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/changelog/')); ?>"
                           class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                           title="<?php esc_attr_e('View version history', 'pdfviewer'); ?>"
                           aria-label="<?php esc_attr_e('View version history and changelog', 'pdfviewer'); ?>">
                            <?php esc_html_e('Changelog', 'pdfviewer'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/enterprise/')); ?>"
                           class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                           title="<?php esc_attr_e('Enterprise plans for regulated industries', 'pdfviewer'); ?>"
                           aria-label="<?php esc_attr_e('View Enterprise plans and pricing', 'pdfviewer'); ?>">
                            <?php esc_html_e('Enterprise', 'pdfviewer'); ?>
                        </a>
                    </li>
                    <?php // Dropdowns grouped together ?>
                    <li class="flex items-center gap-2">
                        <div class="relative" data-dropdown>
                            <button
                                id="desktop-platforms-btn"
                                data-dropdown-trigger
                                class="btn btn-outline btn-sm gap-2 cursor-pointer"
                                aria-expanded="false"
                                aria-controls="desktop-platforms-menu"
                                aria-label="<?php esc_attr_e('Platforms menu - WordPress, Drupal and React options', 'pdfviewer'); ?>"
                                title="<?php esc_attr_e('Platform options', 'pdfviewer'); ?>">
                                <?php esc_html_e('Platforms', 'pdfviewer'); ?>
                                <?php echo pdfviewer_icon('chevron-down', 12); ?>
                            </button>
                            <div
                                id="desktop-platforms-menu"
                                data-dropdown-menu
                                class="z-50"
                                style="display: none;"
                            >
                                <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/')); ?>"
                                   title="<?php esc_attr_e('WordPress PDF Viewer Plugin', 'pdfviewer'); ?>"
                                   aria-label="<?php esc_attr_e('View WordPress PDF Viewer plugin documentation', 'pdfviewer'); ?>">
                                    <?php pdfviewer_wordpress_icon(16); ?>
                                    <?php esc_html_e('WordPress', 'pdfviewer'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/')); ?>"
                                   title="<?php esc_attr_e('Drupal PDF Viewer Module', 'pdfviewer'); ?>"
                                   aria-label="<?php esc_attr_e('View Drupal PDF Viewer module documentation', 'pdfviewer'); ?>">
                                    <?php pdfviewer_drupal_icon(16); ?>
                                    <?php esc_html_e('Drupal', 'pdfviewer'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/')); ?>"
                                   title="<?php esc_attr_e('React / Next.js PDF Components', 'pdfviewer'); ?>"
                                   aria-label="<?php esc_attr_e('View React and Next.js PDF viewer components', 'pdfviewer'); ?>">
                                    <?php pdfviewer_react_icon(16); ?>
                                    <?php esc_html_e('React / Next.js', 'pdfviewer'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="relative" data-dropdown>
                            <button
                                id="desktop-download-nav-btn"
                                data-dropdown-trigger
                                class="btn btn-outline btn-sm gap-2 cursor-pointer"
                                aria-expanded="false"
                                aria-controls="desktop-download-nav-menu"
                                aria-label="<?php esc_attr_e('Download free plugin menu', 'pdfviewer'); ?>"
                                title="<?php esc_attr_e('Download free plugin', 'pdfviewer'); ?>">
                                <?php esc_html_e('Download Free', 'pdfviewer'); ?>
                                <?php echo pdfviewer_icon('chevron-down', 12); ?>
                            </button>
                            <div
                                id="desktop-download-nav-menu"
                                data-dropdown-menu
                                class="z-50"
                                style="display: none;"
                            >
                                <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/#download-wordpress')); ?>"
                                   title="<?php esc_attr_e('Download WordPress Plugin', 'pdfviewer'); ?>"
                                   aria-label="<?php esc_attr_e('Download free WordPress plugin', 'pdfviewer'); ?>">
                                    <?php pdfviewer_wordpress_icon(16); ?>
                                    <?php esc_html_e('WordPress Plugin', 'pdfviewer'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/#download-drupal')); ?>"
                                   title="<?php esc_attr_e('Download Drupal Module', 'pdfviewer'); ?>"
                                   aria-label="<?php esc_attr_e('Download free Drupal module', 'pdfviewer'); ?>">
                                    <?php pdfviewer_drupal_icon(16); ?>
                                    <?php esc_html_e('Drupal Module', 'pdfviewer'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/#download-react')); ?>"
                                   title="<?php esc_attr_e('Download React / Next.js Component', 'pdfviewer'); ?>"
                                   aria-label="<?php esc_attr_e('Download React / Next.js PDF viewer component', 'pdfviewer'); ?>">
                                    <?php pdfviewer_react_icon(16); ?>
                                    <?php esc_html_e('React Component', 'pdfviewer'); ?>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
                <?php
            }
            ?>
        </nav>

        <?php // Tablet Landscape Actions (1024-1279px) ?>
        <div class="hidden lg:flex xl:hidden items-center gap-2 h-10">
            <?php // Cart Button ?>
            <a href="<?php echo esc_url(home_url('/cart/')); ?>"
               class="btn btn-ghost btn-icon h-10 w-10"
               title="<?php esc_attr_e('View cart', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('View shopping cart', 'pdfviewer'); ?>">
                <?php echo pdfviewer_icon('shopping-cart', 20); ?>
            </a>

            <?php // Pro Button ?>
            <a href="<?php echo esc_url(home_url('/pro/')); ?>"
               class="btn btn-sm gradient-hero shadow-glow gap-1 text-white h-9 flex items-center"
               title="<?php esc_attr_e('Get Pro version', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('Upgrade to Pro version with advanced features', 'pdfviewer'); ?>">
                <?php echo pdfviewer_icon('zap', 14); ?>
                <?php esc_html_e('Pro', 'pdfviewer'); ?>
            </a>
        </div>

        <?php // Desktop Actions (1280px+) ?>
        <div class="hidden xl:flex items-center gap-3">
            <?php // Cart Button ?>
            <a href="<?php echo esc_url(home_url('/cart/')); ?>"
               class="btn btn-ghost btn-icon h-10 w-10"
               title="<?php esc_attr_e('View cart', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('View shopping cart', 'pdfviewer'); ?>">
                <?php echo pdfviewer_icon('shopping-cart', 22); ?>
            </a>

            <?php // Get Pro Button ?>
            <a href="<?php echo esc_url(home_url('/pro/')); ?>"
               class="btn btn-md gradient-hero shadow-glow gap-2 text-white"
               title="<?php esc_attr_e('Get Pro version with advanced features', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('Get Pro version', 'pdfviewer'); ?>">
                <?php echo pdfviewer_icon('zap', 16); ?>
                <?php esc_html_e('Get Pro', 'pdfviewer'); ?>
            </a>
        </div>

        <?php // Mobile Actions (below 1024px - mobile and tablet portrait) ?>
        <div class="lg:hidden flex items-center gap-2">
            <?php // Cart Button ?>
            <a href="<?php echo esc_url(home_url('/cart/')); ?>"
               class="p-2 text-foreground hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-md"
               title="<?php esc_attr_e('View cart', 'pdfviewer'); ?>"
               aria-label="<?php esc_attr_e('View shopping cart', 'pdfviewer'); ?>">
                <?php echo pdfviewer_icon('shopping-cart', 20); ?>
            </a>

            <?php // Mobile Menu Button ?>
            <button
                class="p-2 text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-md"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="<?php esc_attr_e('Open navigation menu', 'pdfviewer'); ?>"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg style="display:none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </nav>

    <?php // Mobile Menu (below 1024px - mobile and tablet portrait) ?>
    <div
        id="mobile-menu"
        class="lg:hidden max-h-[calc(100vh-4rem)] overflow-y-auto overscroll-contain"
        style="display:none; -webkit-overflow-scrolling: touch;"
    >
        <nav class="bg-card border-t border-border px-4 py-6 space-y-4 pb-8" aria-label="<?php esc_attr_e('Mobile navigation', 'pdfviewer'); ?>"><?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'space-y-4',
                    'container'      => false,
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'fallback_cb'    => false,
                ));
            } else {
                ?>
                <ul class="space-y-4">
                    <li><a href="<?php echo esc_url(home_url('/#features')); ?>" class="block py-2 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('Explore features', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('Navigate to Features section', 'pdfviewer'); ?>"><?php esc_html_e('Features', 'pdfviewer'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/examples/')); ?>" class="block py-2 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('View examples', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('View live PDF embedding examples', 'pdfviewer'); ?>"><?php esc_html_e('Examples', 'pdfviewer'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/documentation/')); ?>" class="block py-2 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('Documentation', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('Read plugin documentation', 'pdfviewer'); ?>"><?php esc_html_e('Docs', 'pdfviewer'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/changelog/')); ?>" class="block py-2 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('Version history', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('View version history and changelog', 'pdfviewer'); ?>"><?php esc_html_e('Changelog', 'pdfviewer'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/enterprise/')); ?>" class="block py-2 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('Enterprise plans', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('View Enterprise plans and pricing', 'pdfviewer'); ?>"><?php esc_html_e('Enterprise', 'pdfviewer'); ?></a></li>
                    <li class="pt-2 border-t border-border">
                        <span class="text-sm text-muted-foreground font-medium" id="mobile-platforms-label"><?php esc_html_e('Platforms', 'pdfviewer'); ?></span>
                        <ul class="mt-2 space-y-2 pl-2" aria-labelledby="mobile-platforms-label">
                            <li><a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/')); ?>" class="flex items-center gap-2 py-1 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('WordPress PDF Viewer Plugin', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('View WordPress PDF Viewer plugin documentation', 'pdfviewer'); ?>"><?php pdfviewer_wordpress_icon(14); ?><?php esc_html_e('WordPress', 'pdfviewer'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/')); ?>" class="flex items-center gap-2 py-1 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('Drupal PDF Viewer Module', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('View Drupal PDF Viewer module documentation', 'pdfviewer'); ?>"><?php pdfviewer_drupal_icon(14); ?><?php esc_html_e('Drupal', 'pdfviewer'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/')); ?>" class="flex items-center gap-2 py-1 text-foreground hover:text-primary transition-colors" title="<?php esc_attr_e('React / Next.js PDF Components', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('View React and Next.js PDF viewer components', 'pdfviewer'); ?>"><?php pdfviewer_react_icon(14); ?><?php esc_html_e('React / Next.js', 'pdfviewer'); ?></a></li>
                        </ul>
                    </li>
                </ul>
                <?php
            }
            ?>

            <?php // Mobile CTA ?>
            <div class="pt-4 border-t border-border space-y-3">
                <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/#download-wordpress')); ?>"
                   class="btn btn-outline btn-lg w-full gap-2"
                   title="<?php esc_attr_e('Download for WordPress', 'pdfviewer'); ?>"
                   aria-label="<?php esc_attr_e('Download free WordPress plugin', 'pdfviewer'); ?>">
                    <?php pdfviewer_wordpress_icon(20); ?>
                    <?php esc_html_e('Download for WordPress', 'pdfviewer'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/#download-drupal')); ?>"
                   class="btn btn-outline btn-lg w-full gap-2"
                   title="<?php esc_attr_e('Get Drupal Module', 'pdfviewer'); ?>"
                   aria-label="<?php esc_attr_e('Download free Drupal module', 'pdfviewer'); ?>">
                    <?php pdfviewer_drupal_icon(20); ?>
                    <?php esc_html_e('Get Drupal Module', 'pdfviewer'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/#download-react')); ?>"
                   class="btn btn-outline btn-lg w-full gap-2"
                   title="<?php esc_attr_e('Get React Component', 'pdfviewer'); ?>"
                   aria-label="<?php esc_attr_e('Download React / Next.js PDF viewer component', 'pdfviewer'); ?>">
                    <?php pdfviewer_react_icon(20); ?>
                    <?php esc_html_e('Get React Component', 'pdfviewer'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/pro/')); ?>"
                   class="btn btn-lg w-full gap-2 gradient-hero shadow-glow text-white"
                   title="<?php esc_attr_e('PDF Embed Pro - Advanced analytics, password protection, and more', 'pdfviewer'); ?>"
                   aria-label="<?php esc_attr_e('Upgrade to PDF Embed Pro for advanced features', 'pdfviewer'); ?>">
                    <?php echo pdfviewer_icon('zap', 20); ?>
                    <?php esc_html_e('Get Pro', 'pdfviewer'); ?>
                </a>
            </div>
        </nav>
    </div>
</header>

<?php // Spacer for fixed header - responsive heights for all breakpoints ?>
<div class="h-[72px] md:h-[76px] lg:h-[80px]" aria-hidden="true"></div>

<?php // Breadcrumb bar (matches React VisualBreadcrumbs in Layout.tsx) ?>
<?php echo pdfviewer_breadcrumb(); ?>

<main id="main-content" class="site-main" role="main">
