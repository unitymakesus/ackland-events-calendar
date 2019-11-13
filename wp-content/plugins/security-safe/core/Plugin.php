<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class Plugin - Main class for plugin
 * @package SecuritySafe
 */
class Plugin {


    /**
     * local settings values array.
     * @var array
     */
    protected $settings = [];


    /**
     * Is this page a settings page?
     * @var boolean
     */
    public $is_settings_page = '';


    /**
     * Is this page a plugin page?
     * @var boolean
     */ 
    public $is_plugin_page = '';


    /**
     * Logged In Status
     * @var boolean
     */
    public $logged_in;


    /**
     * User
     * @var object
     */
    public $user;


    /**
     * Contains all the admin message values.
     * @var array
     */
    public $messages = array();


    /**
     * Plugin constructor.
     * @since  0.1.0
     */
	function __construct( $session ) {

        // Sets Session Variables
        $this->set_session( $session );

        // Add Text Domain For Translations
        load_plugin_textdomain( SECSAFE_SLUG, false, SECSAFE_DIR_LANG );

        // Retrieve Plugin Settings
        $this->settings = ( empty( $this->settings ) ) ? $this->get_settings() : $this->settings;

        // Check For Upgrades
        $this->upgrade_settings();

	} // __construct()


    /**
     * Sets variables related to this session.
     * @since  2.1.0
     */
    private function set_session( $session ) {

        $this->logged_in = ( isset( $session['logged_in'] ) ) ? $session['logged_in'] : false;
        $this->user = ( isset( $session['user'] ) ) ? $session['user'] : false;

    } // set_session()


    /**
     * Gets variables related to this session.
     * @since  2.1.0
     */
    private static function get_session() {

        $session = [];
        $session['logged_in'] = is_user_logged_in();
        $session['user'] = false;

        if ( $session['logged_in'] ) {

            // Get user once
            $user = wp_get_current_user();

            $new_roles = array_combine( $user->roles, $user->roles );

            // Cache roles
            $session['user']['roles'] = $new_roles;
            
        }

        return $session;

    } // get_session()


    /**
     * Used to update settings in the database.
     * @return array
     * @since 0.1.0
     */
    protected function get_settings() {

        //Janitor::log( 'running get_settings().' );

        $settings = get_option( SECSAFE_OPTIONS );

        // Set settings initially if they do not exist
        if ( ! isset( $settings['general'] ) ) {

            // Initially Set Settings to Default
            //Janitor::log( 'No version in the database. Initially set settings.' );

            $this->reset_settings( true );

            // Get New Initial Settings
            $settings = get_option( SECSAFE_OPTIONS );

        } 

        return $settings;

    } // get_settings()


    /**
     * Used to remove settings in the database.
     * @return array
     * @since 0.2.0
     */
    protected function delete_settings() {

        //Janitor::log( 'running delete_settings()' );

        // Delete settings
        return delete_option( SECSAFE_OPTIONS );

    } // delete_settings()


    /**
     * Used to update settings in the database.
     * @return  boolean
     * @since 0.1.0
     */
    protected function set_settings( $settings ) {

        //Janitor::log( 'running set_settings()' );

        if ( is_array( $settings ) && isset( $settings['plugin']['version'] ) ) {
            
            // Clean settings against the template minimum settings
            $clean_settings = $this->clean_settings( $settings );
            
            // Update DB
            $results = update_option( SECSAFE_OPTIONS, $clean_settings );

            if ( $results ) {

                //Janitor::log( 'Settings have been updated.' );

                //Update Plugin Variable
                $this->settings = $this->get_settings();

                return true;

            } else {

                //Janitor::log( 'ERROR: Settings were not updated.', __FILE__, __LINE__ );

                return false;

            } // $results

        } else {

            if ( ! isset( $settings['plugin']['version'] ) ) {

                //Janitor::log( 'ERROR: Settings variable is not formatted properly. Settings not updated.', __FILE__, __LINE__ );
            
            } else {

                //Janitor::log( 'ERROR: Settings variable is not an array. Settings not updated.', __FILE__, __LINE__ );
            
            }

            return false;

        } // is_array()

    } // set_settings()


