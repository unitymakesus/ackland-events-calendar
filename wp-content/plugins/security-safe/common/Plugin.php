<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class Plugin - Main class for plugin
 * @package SecuritySafe
 */
class Plugin {

    /**
     * Information about the plugin.
     * @var array
     */
    public $plugin = array();


    /**
     * Toggle testing mode on/off.
     * @var boolean
     */
    protected $debug = false;


    /**
     * Security Safe Pro Status
     * @var boolean
     */
    public $pro;


    /**
     * local settings values array.
     * @var array
     */
    protected $settings = array();

    /**
     * Logged In Status
     * @var boolean
     */
    protected $logged_in;


    /**
     * Contains all the admin message values.
     * @var array
     */
    public $messages = array();


    /**
     * Plugin constructor.
     * @since  0.1.0
     */
	function __construct( $plugin = false ) {

        // Get value once
        $this->logged_in = is_user_logged_in();

        // Set Plugin Information
        $this->plugin = $plugin;

        $this->log( 'running __construct() plugin.php' );

        // Add Text Domain For Translations
        load_plugin_textdomain( 'security-safe', false, $this->plugin['dir_lang'] );

        // Retrieve Plugin Settings
        $this->settings = ( empty( $this->settings ) ) ? $this->get_settings() : $this->settings;

        // Check For Upgrades
        $this->upgrade_settings();

        // Cleanup Settings on Plugin Disable
        register_deactivation_hook( $this->plugin['file'], array( $this, 'disable_plugin') );

        // Memory Cleanup
        unset( $plugin );

	} // __construct()


    /**
     * Used to update settings in the database.
     * @return array
     * @since 0.1.0
     */
    protected function get_settings() {

        $this->log( 'running get_settings().' );

        $settings = get_option( $this->plugin['options'] );

        // Set settings initially if they do not exist
        if ( ! isset( $settings['general'] ) ) {

            // Initially Set Settings to Default
            $this->log( 'No version in the database. Initially set settings.' );
            $this->reset_settings( true );

            // Get New Initial Settings
            $settings = get_option( $this->plugin['options'] );

        } 

        return $settings;

    } // get_settings()


    /**
     * Used to remove settings in the database.
     * @return array
     * @since 0.2.0
     */
    protected function delete_settings() {

        $this->log( 'running delete_settings()' );

        // Delete settings
        return delete_option( $this->plugin['options'] );

    } // delete_settings()


    /**
     * Used to update settings in the database.
     * @return  boolean
     * @since 0.1.0
     */
    protected function set_settings( $settings ) {

        $this->log( 'running set_settings()' );

        if ( is_array( $settings ) && isset( $settings['plugin']['version'] ) ) {
            
            $results = update_option( $this->plugin['options'], $settings );

            // Memory Cleanup
            unset( $settings );

            if ( $results ) {

                $this->log( 'Settings have been updated.' );

                //Update Plugin Variable
                $this->settings = $this->get_settings();

                // Memory Cleanup
                unset( $results );

                return true;

            } else {

                $this->log( 'ERROR: Settings were not updated.', __FILE__, __LINE__ );

                // Memory Cleanup
                unset( $results );

                return false;

            } // $results

        } else {

            if ( ! isset( $settings['plugin']['version'] ) ) {

                $this->log( 'ERROR: Settings variable is formatted properly. Settings not updated.', __FILE__, __LINE__ );
            
            } else {

                $this->log( 'ERROR: Settings variable is not an array. Settings not updated.', __FILE__, __LINE__ );
            
            }

            // Memory Cleanup
            unset( $settings );

            return false;

        } // is_array()

    } // set_settings()


