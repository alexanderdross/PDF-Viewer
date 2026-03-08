<?php
/**
 * Template Tags for PDF Viewer Theme
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Simple Picture Element
 * For single images with WebP and fallback
 */
function pdfviewer_simple_picture($args) {
    $defaults = array(
        'webp'        => '',
        'fallback'    => '',
        'alt'         => '',
        'width'       => 0,
        'height'      => 0,
        'class'       => '',
        'lazy'        => true,
        'title'       => '',
        'aria_label'  => '',
        'srcset_webp' => '', // srcset for webp: 'image-1x.webp 1x, image-2x.webp 2x'
        'srcset'      => '', // srcset for fallback
        'sizes'       => '', // sizes attribute
    );

    $args = wp_parse_args($args, $defaults);

    $loading = $args['lazy'] ? 'lazy' : 'eager';
    $decoding = $args['lazy'] ? 'async' : 'sync';
    $fetchpriority = $args['lazy'] ? '' : ' fetchpriority="high"';

    $html = '<picture>';

    // WebP source with optional srcset
    if ($args['webp']) {
        $webp_srcset = $args['srcset_webp'] ? $args['srcset_webp'] : esc_url($args['webp']);
        $html .= '<source type="image/webp" srcset="' . $webp_srcset . '"';
        if ($args['sizes']) {
            $html .= ' sizes="' . esc_attr($args['sizes']) . '"';
        }
        $html .= '>';
    }

    $img_attrs = array(
        'src="' . esc_url($args['fallback']) . '"',
        'alt="' . esc_attr($args['alt']) . '"',
        'loading="' . $loading . '"',
        'decoding="' . $decoding . '"',
    );

    // Add srcset for fallback image
    if ($args['srcset']) {
        $img_attrs[] = 'srcset="' . $args['srcset'] . '"';
    }

    // Add sizes attribute
    if ($args['sizes']) {
        $img_attrs[] = 'sizes="' . esc_attr($args['sizes']) . '"';
    }

    if ($args['width'] > 0) {
        $img_attrs[] = 'width="' . intval($args['width']) . '"';
    }

    if ($args['height'] > 0) {
        $img_attrs[] = 'height="' . intval($args['height']) . '"';
    }

    if ($args['class']) {
        $img_attrs[] = 'class="' . esc_attr($args['class']) . '"';
    }

    if ($args['title']) {
        $img_attrs[] = 'title="' . esc_attr($args['title']) . '"';
    }

    if ($args['aria_label']) {
        $img_attrs[] = 'aria-label="' . esc_attr($args['aria_label']) . '"';
    }

    $html .= '<img ' . implode(' ', $img_attrs) . $fetchpriority . '>';
    $html .= '</picture>';

    return $html;
}

/**
 * Get Logo with Picture Element and Responsive srcset
 */
function pdfviewer_get_logo($lazy = true) {
    $base_uri = PDFVIEWER_THEME_URI . '/assets/images/';

    // Responsive WebP srcset for different device pixel ratios
    $srcset_webp = esc_url($base_uri . 'logo-40.webp') . ' 40w, ' .
                   esc_url($base_uri . 'logo-80.webp') . ' 80w, ' .
                   esc_url($base_uri . 'logo-120.webp') . ' 120w, ' .
                   esc_url($base_uri . 'logo-160.webp') . ' 160w';

    return pdfviewer_simple_picture(array(
        'webp'        => $base_uri . 'logo-40.webp',
        'fallback'    => $base_uri . 'logo.png',
        'alt'         => __('PDF Embed & SEO Optimize logo', 'pdfviewer'),
        'width'       => 40,
        'height'      => 40,
        'class'       => 'logo-img rounded-lg',
        'lazy'        => $lazy,
        'title'       => __('PDF Embed & SEO Optimize - Free WordPress & Drupal Plugin', 'pdfviewer'),
        'aria_label'  => __('PDF Embed & SEO Optimize logo', 'pdfviewer'),
        'srcset_webp' => $srcset_webp,
        'sizes'       => '40px',
    ));
}

/**
 * Navigation Menu Walker for Accessible Navigation
 */