    /**
     * Resets the plugin settings to default configuration.
     * @since  0.2.0
     */  
    protected function reset_settings( $initial = false ) {

        //Janitor::log( 'running reset_settings()' );

        // Keep Plugin Version History
        $plugin_history = ( isset( $this->settings['plugin']['version_history'] ) && $this->settings['plugin']['version_history'] ) ? $this->settings['plugin']['version_history'] : [ SECSAFE_VERSION ];

        if ( ! $initial ) {
            
            $delete = $this->delete_settings();

            if ( ! $delete ) {

                $this->messages[] = [ __( 'Error: Settings could not be deleted [1].', SECSAFE_SLUG ), 3, 0 ];
                return;

            } // ! $delete

        } // ! $initial

        // Get Minimum Settings
        $settings = $this->get_settings_min( $plugin_history );

        $result = $this->set_settings( $settings );

        if ( $result && $initial ) {

            $this->messages[] = [ sprintf( __( '%s settings have been set to the minimum standards.', SECSAFE_SLUG ), SECSAFE_NAME ), 1, 1 ];

        } elseif ( $result && ! $initial ) {

            $this->messages[] = [ __( 'The settings have been reset to default.', SECSAFE_SLUG ), 1, 1 ];

        } elseif ( ! $result ) {

            $this->messages[] = [ __( 'Error: Settings could not be reset. [2]', SECSAFE_SLUG ), 3, 0 ];
        
        } // $result

        //Janitor::log( 'Settings changed to default.' );

    } // reset_settings()


    /**
     * Upgrade settings from an older version
     * @since  1.2.2
     */
    protected function clean_settings( $dirty_settings ){

        // Keep Plugin Version History
        $plugin_history = ( isset( $dirty_settings['plugin']['version_history'] ) && $dirty_settings['plugin']['version_history'] ) ? $dirty_settings['plugin']['version_history'] : [ SECSAFE_VERSION ];

        // Get template for settings
        $min_settings = $this->get_settings_min( $plugin_history );

        // Filtered Settings
        $filtered_settings = [];

        // Filter all non settings values
        foreach ( $min_settings as $key => $value ) {

            foreach ( $value as $k => $v ) {

                if ( isset( $dirty_settings[ $key ][ $k ] ) ) {
                    
                    $filtered_settings[ $key ][ $k ] = $dirty_settings[ $key ][ $k ];

                } else {

                    $filtered_settings[ $key ][ $k ] = '';

                }
                
            } // foreach()

        } // foreach()

        $clean_settings = filter_var_array( $filtered_settings, FILTER_SANITIZE_STRING );

        return $clean_settings;

    } // clean_settings()


    /**
     * Upgrade settings from an older version
     * @since  1.1.0
     */
    protected function upgrade_settings(){

        //Janitor::log( 'Running upgrade_settings()' );

        $settings = $this->settings;
        $upgrade = false;

        // Upgrade Versions
        if ( SECSAFE_VERSION != $settings['plugin']['version'] ) {

            //Janitor::log( 'Upgrading version. ' . SECSAFE_VERSION . ' != ' . $settings['plugin']['version'] );

            $upgrade = true;

            // Add old version to history
            $settings['plugin']['version_history'][] = $settings['plugin']['version'];
            $settings['plugin']['version_history'] = array_unique( $settings['plugin']['version_history'] );
            
            // Update DB To New Version
            $settings['plugin']['version'] = SECSAFE_VERSION;
        
        } // SECSAFE_VERSION

        // Upgrade to version 1.1.0
        if ( isset( $settings['files']['auto_update_core'] ) ) {

            //Janitor::log( 'Upgrading updates for 1.1.0 upgrades.' );

            $upgrade = true;

            // Remove old setting
            unset( $settings['files']['auto_update_core'] );

            if( ! isset( $settings['files']['allow_dev_auto_core_updates'] ) ) {
                $settings['files']['allow_dev_auto_core_updates'] = '0';
            } 

            if( ! isset( $settings['files']['allow_major_auto_core_updates'] ) ) {
                $settings['files']['allow_major_auto_core_updates'] = '0';
            }

            if( ! isset( $settings['files']['allow_minor_auto_core_updates'] ) ) {
                $settings['files']['allow_minor_auto_core_updates'] = '1';
            } 

        } // $settings['auto_update_core']

        if ( $upgrade ) {

            $result = $this->set_settings( $settings ); // Update DB

            if ( $result ) {

                $this->messages[] = [ sprintf( __( '%s: Your settings have been upgraded.', SECSAFE_SLUG ), SECSAFE_NAME ), 0, 1 ];
                //Janitor::log( 'Added upgrade success message.' );

                // Get Settings Again
                $this->settings = $this->get_settings();

            } else {

                $this->messages[] = [ sprintf( __( '%s: There was an error upgrading your settings. We would recommend resetting your settings to fix the issue.', SECSAFE_SLUG ), SECSAFE_NAME ), 3 ];
                //Janitor::log( 'Added upgrade error message.' );

            } // $success

        } // $upgrade

    } // upgrade_settings()


