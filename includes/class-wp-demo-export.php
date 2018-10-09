<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://addonspress.com/
 * @since      1.0.0
 *
 * @package    Wp_Demo_Export
 * @subpackage Wp_Demo_Export/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Demo_Export
 * @subpackage Wp_Demo_Export/includes
 * @author     AddonsPress <addonspress.com>
 */
class Wp_Demo_Export {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      Wp_Demo_Export_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	public $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	public $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $version    The current version of the plugin.
	 */
	public $version;

	/**
	 * The admin class object of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object Wp_Demo_Export_Admin    $admin
	 */
	public $admin;

	/**
	 * The language object of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object Wp_Demo_Export_i18n    $plugin_i18n
	 */
	public $plugin_i18n;

	/**
	 * Main Wp_Demo_Export Instance
	 *
	 * Insures that only one instance of Wp_Demo_Export exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @uses Wp_Demo_Export::setup_globals() Setup the globals needed
	 * @uses Wp_Demo_Export::load_dependencies() Include the required files
	 * @uses Wp_Demo_Export::set_locale() Setup language
	 * @uses Wp_Demo_Export::define_admin_hooks() Setup admin hooks and actions
	 * @uses Wp_Demo_Export::run() run
	 * @return object
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new Wp_Demo_Export;

			$instance->setup_globals();
			$instance->load_dependencies();
			$instance->set_locale();
			$instance->define_admin_hooks();
			$instance->run();
		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Empty construct
	 *
	 * @since    1.0.0
	 */
	public function __construct() { }

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Demo_Export_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setup_globals() {

		$this->version = defined('WP_DEMO_EXPORT_VERSION')?WP_DEMO_EXPORT_VERSION:'1.0.0';
		$this->plugin_name = 'wp-demo-export';
		
		//The array of actions and filters registered with this plugins.
		$this->actions = array();
		$this->filters = array();

		// Misc
		$this->domain         = 'wp-demo-export';      // Unique identifier for retrieving translated strings
		$this->errors         = new WP_Error(); // errors
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Demo_Export_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Demo_Export_i18n. Defines internationalization functionality.
	 * - Wp_Demo_Export_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WP_DEMO_EXPORT_PATH . 'includes/class-wp-demo-export-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WP_DEMO_EXPORT_PATH . 'includes/class-wp-demo-export-i18n.php';

		/**
		 * Export Form
		 */
		require_once WP_DEMO_EXPORT_PATH . 'admin/function-form-load.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WP_DEMO_EXPORT_PATH . 'admin/class-wp-demo-export-admin.php';

		$this->loader = new Wp_Demo_Export_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Demo_Export_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$this->plugin_i18n = new Wp_Demo_Export_i18n();

		$this->loader->add_action( 'plugins_loaded', $this->plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->admin = new Wp_Demo_Export_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $this->admin, 'export_menu' );
		$this->loader->add_action( 'admin_init', $this->admin, 'export_content',1 );
		$this->loader->add_action( 'wp_ajax_wp_demo_export_ajax_form_load', $this->admin, 'form_load' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Demo_Export_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}