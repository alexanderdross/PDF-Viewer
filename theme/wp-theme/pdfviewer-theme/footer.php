<?php
/**
 * Theme Footer
 *
 * @package PDFViewer
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
</main>

<footer class="site-footer gradient-dark text-primary-foreground" role="contentinfo">
    <div class="container mx-auto px-4 py-16 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <?php // Brand Column ?>
            <div class="md:col-span-2">
                <a href="<?php echo esc_url(home_url('/')); ?>"
                   class="flex items-center gap-2 mb-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-lg"
                   aria-label="<?php esc_attr_e('PDF Embed & SEO Optimize - Home', 'pdfviewer'); ?>"
                   title="<?php esc_attr_e('Go to PDF Embed & SEO Optimize homepage', 'pdfviewer'); ?>">
                    <?php
                    echo pdfviewer_simple_picture(array(
                        'webp'     => PDFVIEWER_THEME_URI . '/assets/images/logo.webp',
                        'fallback' => PDFVIEWER_THEME_URI . '/assets/images/logo.png',
                        'alt'      => __('PDF Embed & SEO Optimize logo', 'pdfviewer'),
                        'width'    => 40,
                        'height'   => 40,
                        'class'    => 'rounded-lg',
                        'lazy'     => true,
                        'title'    => __('PDF Embed & SEO Optimize logo', 'pdfviewer'),
                    ));
                    ?>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg leading-tight"><?php esc_html_e('PDF Embed & SEO Optimize', 'pdfviewer'); ?></span>
                        <span class="text-xs text-primary-foreground/60 leading-tight"><?php esc_html_e('for WordPress, Drupal & React', 'pdfviewer'); ?></span>
                    </div>
                </a>

                <p class="text-primary-foreground/70 max-w-md mb-6">
                    <?php esc_html_e('The open-source plugin that transforms how you serve PDFs. Built with Mozilla\'s PDF.js for seamless viewing and optimized for SEO. Available for WordPress, Drupal and React / Next.js.', 'pdfviewer'); ?>
                </p>

                <p class="text-xs text-primary-foreground/50 mb-2"><?php esc_html_e('Download Free Versions:', 'pdfviewer'); ?></p>
                <div class="flex items-center gap-4">
                    <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/#download-wordpress')); ?>"
                       class="text-primary-foreground/60 hover:text-primary-foreground transition-colors flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                       aria-label="<?php esc_attr_e('Download free WordPress plugin', 'pdfviewer'); ?>"
                       title="<?php esc_attr_e('Download free PDF Embed & SEO Optimize plugin for WordPress', 'pdfviewer'); ?>">
                        <?php pdfviewer_wordpress_icon(14); ?>
                        <span class="text-sm"><?php esc_html_e('WordPress', 'pdfviewer'); ?></span>
                        <?php echo pdfviewer_icon('arrow-right', 14); ?>
                    </a>

                    <span class="text-primary-foreground/40" aria-hidden="true">|</span>

                    <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/#download-drupal')); ?>"
                       class="text-primary-foreground/60 hover:text-primary-foreground transition-colors flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                       aria-label="<?php esc_attr_e('Download free Drupal module', 'pdfviewer'); ?>"
                       title="<?php esc_attr_e('Download free PDF Embed & SEO Optimize module for Drupal', 'pdfviewer'); ?>">
                        <?php pdfviewer_drupal_icon(14); ?>
                        <span class="text-sm"><?php esc_html_e('Drupal', 'pdfviewer'); ?></span>
                        <?php echo pdfviewer_icon('arrow-right', 14); ?>
                    </a>

                    <span class="text-primary-foreground/40" aria-hidden="true">|</span>

                    <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/#download-react')); ?>"
                       class="text-primary-foreground/60 hover:text-primary-foreground transition-colors flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                       aria-label="<?php esc_attr_e('Download free React / Next.js component', 'pdfviewer'); ?>"
                       title="<?php esc_attr_e('Download free PDF Embed & SEO Optimize component for React / Next.js', 'pdfviewer'); ?>">
                        <?php pdfviewer_react_icon(14); ?>
                        <span class="text-sm"><?php esc_html_e('React', 'pdfviewer'); ?></span>
                        <?php echo pdfviewer_icon('arrow-right', 14); ?>
                    </a>
                </div>
            </div>

            <?php // Resources Column ?>
            <nav aria-label="<?php esc_attr_e('Footer resources navigation', 'pdfviewer'); ?>">
                <h3 class="font-semibold mb-4 text-base"><?php esc_html_e('Resources', 'pdfviewer'); ?></h3>
                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'space-y-3',
                        'container'      => false,
                        'items_wrap'     => '<ul id="%1$s" class="%2$s" role="list">%3$s</ul>',
                        'fallback_cb'    => false,
                        'link_before'    => '',
                        'link_after'     => '',
                    ));
                } else {
                    ?>
                    <ul class="space-y-3" role="list">
                        <li>
                            <a href="<?php echo esc_url(home_url('/#features')); ?>"
                               class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                               title="<?php esc_attr_e('Explore key features of PDF Embed & SEO Optimize', 'pdfviewer'); ?>">
                                <?php esc_html_e('Features', 'pdfviewer'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/documentation/')); ?>"
                               class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                               title="<?php esc_attr_e('Read the plugin documentation and setup guides', 'pdfviewer'); ?>">
                                <?php esc_html_e('Documentation', 'pdfviewer'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/examples/')); ?>"
                               class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                               title="<?php esc_attr_e('View example implementations and use cases', 'pdfviewer'); ?>">
                                <?php esc_html_e('Examples', 'pdfviewer'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/pro/')); ?>"
                               class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                               title="<?php esc_attr_e('Explore Pro version features and pricing', 'pdfviewer'); ?>">
                                <?php esc_html_e('Pro Features', 'pdfviewer'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/changelog/')); ?>"
                               class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                               title="<?php esc_attr_e('View version history and recent updates', 'pdfviewer'); ?>">
                                <?php esc_html_e('Changelog', 'pdfviewer'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/enterprise/')); ?>"
                               class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                               title="<?php esc_attr_e('Enterprise plans for regulated industries', 'pdfviewer'); ?>">
                                <?php esc_html_e('Enterprise', 'pdfviewer'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="https://dross.net/contact/?topic=pdfviewer"
                               target="_blank" rel="noopener"
                               class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm"
                               title="<?php esc_attr_e('Contact us for enterprise consultation', 'pdfviewer'); ?>">
                                <?php esc_html_e('Contact', 'pdfviewer'); ?>
                            </a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
            </nav>

            <?php // Platform Guides Column ?>
            <nav aria-label="<?php esc_attr_e('Footer platform guides navigation', 'pdfviewer'); ?>">
                <h3 class="font-semibold mb-4 text-base"><?php esc_html_e('Platform Guides', 'pdfviewer'); ?></h3>
                <ul class="space-y-3" role="list">
                    <li>
                        <a href="<?php echo esc_url(home_url('/wordpress-pdf-viewer/')); ?>"
                           class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm flex items-center gap-1.5"
                           title="<?php esc_attr_e('WordPress PDF Viewer Plugin - embed and optimize PDFs', 'pdfviewer'); ?>">
                            <?php pdfviewer_wordpress_icon(12); ?>
                            <?php esc_html_e('WordPress PDF Viewer', 'pdfviewer'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/drupal-pdf-viewer/')); ?>"
                           class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm flex items-center gap-1.5"
                           title="<?php esc_attr_e('Drupal PDF Viewer Module - embed and optimize PDFs', 'pdfviewer'); ?>">
                            <?php pdfviewer_drupal_icon(12); ?>
                            <?php esc_html_e('Drupal PDF Viewer', 'pdfviewer'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/nextjs-pdf-viewer/')); ?>"
                           class="text-primary-foreground/70 hover:text-primary-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 rounded-sm flex items-center gap-1.5"
                           title="<?php esc_attr_e('React / Next.js PDF Components - modern React integration', 'pdfviewer'); ?>">
                            <?php pdfviewer_react_icon(12); ?>
                            <?php esc_html_e('React / Next.js', 'pdfviewer'); ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <?php // Bottom Bar ?>
        <div class="border-t border-primary-foreground/10 mt-12 pt-8 text-sm text-primary-foreground/60">
            <div class="container mx-auto px-4 lg:px-8 flex flex-wrap items-center justify-between gap-4">
                <p>&copy; <?php echo esc_html(pdfviewer_current_year()); ?> <?php esc_html_e('PDF Embed & SEO Optimize. All rights reserved.', 'pdfviewer'); ?></p>

                <?php // Legal Links ?>
                <nav aria-label="<?php esc_attr_e('Legal links', 'pdfviewer'); ?>">
                    <a href="<?php echo esc_url(pdfviewer_get_option('imprint_url', 'https://dross.net/imprint?ref=pdfviewer')); ?>" target="_blank" rel="noopener" class="hover:text-primary-foreground transition-colors" title="<?php esc_attr_e('View company imprint and contact information', 'pdfviewer'); ?>"><?php esc_html_e('Imprint', 'pdfviewer'); ?></a>
                    <span class="text-primary-foreground/40 mx-2" aria-hidden="true">|</span>
                    <a href="<?php echo esc_url(pdfviewer_get_option('privacy_url', 'https://dross.net/privacy-policy?ref=pdfviewer')); ?>" target="_blank" rel="noopener" class="hover:text-primary-foreground transition-colors" title="<?php esc_attr_e('Read our privacy policy', 'pdfviewer'); ?>"><?php esc_html_e('Privacy Policy', 'pdfviewer'); ?></a>
                    <span class="text-primary-foreground/40 mx-2" aria-hidden="true">|</span>
                    <a href="https://dross.net/contact/?topic=pdfviewer" target="_blank" rel="noopener" class="hover:text-primary-foreground transition-colors" title="<?php esc_attr_e('Contact us', 'pdfviewer'); ?>"><?php esc_html_e('Contact', 'pdfviewer'); ?></a>
                </nav>

                <p><?php esc_html_e('Made with', 'pdfviewer'); ?> <?php pdfviewer_heart_icon(16, 'text-accent inline-block align-middle'); ?> <?php esc_html_e('by', 'pdfviewer'); ?> <a href="<?php echo esc_url(pdfviewer_get_option('company_url', 'https://dross.net/?ref=pdfviewer#media')); ?>" target="_blank" rel="noopener" class="text-accent hover:underline" title="<?php esc_attr_e('Visit Dross:Media website', 'pdfviewer'); ?>">Dross:Media</a></p>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