    /**
     * Set settings for a particular settings page
     * @param  $settings_page string of the page posted to
     * @return $settings array of settings in database
     * @since  0.1.0
     */
    protected function post_settings( $settings_page ) {

        //Janitor::log( 'Running post_settings().' );

        $settings_page = strtolower( $settings_page );

        if ( isset( $_POST ) && ! empty( $_POST ) && $settings_page ) {

            $nonce = ( isset( $_POST['_nonce_save_settings'] ) ) ? $_POST['_nonce_save_settings'] : false;

            // Security Check
            if ( ! $nonce || ! wp_verify_nonce( $nonce, SECSAFE_SLUG . '-save-settings' ) ) {

                $this->messages[] = [ __( 'Error: Settings not saved. Your session expired. Please try again.', SECSAFE_SLUG ), 3 ];
                return; // Bail

            }

            //Janitor::log( 'Form was submitted.' );

            //This is sanitized in clean_settings()
            $new_settings = $_POST;

            // Remove submit value
            unset( $new_settings['submit'] );

            // Get settings
            $settings = $this->settings; // Get copy of settings

            $options = $settings[ $settings_page ]; // Get page specific settings

            // Set Settings Array With New Values
            foreach ( $options as $label => $value ) {

                if ( isset( $new_settings[ $label ] ) ) {

                    if ( $options[ $label ] != $new_settings[ $label ] ) {
                        // Set Value
                        //echo "set " . $label . "<br>";
                        $options[ $label ] = $new_settings[ $label ];
                        $same = false;
                    }

                    unset( $new_settings[ $label ] );

                } elseif ( ! isset( $new_settings[ $label ] ) && $options[ $label ] != '0' ) {
                    
                    // Set Value To Default
                    $options[ $label ] = '0';

                } // isset()

            } //endforeach

            // Add New Settings
            if ( ! empty( $new_settings ) ) {

                foreach ( $new_settings as $label => $value ) {

                    $options[ $label ] = $new_settings[ $label ];

                } // foreach()

            } // ! empty()

            // Cleanup Settings
            $settings[ $settings_page ] = $options; // Update page settings

            // Compare New / Old Settings to see if anything actually changed
            if ( $settings == $this->settings ) {

                // Tell user that they were updated, but nothing actually changed
                $this->messages[] = [ __( 'Settings saved.', SECSAFE_SLUG ), 0, 1 ];

            } else {

                // Actually Update Settings
                $success = $this->set_settings( $settings ); // Update DB

                if ( $success ) {

                    $this->messages[] = [ __( 'Your settings have been saved.', SECSAFE_SLUG ), 0, 1 ];
                    //Janitor::log( 'Added success message.' );

                } else {

                    $this->messages[] = [ __( 'Error: Settings not saved.', SECSAFE_SLUG ), 3 ];
                    //Janitor::log( 'Added error message.' );

                } // $success

            } // $same

        } else {

            //Janitor::log( 'Form NOT submitted.' );

        } // $_POST

        //Janitor::log( 'Finished post_settings() for ' . $settings_page );

    } // post_settings()


