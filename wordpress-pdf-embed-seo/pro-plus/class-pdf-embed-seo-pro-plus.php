<?php
/**
 * PDF Embed & SEO Optimize - Pro+ Enterprise Module
 *
 * @package    PDF_Embed_SEO_Optimize
 * @subpackage Pro_Plus
 * @version    1.3.0
 * @author     Dross:Media
 * @license    GPL-2.0+
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Pro+ class.
 *
 * @since 1.3.0
 */
final class PDF_Embed_SEO_Pro_Plus {

    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '1.3.1';

    /**
     * Minimum Premium version required.
     *
     * @var string
     */
    const MIN_PREMIUM_VERSION = '1.2.0';

    /**
     * Single instance.
     *
     * @var PDF_Embed_SEO_Pro_Plus|null
     */
    private static $instance = null;

    /**
     * License status.
     *
     * @var string
     */
    private $license_status = 'inactive';

    /**
     * Pro+ features instances.
     */
    public $advanced_analytics;
    public $security;
    public $webhooks;
    public $white_label;
    public $versioning;
    public $annotations;
    public $compliance;
    public $rest_api;

    /**
     * Get single instance.
     *
     * @since 1.3.0
     * @return PDF_Embed_SEO_Pro_Plus
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    private function __construct() {
        $this->define_constants();
        $this->check_requirements();
    }

    /**
     * Define Pro+ constants.
     *
     * @since 1.3.0
     */
    private function define_constants() {
        if ( ! defined( 'PDF_EMBED_SEO_PRO_PLUS_VERSION' ) ) {
            define( 'PDF_EMBED_SEO_PRO_PLUS_VERSION', self::VERSION );
        }
        if ( ! defined( 'PDF_EMBED_SEO_PRO_PLUS_DIR' ) ) {
            define( 'PDF_EMBED_SEO_PRO_PLUS_DIR', plugin_dir_path( __FILE__ ) );
        }
        if ( ! defined( 'PDF_EMBED_SEO_PRO_PLUS_URL' ) ) {
            define( 'PDF_EMBED_SEO_PRO_PLUS_URL', plugin_dir_url( __FILE__ ) );
        }
    }

    /**
     * License API base URL for remote validation.
     *
     * @var string
     */
    const LICENSE_API_URL = 'https://pdfviewer.drossmedia.de/wp-json/plm/v1';

    /**
     * Check requirements before initializing.
     *
     * @since 1.3.0
     */
    private function check_requirements() {
        // Check if Premium is active.
        if ( ! function_exists( 'pdf_embed_seo_is_premium' ) || ! pdf_embed_seo_is_premium() ) {
            add_action( 'admin_notices', array( $this, 'premium_required_notice' ) );
            return;
        }

        // Check Premium version.
        if ( defined( 'PDF_EMBED_SEO_PREMIUM_VERSION' ) &&
             version_compare( PDF_EMBED_SEO_PREMIUM_VERSION, self::MIN_PREMIUM_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'premium_version_notice' ) );
            return;
        }

        // Validate license.
        $this->validate_license();

        // Initialize if license is valid.
        if ( $this->is_license_valid() ) {
            add_action( 'init', array( $this, 'init' ), 25 );
        } else {
            add_action( 'admin_notices', array( $this, 'license_notice' ) );
        }

        // License heartbeat cron.
        add_action( 'pdf_embed_seo_pro_plus_license_check', array( $this, 'heartbeat_check' ) );
        if ( ! wp_next_scheduled( 'pdf_embed_seo_pro_plus_license_check' ) ) {
            wp_schedule_event( time(), 'daily', 'pdf_embed_seo_pro_plus_license_check' );
        }

        // Deactivate license when plugin is deactivated.
        $plugin_file = defined( 'PDF_EMBED_SEO_PLUGIN_BASENAME' ) ? PDF_EMBED_SEO_PLUGIN_BASENAME : '';
        if ( $plugin_file ) {
            register_deactivation_hook( WP_PLUGIN_DIR . '/' . $plugin_file, array( __CLASS__, 'deactivate_license_on_plugin_deactivate' ) );
        }