    /**
     * Resets the plugin settings to default configuration.
     * @since  0.2.0
     */  
    protected function reset_settings( $initial = false ) {

        $this->log( 'running reset_settings()' );

        // Keep Plugin Version History
        $plugin_history = ( isset( $this->settings['plugin']['version_history'] ) && $this->settings['plugin']['version_history'] ) ? $this->settings['plugin']['version_history'] : array( $this->plugin['version'] );

        if ( ! $initial ) {
            
            $delete = $this->delete_settings();

            if ( ! $delete ) {

                $this->messages[] = array( 'Error: Settings could not be set [1].', 3, 0 );
                return;

            } // ! $delete

        } // ! $initial

        // Get Minimum Settings
        $settings = $this->get_settings_min( $plugin_history );

        $result = $this->set_settings( $settings );

        if ( $result && $initial ) {

            $this->messages[] = array( 'Security Safe settings have been set to the minimum standards.', 1, 1 );

        } elseif ( $result && ! $initial ) {

            $this->messages[] = array( 'The settings have been reset to default.', 1, 1 );

        } elseif ( ! $result ) {

            $this->messages[] = array( 'Error: Settings could not be reset. [2]', 3, 0 );
        
        } // $result

        $this->log( 'Settings changed to default.' );

        // Memory Cleanup
        unset( $privacy, $files, $content, $access, $firewall, $backups, $general, $plugin, $settings, $result, $delete, $plugin_history );

    } // reset_settings()

    /**
     * Upgrade settings from an older version
     * @since  1.1.0
     */
    protected function upgrade_settings(){

        $this->log( 'Running upgrade_settings()' );

        $settings = $this->settings;
        $upgrade = false;

        // Upgrade Versions
        if ( $this->plugin['version'] != $settings['plugin']['version'] ) {

            $this->log( 'Upgrading version. ' . $this->plugin['version'] . ' != ' . $settings['plugin']['version'] );

            $upgrade = true;

            // Add old version to history
            $settings['plugin']['version_history'][] = $settings['plugin']['version'];
            $settings['plugin']['version_history'] = array_unique( $settings['plugin']['version_history'] );
            
            // Update DB To New Version
            $settings['plugin']['version'] = $this->plugin['version'];
        
        } // $this->plugin['version']

        // Upgrade to version 1.1.0
        if ( isset( $settings['files']['auto_update_core'] ) ) {

            $this->log( 'Upgrading updates for 1.1.0 upgrades.' );

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

                $this->messages[] = array( 'Security Safe: Your settings have been upgraded.', 0, 1 );
                $this->log( 'Added upgrade success message.' );

                // Get Settings Again
                $this->settings = $this->get_settings();

            } else {

                $this->messages[] = array( 'Security Safe: There was an error upgrading your settings. We would recommend resetting your settings to fix the issue.', 3 );
                $this->log( 'Added upgrade error message.' );

            } // $success

        } // $upgrade