class PDFViewer_Nav_Walker extends Walker_Nav_Menu {
    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ($args->walker->has_children) {
            $classes[] = 'has-submenu';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id_attr = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id_attr = $id_attr ? ' id="' . esc_attr($id_attr) . '"' : '';

        $output .= $indent . '<li' . $id_attr . $class_names . '>';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';

        // Add aria attributes for current page
        if (in_array('current-menu-item', $classes)) {
            $atts['aria-current'] = 'page';
        }

        // Add external link attributes
        if ($atts['target'] === '_blank') {
            $atts['rel'] = 'noopener';
            $atts['aria-label'] = $item->title . ' ' . __('(opens in new tab)', 'pdfviewer');
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' class="text-muted-foreground hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm">';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Display Skip Link
 */
function pdfviewer_skip_link() {
    ?>
    <a href="#main-content"
       class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-primary focus:text-primary-foreground focus:rounded-lg focus:shadow-lg focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:font-semibold focus:outline-none transition-all"
       aria-label="<?php esc_attr_e('Skip navigation and go directly to main content', 'pdfviewer'); ?>">
        <?php esc_html_e('Skip to main content', 'pdfviewer'); ?>
    </a>
    <?php
}

/**
 * Display Site Logo with Link
 */
function pdfviewer_site_logo($is_scrolled = false) {
    $logo_class = $is_scrolled ? 'w-8 h-8' : 'w-10 h-10';
    ?>
    <a href="<?php echo esc_url(home_url('/')); ?>"
       class="flex items-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-lg"
       aria-label="<?php esc_attr_e('PDF Embed & SEO Optimize - Home', 'pdfviewer'); ?>"
       title="<?php esc_attr_e('Go to PDF Embed & SEO Optimize homepage', 'pdfviewer'); ?>">
        <?php echo pdfviewer_get_logo(false); ?>
        <div class="flex flex-col">
            <span class="font-bold text-lg leading-tight hidden sm:block"><?php esc_html_e('PDF Embed & SEO Optimize', 'pdfviewer'); ?></span>
            <span class="text-xs text-muted-foreground leading-tight hidden sm:block"><?php esc_html_e('for WordPress & Drupal', 'pdfviewer'); ?></span>
        </div>
    </a>
    <?php
}

/**
 * Display Primary Navigation
 */
function pdfviewer_primary_nav() {
    if (has_nav_menu('primary')) {
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class'     => 'flex items-center gap-6',
            'container'      => 'nav',
            'container_class' => 'hidden lg:flex items-center',
            'container_aria_label' => __('Main navigation', 'pdfviewer'),
            'walker'         => new PDFViewer_Nav_Walker(),
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ));
    }
}

/**
 * Display Footer Navigation
 */
function pdfviewer_footer_nav($location = 'footer') {
    if (has_nav_menu($location)) {
        wp_nav_menu(array(
            'theme_location' => $location,
            'menu_class'     => 'space-y-3',
            'container'      => false,
            'walker'         => new PDFViewer_Nav_Walker(),
            'items_wrap'     => '<ul id="%1$s" class="%2$s" role="list">%3$s</ul>',
            'fallback_cb'    => false,
        ));
    }
}

/**
 * Display WordPress Icon SVG (Official Logo)
 */
function pdfviewer_wordpress_icon($size = 14) {
    ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo intval($size); ?>" height="<?php echo intval($size); ?>" viewBox="0 0 122.52 122.523" fill="currentColor" aria-hidden="true">
        <path d="M8.708 61.26c0 20.802 12.089 38.779 29.619 47.298L13.258 39.872a52.354 52.354 0 00-4.55 21.388zm88.032-2.652c0-6.495-2.333-10.993-4.334-14.494-2.664-4.329-5.161-7.995-5.161-12.324 0-4.831 3.664-9.328 8.825-9.328.233 0 .454.029.681.042-9.35-8.566-21.807-13.796-35.489-13.796-18.36 0-34.513 9.42-43.91 23.688 1.233.037 2.395.063 3.382.063 5.497 0 14.006-.667 14.006-.667 2.833-.167 3.167 3.994.337 4.329 0 0-2.847.335-6.015.501L48.2 93.547l11.501-34.493-8.188-22.434c-2.83-.166-5.511-.501-5.511-.501-2.832-.166-2.5-4.496.332-4.329 0 0 8.679.667 13.843.667 5.496 0 14.006-.667 14.006-.667 2.835-.167 3.168 3.994.337 4.329 0 0-2.853.335-6.015.501l18.992 56.494 5.242-17.517c2.272-7.269 4.001-12.49 4.001-16.989zm-35.63 7.474l-15.768 45.819c4.708 1.384 9.687 2.141 14.846 2.141 6.12 0 11.989-1.058 17.452-2.979a1.463 1.463 0 01-.118-.228l-16.412-44.753zM106.35 36.29c.226 1.674.354 3.471.354 5.404 0 5.333-.996 11.328-3.996 18.824l-16.053 46.413c15.624-9.111 26.133-26.038 26.133-45.426.001-9.137-2.333-17.729-6.438-25.215zM61.262 0C27.483 0 0 27.481 0 61.26c0 33.783 27.483 61.263 61.262 61.263 33.778 0 61.265-27.48 61.265-61.263C122.526 27.481 95.04 0 61.262 0zm0 119.715c-32.23 0-58.453-26.223-58.453-58.455 0-32.23 26.222-58.451 58.453-58.451 32.229 0 58.45 26.221 58.45 58.451 0 32.232-26.221 58.455-58.45 58.455z"/>
    </svg>
    <?php
}

/**
 * Display Drupal Icon SVG (Official Druplicon)
 */
function pdfviewer_drupal_icon($size = 14) {
    ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo intval($size); ?>" height="<?php echo intval($size); ?>" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M15.78 5.113C14.09 3.425 12.48 1.815 11.998 0c-.48 1.815-2.09 3.425-3.778 5.113-2.534 2.53-5.405 5.4-5.405 9.702a9.184 9.185 0 1018.368 0c0-4.303-2.871-7.171-5.405-9.702M6.72 16.954a1.022 1.022 0 01-1.448.006 1.024 1.024 0 01-.007-1.447c1.236-1.239 1.832-2.058 2.29-3.338.063-.176.127-.353.194-.531l.135-.378.112.39c.085.298.18.6.314.903.192.435.443.862.723 1.273.396.58.87 1.123 1.38 1.623a.96.96 0 01.276.678.963.963 0 01-.282.679.963.963 0 01-.681.282.96.96 0 01-.678-.276c-.703-.703-1.3-1.476-1.78-2.302-.185.467-.406.916-.675 1.379-.453.78-.96 1.507-1.873 2.059m5.639 2.748c-.436.16-.912.249-1.418.266l-.158.004c-.507-.017-.982-.106-1.418-.266-.91-.333-1.538-.913-1.886-1.735a.966.966 0 01.478-1.278.96.96 0 011.278.477c.166.39.44.651.865.82.331.132.693.187 1.063.187.37 0 .732-.055 1.063-.187.425-.169.699-.43.865-.82a.967.967 0 011.278-.477c.494.227.71.784.478 1.278-.348.822-.977 1.402-1.886 1.735m4.282-2.758c-.913-.552-1.42-1.279-1.873-2.059-.269-.463-.49-.912-.675-1.379-.48.826-1.077 1.599-1.78 2.302a.96.96 0 01-.678.276.963.963 0 01-.681-.282.963.963 0 01-.282-.679.96.96 0 01.276-.678c.51-.5.984-1.043 1.38-1.623.28-.411.531-.838.723-1.273.134-.303.23-.605.314-.903l.112-.39.135.378c.067.178.131.355.194.531.458 1.28 1.054 2.1 2.29 3.338a1.024 1.024 0 01-.007 1.447 1.022 1.022 0 01-1.448-.006"/>
    </svg>
    <?php
}

/**
 * Display React Icon SVG
 */
function pdfviewer_react_icon($size = 14) {
    ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo intval($size); ?>" height="<?php echo intval($size); ?>" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <circle cx="12" cy="12" r="2.139"/>
        <path d="M12 9.861c-3.16 0-6.178.526-8.49 1.482-1.156.478-2.122 1.053-2.816 1.728-.722.702-1.194 1.528-1.194 2.429s.472 1.727 1.194 2.429c.694.675 1.66 1.25 2.816 1.728 2.312.956 5.33 1.482 8.49 1.482s6.178-.526 8.49-1.482c1.156-.478 2.122-1.053 2.816-1.728.722-.702 1.194-1.528 1.194-2.429s-.472-1.727-1.194-2.429c-.694-.675-1.66-1.25-2.816-1.728-2.312-.956-5.33-1.482-8.49-1.482zm0 1.5c2.989 0 5.817.493 7.911 1.359.994.411 1.772.904 2.293 1.41.493.48.796 1.008.796 1.37 0 .362-.303.89-.796 1.37-.521.506-1.299.999-2.293 1.41-2.094.866-4.922 1.359-7.911 1.359s-5.817-.493-7.911-1.359c-.994-.411-1.772-.904-2.293-1.41-.493-.48-.796-1.008-.796-1.37 0-.362.303-.89.796-1.37.521-.506 1.299-.999 2.293-1.41 2.094-.866 4.922-1.359 7.911-1.359z"/>
        <path d="M8.68 3.808c-1.58.913-2.164 2.09-2.164 3.153 0 1.096.626 2.364 1.782 3.694.578.665 1.265 1.335 2.044 1.988.649.545 1.359 1.076 2.122 1.58a51.252 51.252 0 002.122-1.58c.779-.653 1.466-1.323 2.044-1.988 1.156-1.33 1.782-2.598 1.782-3.694 0-1.063-.584-2.24-2.164-3.153-1.494-.863-3.636-1.308-5.784-1.308s-4.29.445-5.784 1.308zm.75 1.299c1.274-.736 3.136-1.107 5.034-1.107s3.76.371 5.034 1.107c1.166.673 1.73 1.434 1.73 2.054 0 .687-.389 1.613-1.391 2.765a16.88 16.88 0 01-1.869 1.822c-.563.472-1.18.943-1.84 1.398a28.2 28.2 0 01-1.664-1.398c-.696-.583-1.327-1.18-1.869-1.822-1.002-1.152-1.391-2.078-1.391-2.765 0-.62.564-1.381 1.73-2.054z" transform="rotate(60 12 12)"/>
        <path d="M8.68 3.808c-1.58.913-2.164 2.09-2.164 3.153 0 1.096.626 2.364 1.782 3.694.578.665 1.265 1.335 2.044 1.988.649.545 1.359 1.076 2.122 1.58a51.252 51.252 0 002.122-1.58c.779-.653 1.466-1.323 2.044-1.988 1.156-1.33 1.782-2.598 1.782-3.694 0-1.063-.584-2.24-2.164-3.153-1.494-.863-3.636-1.308-5.784-1.308s-4.29.445-5.784 1.308zm.75 1.299c1.274-.736 3.136-1.107 5.034-1.107s3.76.371 5.034 1.107c1.166.673 1.73 1.434 1.73 2.054 0 .687-.389 1.613-1.391 2.765a16.88 16.88 0 01-1.869 1.822c-.563.472-1.18.943-1.84 1.398a28.2 28.2 0 01-1.664-1.398c-.696-.583-1.327-1.18-1.869-1.822-1.002-1.152-1.391-2.078-1.391-2.765 0-.62.564-1.381 1.73-2.054z" transform="rotate(-60 12 12)"/>
    </svg>
    <?php
}

/**
 * Display Heart Icon SVG
 */
function pdfviewer_heart_icon($size = 16, $class = 'text-accent') {
    ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo intval($size); ?>" height="<?php echo intval($size); ?>" viewBox="0 0 24 24" fill="currentColor" class="<?php echo esc_attr($class); ?> flex-shrink-0" aria-label="<?php esc_attr_e('love', 'pdfviewer'); ?>">
        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
    </svg>
    <?php
}

/**
 * Output Current Year
 *
 * @return string Current year (4 digits)
 */
function pdfviewer_current_year() {
    return wp_date('Y');
}