        // Always add admin menu for license management.
        add_action( 'admin_menu', array( $this, 'add_license_submenu' ), 30 );
        add_action( 'admin_init', array( $this, 'register_license_settings' ) );
    }

    /**
     * Initialize Pro+ features.
     *
     * @since 1.3.0
     */
    public function init() {
        if ( ! defined( 'PDF_EMBED_SEO_IS_PRO_PLUS' ) ) {
            define( 'PDF_EMBED_SEO_IS_PRO_PLUS', true );
        }

        $this->includes();
        $this->init_features();
        $this->init_hooks();

        /**
         * Action fired when Pro+ is fully initialized.
         *
         * @since 1.3.0
         */
        do_action( 'pdf_embed_seo_pro_plus_init' );
    }

    /**
     * Include required files.
     *
     * @since 1.3.0
     */
    private function includes() {
        // Core Pro+ classes.
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-admin.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-advanced-analytics.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-security.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-webhooks.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-white-label.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-versioning.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-annotations.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-compliance.php';
        require_once PDF_EMBED_SEO_PRO_PLUS_DIR . 'includes/class-pdf-embed-seo-pro-plus-rest-api.php';
    }

    /**
     * Initialize feature classes.
     *
     * @since 1.3.0
     */
    private function init_features() {
        $settings = $this->get_settings();

        // Admin (always loaded).
        new PDF_Embed_SEO_Pro_Plus_Admin();

        // Advanced Analytics (heatmaps, engagement scoring).
        if ( ! empty( $settings['enable_advanced_analytics'] ) ) {
            $this->advanced_analytics = new PDF_Embed_SEO_Pro_Plus_Advanced_Analytics();
        }

        // Security (2FA, IP whitelisting, audit logs).
        if ( ! empty( $settings['enable_security'] ) ) {
            $this->security = new PDF_Embed_SEO_Pro_Plus_Security();
        }

        // Webhooks & Integrations.
        if ( ! empty( $settings['enable_webhooks'] ) ) {
            $this->webhooks = new PDF_Embed_SEO_Pro_Plus_Webhooks();
        }

        // White Label & Branding.
        if ( ! empty( $settings['enable_white_label'] ) ) {
            $this->white_label = new PDF_Embed_SEO_Pro_Plus_White_Label();
        }

        // Document Versioning.
        if ( ! empty( $settings['enable_versioning'] ) ) {
            $this->versioning = new PDF_Embed_SEO_Pro_Plus_Versioning();
        }

        // Annotations & Signatures.
        if ( ! empty( $settings['enable_annotations'] ) ) {
            $this->annotations = new PDF_Embed_SEO_Pro_Plus_Annotations();
        }

        // Compliance (GDPR, HIPAA).
        if ( ! empty( $settings['enable_compliance'] ) ) {
            $this->compliance = new PDF_Embed_SEO_Pro_Plus_Compliance();
        }

        // REST API (always loaded for Pro+ endpoints).
        $this->rest_api = new PDF_Embed_SEO_Pro_Plus_REST_API();
    }

    /**
     * Initialize hooks.
     *
     * @since 1.3.0
     */
    private function init_hooks() {
        // Enqueue admin assets.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

        // Enqueue frontend assets.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );

        // Add Pro+ badge to admin menu.
        add_action( 'admin_head', array( $this, 'add_pro_plus_badge_styles' ) );

        // Filter REST API responses.
        add_filter( 'pdf_embed_seo_rest_document', array( $this, 'add_pro_plus_data' ), 20, 3 );
    }

    /**
     * Validate Pro+ license via remote API with local fallback.
     *
     * @since 1.3.0
     */
    private function validate_license() {
        $license_key = get_option( 'pdf_embed_seo_pro_plus_license_key', '' );
        $stored_status = get_option( 'pdf_embed_seo_pro_plus_license_status', 'inactive' );

        if ( empty( $license_key ) ) {
            $this->license_status = 'inactive';
            return;
        }

        // Attempt remote validation against License Dashboard API.
        $response = wp_remote_post( self::LICENSE_API_URL . '/license/validate', array(
            'timeout' => 15,
            'body'    => array(
                'license_key' => $license_key,
                'platform'    => 'wordpress',
            ),
        ) );

        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( ! empty( $data['valid'] ) ) {
                $this->license_status = 'valid';
                update_option( 'pdf_embed_seo_pro_plus_license_status', 'valid' );
                if ( ! empty( $data['expires_at'] ) ) {
                    update_option( 'pdf_embed_seo_pro_plus_license_expires', gmdate( 'Y-m-d', strtotime( $data['expires_at'] ) ) );
                } else {
                    delete_option( 'pdf_embed_seo_pro_plus_license_expires' );
                }
                update_option( 'pdf_embed_seo_pro_plus_license_type', $data['type'] ?? 'pro_plus' );
                update_option( 'pdf_embed_seo_pro_plus_license_plan', $data['plan'] ?? 'starter' );
                update_option( 'pdf_embed_seo_pro_plus_last_check', time() );

                // Activate this site with the license.
                $this->activate_remote( $license_key );
                return;
            }

            // API responded but key is not valid.
            $this->license_status = $data['status'] ?? 'invalid';
            update_option( 'pdf_embed_seo_pro_plus_license_status', $this->license_status );
            if ( ! empty( $data['expires_at'] ) ) {
                update_option( 'pdf_embed_seo_pro_plus_license_expires', gmdate( 'Y-m-d', strtotime( $data['expires_at'] ) ) );
            }
            return;
        }

        // Remote validation failed — fall back to local regex.
        $this->validate_license_locally( $license_key );

        // Update stored status if changed.
        if ( $stored_status !== $this->license_status ) {
            update_option( 'pdf_embed_seo_pro_plus_license_status', $this->license_status );
        }
    }

    /**
     * Local-only Pro+ license validation (fallback when API is unreachable).
     *
     * @since 1.3.0
     * @param string $license_key The license key.
     */
    private function validate_license_locally( $license_key ) {
        $expires = get_option( 'pdf_embed_seo_pro_plus_license_expires', '' );

        if ( preg_match( '/^PDF\$PRO\+#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}-[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key ) ) {
            if ( ! empty( $expires ) && strtotime( $expires ) < time() ) {
                if ( strtotime( $expires . ' +14 days' ) < time() ) {
                    $this->license_status = 'expired';
                } else {
                    $this->license_status = 'grace_period';
                }
            } else {
                $this->license_status = 'valid';
            }
        } elseif ( preg_match( '/^PDF\$UNLIMITED#[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key ) ) {
            $this->license_status = 'valid';
        } elseif ( preg_match( '/^PDF\$DEV#[A-Z0-9]{4}-[A-Z0-9]{4}@[A-Z0-9]{4}![A-Z0-9]{4}$/i', $license_key ) ) {
            $this->license_status = 'valid';
        } else {
            $this->license_status = 'invalid';
        }
    }

    /**
     * Activate this site with the License Dashboard.
     *
     * @since 1.3.0
     * @param string $license_key The license key.
     */
    private function activate_remote( $license_key ) {
        wp_remote_post( self::LICENSE_API_URL . '/license/activate', array(
            'timeout' => 15,
            'body'    => array(
                'license_key'    => $license_key,
                'site_url'       => home_url(),
                'platform'       => 'wordpress',
                'plugin_version' => defined( 'PDF_EMBED_SEO_VERSION' ) ? PDF_EMBED_SEO_VERSION : self::VERSION,
                'php_version'    => phpversion(),
                'cms_version'    => get_bloginfo( 'version' ),
            ),
        ) );
    }

    /**
     * Daily heartbeat check against the License Dashboard API.
     *
     * @since 1.3.0
     */
    public function heartbeat_check() {
        $license_key = get_option( 'pdf_embed_seo_pro_plus_license_key', '' );
        if ( empty( $license_key ) ) {
            return;
        }

        $response = wp_remote_post( self::LICENSE_API_URL . '/license/check', array(
            'timeout' => 15,
            'body'    => array(
                'license_key'    => $license_key,
                'site_url'       => home_url(),
                'plugin_version' => defined( 'PDF_EMBED_SEO_VERSION' ) ? PDF_EMBED_SEO_VERSION : self::VERSION,
                'platform'       => 'wordpress',
            ),
        ) );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return; // Fail gracefully, retry next day.
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( ! is_array( $data ) ) {
            return;
        }

        update_option( 'pdf_embed_seo_pro_plus_last_check', time() );

        if ( isset( $data['valid'] ) ) {
            $status = $data['valid'] ? 'valid' : ( $data['status'] ?? 'expired' );
            update_option( 'pdf_embed_seo_pro_plus_license_status', $status );

            if ( ! empty( $data['expires_at'] ) ) {
                update_option( 'pdf_embed_seo_pro_plus_license_expires', gmdate( 'Y-m-d', strtotime( $data['expires_at'] ) ) );
            }

            if ( ! empty( $data['update_available'] ) && ! empty( $data['latest_version'] ) ) {
                update_option( 'pdf_embed_seo_pro_plus_update_available', $data['latest_version'] );
            } else {
                delete_option( 'pdf_embed_seo_pro_plus_update_available' );
            }
        }
    }

    /**
     * Deactivate license when the plugin is deactivated.
     *
     * @since 1.3.0
     */
    public static function deactivate_license_on_plugin_deactivate() {
        $license_key = get_option( 'pdf_embed_seo_pro_plus_license_key', '' );
        if ( empty( $license_key ) ) {
            return;
        }

        wp_remote_post( self::LICENSE_API_URL . '/license/deactivate', array(
            'timeout' => 10,
            'body'    => array(
                'license_key' => $license_key,
                'site_url'    => home_url(),
            ),
        ) );

        wp_clear_scheduled_hook( 'pdf_embed_seo_pro_plus_license_check' );
    }

    /**
     * Check if license is valid.
     *
     * @since 1.3.0
     * @return bool
     */
    public function is_license_valid() {
        return in_array( $this->license_status, array( 'valid', 'grace_period' ), true );
    }

    /**
     * Get license status.
     *
     * @since 1.3.0
     * @return string
     */
    public function get_license_status() {
        return $this->license_status;
    }

    /**
     * Get Pro+ settings.
     *
     * @since 1.3.0
     * @return array
     */
    public function get_settings() {
        $defaults = array(
            'enable_advanced_analytics' => true,
            'enable_security'           => true,
            'enable_webhooks'           => true,
            'enable_white_label'        => true,
            'enable_versioning'         => true,
            'enable_annotations'        => true,
            'enable_compliance'         => true,
            // Advanced Analytics.
            'heatmaps_enabled'          => true,
            'engagement_scoring'        => true,
            'geographic_tracking'       => true,
            'device_analytics'          => true,
            // Security.
            'two_factor_enabled'        => false,
            'ip_whitelist'              => '',
            'audit_log_retention'       => 90,
            'max_failed_attempts'       => 5,
            // Webhooks.
            'webhook_url'               => '',
            'webhook_secret'            => '',
            'webhook_events'            => array( 'view', 'download', 'password_attempt' ),
            // White Label.
            'custom_branding'           => false,
            'hide_powered_by'           => false,
            'custom_logo_url'           => '',
            'custom_css'                => '',
            // Versioning.
            'keep_versions'             => 10,
            'auto_version'              => true,
            // Annotations.
            'allow_user_annotations'    => false,
            'signature_enabled'         => false,
            // Compliance.
            'gdpr_mode'                 => true,
            'hipaa_mode'                => false,
            'data_retention_days'       => 365,
        );

        $settings = get_option( 'pdf_embed_seo_pro_plus_settings', array() );

        return wp_parse_args( $settings, $defaults );
    }

    /**
     * Add license submenu.
     *
     * @since 1.3.0
     */
    public function add_license_submenu() {
        add_submenu_page(
            'edit.php?post_type=pdf_document',
            __( 'Pro+ License', 'pdf-embed-seo-optimize' ),
            __( 'Pro+ License', 'pdf-embed-seo-optimize' ),
            'manage_options',
            'pdf-pro-plus-license',
            array( $this, 'render_license_page' )
        );
    }

    /**
     * Register license settings.
     *
     * @since 1.3.0
     */
    public function register_license_settings() {
        register_setting( 'pdf_embed_seo_pro_plus_license', 'pdf_embed_seo_pro_plus_license_key', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
    }

    /**
     * Render license page.
     *
     * @since 1.3.0
     */
    public function render_license_page() {
        include PDF_EMBED_SEO_PRO_PLUS_DIR . 'admin/views/license-page.php';
    }

    /**
     * Enqueue admin assets.
     *
     * @since 1.3.0
     * @param string $hook Current admin page.
     */
    public function enqueue_admin_assets( $hook ) {
        $screen = get_current_screen();

        if ( ! $screen || 'pdf_document' !== $screen->post_type ) {
            return;
        }

        wp_enqueue_style(
            'pdf-embed-seo-pro-plus-admin',
            PDF_EMBED_SEO_PRO_PLUS_URL . 'admin/css/pro-plus-admin.css',
            array(),
            self::VERSION
        );

        wp_enqueue_script(
            'pdf-embed-seo-pro-plus-admin',
            PDF_EMBED_SEO_PRO_PLUS_URL . 'admin/js/pro-plus-admin.js',
            array( 'jquery' ),
            self::VERSION,
            true
        );

        wp_localize_script( 'pdf-embed-seo-pro-plus-admin', 'pdfProPlusAdmin', array(
            'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'pdf_pro_plus_admin' ),
            'i18n'     => array(
                'confirm' => __( 'Are you sure?', 'pdf-embed-seo-optimize' ),
            ),
        ) );
    }

    /**
     * Enqueue frontend assets.
     *
     * @since 1.3.0
     */
    public function enqueue_frontend_assets() {
        if ( ! is_singular( 'pdf_document' ) ) {
            return;
        }

        wp_enqueue_style(
            'pdf-embed-seo-pro-plus',
            PDF_EMBED_SEO_PRO_PLUS_URL . 'public/css/pro-plus-viewer.css',
            array( 'pdf-embed-seo-premium-viewer' ),
            self::VERSION
        );

        wp_enqueue_script(
            'pdf-embed-seo-pro-plus',
            PDF_EMBED_SEO_PRO_PLUS_URL . 'public/js/pro-plus-viewer.js',
            array( 'jquery', 'pdf-embed-seo-premium-viewer' ),
            self::VERSION,
            true
        );

        wp_localize_script( 'pdf-embed-seo-pro-plus', 'pdfProPlus', array(
            'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
            'restUrl'    => rest_url( 'pdf-embed-seo/v1' ),
            'nonce'      => wp_create_nonce( 'wp_rest' ),
            'postId'     => get_the_ID(),
            'settings'   => $this->get_settings(),
            'i18n'       => array(
                'loading'   => __( 'Loading...', 'pdf-embed-seo-optimize' ),
                'error'     => __( 'An error occurred', 'pdf-embed-seo-optimize' ),
                'annotate'  => __( 'Add Annotation', 'pdf-embed-seo-optimize' ),
                'signature' => __( 'Add Signature', 'pdf-embed-seo-optimize' ),
            ),
        ) );
    }

    /**
     * Add Pro+ badge styles.
     *
     * @since 1.3.0
     */
    public function add_pro_plus_badge_styles() {
        ?>
        <style>
            .pdf-pro-plus-badge {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #fff;
                padding: 2px 6px;
                border-radius: 3px;
                font-size: 10px;
                font-weight: bold;
                margin-left: 5px;
                text-transform: uppercase;
            }
            .pdf-pro-plus-feature {
                border-left: 3px solid #764ba2;
                padding-left: 10px;
            }
        </style>
        <?php
    }

    /**
     * Add Pro+ data to REST response.
     *
     * @since 1.3.0
     * @param array    $data     Response data.
     * @param WP_Post  $post     Post object.
     * @param bool     $detailed Whether to include detailed data.
     * @return array
     */
    public function add_pro_plus_data( $data, $post, $detailed = false ) {
        if ( ! $detailed ) {
            return $data;
        }

        // Add Pro+ specific data.
        $data['pro_plus'] = array(
            'has_versions'     => $this->versioning ? $this->versioning->has_versions( $post->ID ) : false,
            'version_count'    => $this->versioning ? $this->versioning->get_version_count( $post->ID ) : 0,
            'has_annotations'  => $this->annotations ? $this->annotations->has_annotations( $post->ID ) : false,
            'annotation_count' => $this->annotations ? $this->annotations->get_annotation_count( $post->ID ) : 0,
            'engagement_score' => $this->advanced_analytics ? $this->advanced_analytics->get_engagement_score( $post->ID ) : null,
        );

        return $data;
    }

    /**
     * Premium required notice.
     *
     * @since 1.3.0
     */
    public function premium_required_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <strong><?php esc_html_e( 'PDF Embed & SEO Pro+ requires the Premium module to be active.', 'pdf-embed-seo-optimize' ); ?></strong>
                <?php esc_html_e( 'Please activate your Premium license first.', 'pdf-embed-seo-optimize' ); ?>
            </p>
        </div>
        <?php
    }

    /**
     * Premium version notice.
     *
     * @since 1.3.0
     */
    public function premium_version_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <strong><?php esc_html_e( 'PDF Embed & SEO Pro+ requires Premium version', 'pdf-embed-seo-optimize' ); ?> <?php echo esc_html( self::MIN_PREMIUM_VERSION ); ?> <?php esc_html_e( 'or higher.', 'pdf-embed-seo-optimize' ); ?></strong>
                <?php esc_html_e( 'Please update your Premium module.', 'pdf-embed-seo-optimize' ); ?>
            </p>
        </div>
        <?php
    }

    /**
     * License notice.
     *
     * @since 1.3.0
     */
    public function license_notice() {
        $status = $this->get_license_status();
        $class = 'notice-warning';
        $message = '';

        switch ( $status ) {
            case 'inactive':
                $message = __( 'Please enter your Pro+ license key to activate enterprise features.', 'pdf-embed-seo-optimize' );
                break;
            case 'invalid':
                $class = 'notice-error';
                $message = __( 'Your Pro+ license key is invalid. Please check your license key.', 'pdf-embed-seo-optimize' );
                break;
            case 'expired':
                $class = 'notice-error';
                $message = __( 'Your Pro+ license has expired. Please renew to continue using enterprise features.', 'pdf-embed-seo-optimize' );
                break;
            case 'grace_period':
                $message = __( 'Your Pro+ license is in grace period. Please renew within 14 days.', 'pdf-embed-seo-optimize' );
                break;
        }

        if ( $message ) {
            ?>
            <div class="notice <?php echo esc_attr( $class ); ?>">
                <p>
                    <strong><?php esc_html_e( 'PDF Embed & SEO Pro+:', 'pdf-embed-seo-optimize' ); ?></strong>
                    <?php echo esc_html( $message ); ?>
                    <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=pdf_document&page=pdf-pro-plus-license' ) ); ?>">
                        <?php esc_html_e( 'Enter License Key', 'pdf-embed-seo-optimize' ); ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }
}

/**
 * Get Pro+ instance.
 *
 * @since 1.3.0
 * @return PDF_Embed_SEO_Pro_Plus
 */
function pdf_embed_seo_pro_plus() {
    return PDF_Embed_SEO_Pro_Plus::get_instance();
}

/**
 * Check if Pro+ is active.
 *
 * @since 1.3.0
 * @return bool
 */
function pdf_embed_seo_is_pro_plus() {
    return defined( 'PDF_EMBED_SEO_IS_PRO_PLUS' ) && PDF_EMBED_SEO_IS_PRO_PLUS;
}

// Initialize Pro+.
pdf_embed_seo_pro_plus();
