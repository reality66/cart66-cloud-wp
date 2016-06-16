<?php

final class Cart66_Cloud {

    public static $instance;

    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Cart66_Cloud();
        }

        return self::$instance;
    }

    private function __construct() {
        $this->define_constants();
        spl_autoload_register( array( $this, 'class_loader' ) );
        $this->include_core_files();
        $this->register_actions();
    }

    private function define_constants() {
        $plugin_dir = basename(dirname(__DIR__));
        define( 'CC_PATH', WP_PLUGIN_DIR . '/' . $plugin_dir . '/' );
        define( 'CC_URL',  WP_PLUGIN_URL . '/' . $plugin_dir . '/' );
        define( 'CC_TEMPLATE_DEBUG_MODE', false );
        define( 'CC_VERSION_NUMBER', '2.0.13' );
    }

    private function include_core_files() {
        include_once( CC_PATH . 'includes/cc-helper-functions.php' );
        include_once( CC_PATH . 'includes/cc-partial-functions.php' );
        include_once( CC_PATH . 'includes/cc-actions.php');
        include_once( CC_PATH . 'includes/cc-product-post-type.php' );
        include_once( CC_PATH . 'includes/cc-template-manager.php' );
        include_once( CC_PATH . 'includes/cc-requests-authenticated.php' );
        include_once( CC_PATH . 'includes/cc-request-handlers.php' ); // Handle incoming tasks and custom routes
        include_once( CC_PATH . 'includes/class-cc-routes.php' );
        include_once( CC_PATH . 'includes/admin/cc-image-meta-box.php' );

        if( is_admin() ) {
            include_once( CC_PATH . 'includes/admin/class-cc-admin.php' );
            include_once( CC_PATH . 'includes/admin/cc-product-meta-box.php' );
        }
    }

    public function register_actions() {

        // Initialize core classes
        add_action( 'init', array( $this, 'init' ), 0 );

        // Check for incoming cart66 tasks and actions
        add_action( 'wp_loaded', 'cc_task_dispatcher' );
        add_action( 'parse_query', 'cc_route_handler' );

        // Register custom post type for products
        add_action( 'init', 'cc_register_product_post_type' );

        // Add actions to process all add to cart requests via ajax
        add_action( 'wp_enqueue_scripts',                 'cc_enqueue_ajax_add_to_cart' );
        add_action( 'wp_enqueue_scripts',                 'cc_enqueue_cart66_wordpress_js' );
        add_action( 'wp_enqueue_scripts',                 'cc_enqueue_cart66_styles' );
        add_action( 'wp_enqueue_scripts',                 'cc_enqueue_featherlight' );
        add_action( 'wp_ajax_cc_ajax_add_to_cart',        array('CC_Cart', 'ajax_add_to_cart') );
        add_action( 'wp_ajax_nopriv_cc_ajax_add_to_cart', array('CC_Cart', 'ajax_add_to_cart') );

        // Check if request is a page slurp
        add_action( 'template_redirect', array('CC_Page_Slurp', 'check_slurp') );

        // Preload cart summary if available, otherwise drop unknown carts
        add_action( 'template_redirect', array('CC_Cart', 'preload_summary') );

        // Register sidebar widget
        add_action ('widgets_init', function() {
            register_widget('CC_Cart_Widget');
        } );

        // Write custom css to the head
        add_action( 'wp_head', 'cc_custom_css' );

        // Refresh notices after theme switch
        add_action( 'after_switch_theme', 'cc_reset_theme_notices' );

        // Register activation and deactivation hooks
        register_activation_hook( __FILE__, 'cc_activate' );
        register_deactivation_hook( __FILE__, 'cc_deactivate' );

        // Add filter for hiding slurp page from navigation
        add_filter( 'get_pages', 'CC_Page_Slurp::hide_page_slurp' );

        if ( 'yes' == CC_Admin_Setting::get_option( 'cart66_post_type_settings', 'product_templates' ) ) {
            // Add filter for rendering post type page templates
            add_filter( 'template_include', 'cc_template_include' );

            // Only register category widget when using product post type templates
            add_action( 'widgets_init', create_function('', 'return register_widget("CC_Category_Widget");') );
        }
        else {
            // Add filter for to attempt to get products showing as pages rather than posts
            add_filter( 'template_include', 'cc_use_page_template' );

            // Add filter for rendering product partial with gallery and order form
            add_filter( 'the_content', 'cc_filter_product_single' );
        }

    }

    public function init() {
        do_action( 'before_cart66_init' );

        CC_Shortcode_Manager::init();

        do_action ( 'after_cart66_init' );
    }

    public static function class_loader($class) {
        if(cc_starts_with($class, 'CC_')) {
            $class = strtolower($class);
            $file = 'class-' . str_replace( '_', '-', $class ) . '.php';
            $root = CC_PATH;

            if(cc_starts_with($class, 'cc_exception')) {
                include_once $root . 'includes/exception-library.php';
            } elseif ( cc_starts_with( $class, 'cc_admin_setting' ) ) {
                include_once $root . 'includes/admin/settings/' . $file;
            } elseif ( cc_starts_with( $class, 'cc_admin' ) ) {
                include_once $root . 'includes/admin/' . $file;
            } elseif ( cc_starts_with( $class, 'cc_cloud' ) ) {
                include_once $root . 'includes/cloud/' . $file;
            } else {
                include_once $root . 'includes/' . $file;
            }

        } elseif($class == 'CC') {
            include CC_PATH . 'includes/class-cc.php';
        }
    }

    /**
     * Get the plugin path
     *
     * @return string
     */
    public function plugin_path() {
        return CC_PATH;
    }

    public function plugin_url() {
        return CC_URL;
    }

    /**
     * Get the template path
     *
     * @return string
     */
    public function template_path() {
        return apply_filters( 'cart66_template_path', 'cart66/' );
    }

    public function version_number() {
        return CC_VERSION_NUMBER;
    }
}