    /**
     * Retrieves the minimun standard settings. Also used as a template for importing settings.
     * @since  1.2.0
     */ 
    protected function get_settings_min( $plugin_history ) {

        // Privacy ---------------------------------|
        $privacy = [
                        'on' => '1',                                // Toggle on/off all privacy policies.
                        'wp_generator' => '1',
                        'wp_version_admin_footer' => '0',
                        'hide_script_versions' => '0',
                        'http_headers_useragent' => '0',
                    ];

        // Files -----------------------------------|
        $files = [
                        'on' => '1',                                // Toggle on/off all file policies.
                        'DISALLOW_FILE_EDIT' => '1',
                        'version_files_core' => '0',
                        'version_files_plugins' => '0',
                        'version_files_themes' => '0',
                        'allow_dev_auto_core_updates' => '0',
                        'allow_major_auto_core_updates' => '0',
                        'allow_minor_auto_core_updates' => '1',
                        'auto_update_plugin' => '0',
                        'auto_update_theme' => '0',
                        'version_files_core' => '0',
                        'version_files_plugins' => '0',
                        'version_files_themes' => '0',
                    ];

        // Content ---------------------------------|
        $content = [
                        'on' => '1',                                // Toggle on/off all content policies.
                        'disable_text_highlight' => '0',
                        'disable_right_click' => '0',
                        'hide_password_protected_posts' => '0',
                    ];

        // Access ----------------------------------|
        $access = [
                        'on' => '1',                                // Toggle on/off all access policies.
                        'xml_rpc' => '1',
                        'login_errors' => '1',
                        'login_password_reset' => '0',
                        'login_remember_me' => '0',
                        'login_local' => '1',
                    ];

        // Firewall --------------------------------|
        $firewall = [
                        'on' => '1',                                // Toggle on/off all firewall rules.
                        'blacklist_ips' => '1',
                        'whitelist_ips' => '1'
                    ];

        // Backups ---------------------------------|
        $backups = [
                        'on' => '1',                                // Toggle on/off all backup features.
                    ];

        // General Settings ------------------------|
        $general = [
                        'on' => '1',                                // Toggle on/off all policies in the plugin.
                        'security_level' => '1',                    // This is not used yet. Intended as preset security levels for faster configurations.
                        'cleanup' => '0',                           // Remove Settings When Disabled
                        'cache_busting' => '1',                     // Bust cache when removing versions from JS & CSS files
                    ];

        // Plugin Version Tracking -----------------|
        $plugin = [
                        'version' => SECSAFE_VERSION,
                        'version_history' => $plugin_history,
                    ];

        // Set everything in the $settings array
        return [
                        'privacy' => $privacy,
                        'files' => $files,
                        'content' => $content,
                        'access' => $access,
                        'firewall' => $firewall,
                        'backups' => $backups,
                        'general' => $general,
                        'plugin' => $plugin,
                    ];

    } // get_settings_min()


    /**
     * Initializes the plugin.
     * @since  1.8.0
     */ 
    static function init(){

        global $SecuritySafe;

        // Set Session
        $session = Self::get_session();

        $admin_user = false;

        if ( is_admin() ) {

            // Multisite Compatibility
            if ( is_multisite() ){

                // Only Super Admin has the power
                $admin_user = ( isset( $session['user']['roles']['super_admin'] ) ) ? true : false;

            } else {

                $admin_user = ( isset( $session['user']['roles']['administror'] ) || current_user_can( 'manage_options' ) ) ? true : false;

            }
            
        } // is_admin()

        if ( $admin_user ) {

            // Load Admin
            require_once( SECSAFE_DIR_ADMIN . '/Admin.php' );

            $SecuritySafe = new Admin( $session );

        } else {

            $SecuritySafe = new Security( $session );

        }

    } // init()


    /**
     * Get cache_buster value from database
     * @return int
     */ 
    public function get_cache_busting() {

        return ( isset( $this->settings['general']['cache_busting'] ) ) ? (int) $this->settings['general']['cache_busting'] : $this->increase_cache_busting( true );

    } // get_cache_busting()



    /**
     * Increase cache_busting value by 1
     * @param  boolean $return Return cache_busting value if true
     */
    function increase_cache_busting( $return = false ) {

        //Janitor::log( 'Running increase_cache_busting().' );

        $settings = $this->settings;

        $cache_busting = ( isset( $settings['general']['cache_busting'] ) && $settings['general']['cache_busting'] > 0 ) ? (int) $settings['general']['cache_busting'] : 0;

        // Increase Value
        $settings['general']['cache_busting'] = ( $cache_busting > 99 ) ? 1 : $cache_busting + 1; //Increase value

        $result = $this->set_settings( $settings );

        if ( $return && $result ) {

            return $settings['general']['cache_busting'];

        } else if ( $return ) {

            return "0";
            
        }

    } // increase_cache_busting()

    
    /**
     * Clears Cached PHP Functions 
     * @since 1.1.13
     */
    static function clear_php_cache() {
        
        if ( version_compare( PHP_VERSION, '5.5.0', '>=' ) ) {

            if ( function_exists('opcache_reset') ) { 

                opcache_reset(); 
            }

        } else {

            if ( function_exists('apc_clear_cache') ) { 

                apc_clear_cache();
            }

        } // PHP_VERSION

    } // clear_php_cache()


} // Plugin{}