        // Memory Cleanup
        unset( $settings, $upgrade );

    } // upgrade_settings()

    /**
     * Sanitize Data before placing it in the database
     * @return $settings array of settings in database
     * @since  0.1.0
     */
    protected function post_settings( $settings_page ) {

        $this->log( 'Running post_settings().' );

        $settings_page = strtolower( $settings_page );

        if ( isset( $_POST ) && ! empty( $_POST ) ) {

            $this->log( 'Form was submitted.' );

            // Sanitized Posted Settings
            $new_settings = filter_var_array( $_POST, FILTER_SANITIZE_STRING );

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

            // Compare New / Old Settings
            if ( $settings == $this->settings ) {

                $this->messages[] = array( 'Settings saved.', 0, 1 );

            } else {

                // Update Settings
                $success = $this->set_settings( $settings ); // Update DB

                if ( $success ) {

                    $this->messages[] = array( 'Your settings have been saved.', 0, 1 );
                    $this->log( 'Added success message.' );

                } else {

                    $this->messages[] = array( 'There was an error. Settings not saved.', 3 );
                    $this->log( 'Added error message.' );

                } // $success

                // Memory Cleanup
                unset( $success );

            } // $same

            // Memory Cleanup
            unset( $new_settings, $settings, $options, $same, $label, $value );

        } else {

            $this->log( 'Form NOT submitted.' );

        } // $_POST

        $this->log( 'Finished post_settings() for ' . $settings_page );

    } // post_settings()

    /**
     * Retrieves the minimun standard settings. Also used as a template for importing settings.
     * @since  1.2.0
     */ 
    protected function get_settings_min( $plugin_history ) {

        // Privacy ---------------------------------|
        $privacy = array(
                        'on' => '1',                                // Toggle on/off all privacy policies.
                        'wp_generator' => '1',
                        'wp_version_admin_footer' => '0',
                        'hide_script_versions' => '0',
                        'http_headers_useragent' => '0',
                    );

        // Files -----------------------------------|
        $files = array(
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
                    );

        // Content ---------------------------------|
        $content = array(
                        'on' => '1',                                // Toggle on/off all content policies.
                        'disable_text_highlight' => '0',
                        'disable_right_click' => '0',
                        'hide_password_protected_posts' => '0',
                    );

        // Access ----------------------------------|
        $access = array(
                        'on' => '1',                                // Toggle on/off all access policies.
                        'xml_rpc' => '0',
                        'login_errors' => '1',
                        'login_password_reset' => '0',
                        'login_remember_me' => '0',
                        'login_local' => '0',
                    );

        // Firewall --------------------------------|
        $firewall = array(
                        'on' => '1',                                // Toggle on/off all firewall rules.
                    );

        // Backups ---------------------------------|
        $backups = array(
                        'on' => '1',                                // Toggle on/off all backup features.
                    );

        // General Settings ------------------------|
        $general = array(
                        'on' => '1',                                // Toggle on/off all policies in the plugin.
                        'security_level' => '1',                    // This is not used yet. Intended as preset security levels for faster configurations.
                        'cleanup' => '0',                           // Remove Settings When Disabled
                        'cache_busting' => '1',                     // Bust cache when removing versions from JS & CSS files
                    );

        // Plugin Version Tracking -----------------|
        $plugin = array(
                        'version' => $this->plugin['version'],
                        'version_history' => $plugin_history,
                    );

        // Memory Cleanup
        unset( $plugin_history );

        // Set everything in the $settings array
        return array(
                        'privacy' => $privacy,
                        'files' => $files,
                        'content' => $content,
                        'access' => $access,
                        'firewall' => $firewall,
                        'backups' => $backups,
                        'general' => $general,
                        'plugin' => $plugin,
                    );

    } // get_settings_min()


    /**
     * Removes the global variable for the plugin after PHP is done executing.
     * @since  0.2.0
     */ 
    static function shutdown(){

        global $SecuritySafe;

        // Memory Cleanup
        unset( $SecuritySafe );

    } // shutdown()


    /**
     * Initializes the plugin.
     * @since  1.8.0
     */ 
    static function init(){

        global $SecuritySafe;

        $admin_user = false;

        if ( is_admin() ) {

            // Multisite Compatibility
            if ( is_multisite() ){

                $admin_user = ( is_super_admin() ) ? true : false;

            } else {

                $admin_user = ( current_user_can( 'manage_options' ) ) ? true : false;

            }
            
        } // is_admin()

        // Initialize Plugin
        $init = __NAMESPACE__ . '\\';
        $init .= ( $admin_user ) ? 'Admin' : 'Security';

        // Load Security Policies
        require_once( __DIR__ . '/Security.php' );

        if ( $admin_user ) {

            // Load Admin
            require_once( dirname( __DIR__ ) . '/admin/Admin.php' );

        }
        
        // Pass Plugin Variables
        $SecuritySafe = new $init( $SecuritySafe );

        // Memory Cleanup
        unset( $init, $admin_user );

    } // init()


    /**
     * Removes the settings from the database on plugin deactivation
     * @since  0.3.5
     */
    public function disable_plugin() {

        $this->log( 'Running disable_plugin().' );

        if( isset( $this->settings['general']['cleanup'] ) && $this->settings['general']['cleanup'] == '1' ) {

            $this->delete_settings();

        } // isset()

    } // disable_plugin()


    /**
     * Get cache_buster value from database
     * @return int
     */ 
    public function get_cache_busting() {

        $this->log( 'Running get_cache_busting().' );

        return ( isset( $this->settings['general']['cache_busting'] ) ) ? (int) $this->settings['general']['cache_busting'] : $this->increase_cache_busting(true);

    } // get_cache_busting()



    /**
     * Increase cache_busting value by 1
     * @param  boolean $return Return cache_busting value if true
     */
    function increase_cache_busting( $return = false ) {

        $this->log( 'Running increase_cache_busting().' );

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


    /**
     * Writes to debug.log for troubleshooting
     * @param string $message Message entered into the log
     * @param string $file Location of the file where the error occured
     * @param string $line Line number of where the error occured
     * @return void
     * @since 0.1.0
     */
     function log( $message = false, $file = false, $line = false ) {

        if ( ! $this->debug ) { return; }

        $message = ( $message ) ? $message : 'Error: Log Message not defined!';
        $message .= ( $file && $line ) ? ' - ' . 'Occurred on line ' . $line . ' in file ' . $file : '';

        error_log( date( 'Y-M-j h:m:s' ) . " - " . $message . "\n", 3, $this->plugin['dir'] . '/debug.log' );

        // Memory Cleanup
        unset( $message, $file, $line );

    } // log()


    /**
     * Writes to backup.log for troubleshooting backup issues
     * @param string $message Message entered into the log
     * @param string $file Location of the file where the error occured
     * @param string $line Line number of where the error occured
     * @return void
     * @since 1.8.0
     */
     function log_backup( $message, $file = false, $line = false ) {

        // Determine File Structure
        $content_dir = ( defined( 'WP_CONTENT_DIR' ) ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';

        $log_dir = ( isset( $this->settings['backups']['storage_location'] ) ) ? $this->settings['backups']['storage_location'] : 'security-safe';
        
        $log_dir = $content_dir . '/' . $log_dir;

        // Create Directory
        if ( ! file_exists( $log_dir ) && ! is_dir( $log_dir ) ) {

            mkdir( $log_dir, 0750);

        } 

        $index = $log_dir . '/index.html';

        // Prevent Snooping
        if ( ! file_exists( $index ) ) {

            file_put_contents( $index, "");

        } 

        // Keep this directory secure
        chmod( $log_dir, 0750 );
        chmod( $index, 0640 );

        // Name of log file
        $filename = 'backup.log';

        // Log message in the log file
        $activity_log_path = $log_dir . '/' . $filename;

        // Remove Duplicate Slashes
        $activity_log_path = str_replace( '//', '/', $activity_log_path );

        $datestamp = date( 'Y-M-j h:m:s' );
        $message = ( $message ) ? $message : 'Error: Log Message not defined!';

        if ( $file && $line ) {

            $message .= ' - ' . 'Occurred on line ' . $line . ' in file ' . $file;

        } // $file

        $filesize = filesize( $activity_log_path );

        // Make log file smaller
        if ( $filesize > 100000) { // 100k file size max

            // Count lines
            $text = file_get_contents( $activity_log_path );
            $lines = explode( "\n", $text );
            $count = count( $lines );
            $keep = 500; // lines to keep

            if ( $count > $keep ){

                $start = $count - $keep;

                // Truncate Lines
                $lines = array_slice( $lines, $start );
                $text = implode( "\n", $lines );

                // Write retained lines back to file
                file_put_contents( $activity_log_path, $text );

                // Add notice to log
                error_log( $datestamp . " - " . "Downsized log to " . $keep . " lines. \n", 3, $activity_log_path );

            }

        } // $filesize

        $activity_log = $datestamp . " - " . $message . ' / ' . $filesize . '/' . $count . "\n";

        // Add to log
        error_log( $activity_log, 3, $activity_log_path );

        // Keep secure
        chmod( $activity_log_path, 0640);

        // Memory Cleanup
        unset( $activity_log_path, $datestamp, $message, $file, $line, $activity_log );

        // Memory Cleanup
        unset( $filename );

    } // log_backup()

} // Plugin()
