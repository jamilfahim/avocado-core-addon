<?php
/**
* Plugin Name: fahim Core 
* Description: Premium & Advanced Essential Elements for Elementor
* Plugin URI:  http://themeforest.net/user/KlbTheme
* Version:     1.1.6
* Author:      KlbTheme
* Author URI:  http://themeforest.net/user/KlbTheme
*/


/*
* Exit if accessed directly.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

final class Fahim_Elementor_Addons
{
    /**
    * Plugin Version
    *
    * @since 1.0
    *
    * @var string The plugin version.
    */
    const VERSION = '1.0.0';

    /**
    * Minimum Elementor Version
    *
    * @since 1.0
    *
    * @var string Minimum Elementor version required to run the plugin.
    */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
    * Minimum PHP Version
    *
    * @since 1.0
    *
    * @var string Minimum PHP version required to run the plugin.
    */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
    * Instance
    *
    * @since 1.0
    *
    * @access private
    * @static
    *
    * @var Fahim_Elementor_Addons The single instance of the class.
    */
    private static $_instance = null;

    /**
    * Instance
    *
    * Ensures only one instance of the class is loaded or can be loaded.
    *
    * @since 1.0
    *
    * @access public
    * @static
    *
    * @return Fahim_Elementor_Addons An instance of the class.
    */
    public static function instance()
    {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
    * Constructor
    *
    * @since 1.0
    *
    * @access public
    */
    public function __construct()
    {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
    * Load Textdomain
    *
    * Load plugin localization files.
    *
    * Fired by `init` action hook.
    *
    * @since 1.0
    *
    * @access public
    */
    public function i18n()
    {
        load_plugin_textdomain( 'fahim-core' );
    }
	
   /**
    * Initialize the plugin
    *
    * Load the plugin only after Elementor (and other plugins) are loaded.
    * Checks for basic plugin requirements, if one check fail don't continue,
    * if all check have passed load the files required to run the plugin.
    *
    * Fired by `plugins_loaded` action hook.
    *
    * @since 1.0
    *
    * @access public
    */
    public function init()
    {
        // Check if Elementor is installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'bacola_admin_notice_missing_main_plugin' ] );
            return;
        }
        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'bacola_admin_notice_minimum_elementor_version' ] );
            return;
        }
        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'bacola_admin_notice_minimum_php_version' ] );
            return;
        }
		
		// Categories registered
        add_action( 'elementor/elements/categories_registered', [ $this, 'fahim_add_widget_category' ] );

        /* Custom plugin helper functions */
        require_once( __DIR__ . '/classes/class-helpers-functions.php' );
		
		
        // Widgets registered
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

        // Register Widget Styles
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
		
        // Register Widget Scripts
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'widget_scripts' ] );
    }
	
    /**
    * Register Widgets Category
    *
    */
    public function fahim_add_widget_category( $elements_manager )
    {
        $elements_manager->add_category( 'fahim', ['title' => esc_html__( 'Fahim Core', 'fahim-core' )]);
    }	
	
    /**
    * Init Widgets
    *
    * Include widgets files and register them
    *
    * @since 1.0
    *
    * @access public
    */
    public function init_widgets()
    {

		// Product Categories
		require_once( __DIR__ . '/widgets/product-categories.php' );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Fahim_Product_grid_Widget() );
		
        // Product crousel
		require_once( __DIR__ . '/widgets/product-carousel.php' );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Fahim_Product_Crousel_Widget() );

         // Product hover card
		require_once( __DIR__ . '/widgets/product-hover-card.php' );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Fahim_Product_Card_Hover_Widget() );
		
	}
	
	
    /**
    * Admin notice
    *
    * Warning when the site doesn't have Elementor installed or activated.
    *
    * @since 1.0
    *
    * @access public
    */
    public function bacola_admin_notice_missing_main_plugin()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '%1$s requires %2$s to be installed and activated.', 'bacola-core' ),
            '<strong>' . esc_html__( 'Bacola Core', 'bacola-core' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'bacola-core' ) . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required Elementor version.
    *
    * @since 1.0
    *
    * @access public
    */
    public function bacola_admin_notice_minimum_elementor_version()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '%1$s requires %2$s version %3$s or greater.', 'bacola-core' ),
            '<strong>' . esc_html__( 'Bacola Core', 'bacola-core' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'bacola-core' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required PHP version.
    *
    * @since 1.0
    *
    * @access public
    */
    public function bacola_admin_notice_minimum_php_version()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '%1$s requires %2$s version %3$s or greater.', 'bacola-core' ),
            '<strong>' . esc_html__( 'Bacola Core', 'bacola-core' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'bacola-core' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
	
    public function widget_styles()
    {
        wp_enqueue_style( 'widget-style', plugins_url( 'assets/css/style.css', __FILE__ ));
        wp_enqueue_style( 'slick-theme-style', plugins_url( 'assets/slick/slick-theme.css', __FILE__ ));
        wp_enqueue_style( 'slick-style', plugins_url( 'assets/slick/slick.css', __FILE__ ));
      
       
    }

    public function widget_scripts()
    {
       
      
        wp_enqueue_script( 'slick-scripts', plugins_url( 'assets/slick/slick.min.js', __FILE__ ), array('jquery'),null,true );
        wp_enqueue_script( 'main-scripts', plugins_url( 'assets/js/main.js', __FILE__ ), array('jquery'),null,true );
       

    }
	



} 
Fahim_Elementor_Addons::instance();