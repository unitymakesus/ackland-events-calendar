<?php
/**
 * Plugin Name: Simple Google Rating
 * Plugin URI: http://domain.com/simple-google-rating/
 * Description: Just a badge showing the Google Rating for a business.
 * Version: 1.0.0
 * Author: Alisa Herr | Unity Digital Agency
 * Author URI: https://www.unitymakes.us/
 * Requires at least: 4.0.0
 * Tested up to: 4.0.0
 *
 * Text Domain: simple-gr
 * Domain Path: /languages/
 *
 * @package Simple_Google_Rating
 * @category Core
 * @author Unity
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of Simple_Google_Rating to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Simple_Google_Rating
 */
function Simple_Google_Rating() {
	return Simple_Google_Rating::instance();
} // End Simple_Google_Rating()

add_action( 'plugins_loaded', 'Simple_Google_Rating' );

/**
 * Main Simple_Google_Rating Class
 *
 * @class Simple_Google_Rating
 * @version	1.0.0
 * @since 1.0.0
 * @package Simple_Google_Rating
 * @author Unity
 */
final class Simple_Google_Rating {
	/**
	 * Simple_Google_Rating The single instance of Simple_Google_Rating.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The plugin directory URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin directory path.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The settings object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings;
	// Admin - End

	// Post Types - Start
	/**
	 * The post types we're registering.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $post_types = array();
	// Post Types - End
	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct () {
		$this->token 			= 'simple-google-rating';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.0';

		// Admin - Start
		require_once( 'classes/class-simple-google-rating-settings.php' );
			$this->settings = Simple_Google_Rating_Settings::instance();

		if ( is_admin() ) {
			require_once( 'classes/class-simple-google-rating-admin.php' );
			$this->admin = Simple_Google_Rating_Admin::instance();
		}

		add_action( 'admin_enqueue_scripts', array($this, 'load_scripts_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'load_scripts_styles') );
		// Admin - End

		// Shortcode
		require_once( 'classes/class-simple-google-rating-shortcodes.php' );
		$this->shortcodes = Simple_Google_Rating_Shortcodes::instance();


		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	} // End __construct()

	/**
	 * Main Simple_Google_Rating Instance
	 *
	 * Ensures only one instance of Simple_Google_Rating is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Simple_Google_Rating()
	 * @return Main Simple_Google_Rating instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Enqeueue plugin scripts and styles on front end.
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_scripts_styles($hook) {
		// Load only on settings page for this plugin OR front-end
		if($hook == 'settings_page_simple-google-rating' || !is_admin()) {
			wp_enqueue_style( 'sgr_css', plugins_url('css/style.css', __FILE__) );
		}
	}

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'simple-google-rating', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()

	/**
	 * Cloning is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 */
	public function install () {
		$this->_log_version_number();
	} // End install()

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 */
	private function _log_version_number () {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	} // End _log_version_number()
} // End Class
