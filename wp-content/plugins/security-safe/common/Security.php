<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * Class Security
 * @package SecuritySafe
 */
class Security extends Plugin
{
    /**
     * List of all policies running.
     * @var array
     */
    protected  $policies ;
    /**
     * Security constructor.
     */
    function __construct( $plugin )
    {
        
        if ( is_array( $plugin ) ) {
            // Run parent class constructor first
            parent::__construct( $plugin );
            $this->log( 'running Security.php' );
            
            if ( isset( $this->settings['general']['on'] ) && $this->settings['general']['on'] == '1' ) {
                // Run All Policies
                $this->privacy();
                $this->files();
                $this->content();
                $this->access();
                $this->firewall();
                $this->backups();
            }
            
            // $this->settings['general']['on']
        } else {
            $this->log( 'ERROR: Cannot load plugin. $plugin is not an array in Security.php' );
        }
        
        // Memory Cleanup
        unset( $plugin );
    }
    
    // __construct()
    /**
     * Privacy Policies
     * @since  0.2.0
     */
    private function privacy()
    {
        $this->log( 'running privacy().' );
        $settings = $this->settings['privacy'];
        
        if ( $settings['on'] == "1" ) {
            // Hide WordPress Version
            $this->add_policy( $settings, 'PolicyHideWPVersion', 'wp_generator' );
            if ( is_admin() ) {
                // Hide WordPress Version Admin Footer
                $this->add_policy( $settings, 'PolicyHideWPVersionAdmin', 'wp_version_admin_footer' );
            }
            // Hide Script Versions
            $this->add_policy( $settings, 'PolicyHideScriptVersions', 'hide_script_versions' );
            // Make Website Anonymous
            $this->add_policy( $settings, 'PolicyAnonymousWebsite', 'http_headers_useragent' );
        }
        
        // $settings['on']
        // Memory Cleanup
        unset( $settings );
    }
    
    // privacy()
    /**
     * File Policies
     * @since  0.2.0
     */
    private function files()
    {
        $this->log( 'running files().' );
        global  $wp_version ;
        $settings = $this->settings['files'];
        
        if ( $settings['on'] == '1' ) {
            // Disallow Theme File Editing
            $this->add_constant_policy(
                $settings,
                'PolicyDisallowFileEdit',
                'DISALLOW_FILE_EDIT',
                true
            );
            // Protect WordPress Version Files
            $this->add_policy( $settings, 'PolicyWordPressVersionFiles', 'version_files_core' );
            // Auto Updates: https://codex.wordpress.org/Configuring_Automatic_Background_Updates
            
            if ( version_compare( $wp_version, '3.7.0' ) >= 0 && !defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
                
                if ( !defined( 'WP_AUTO_UPDATE_CORE' ) ) {
                    // Automatic Nightly Core Updates
                    $this->add_filter_bool( $settings, 'PolicyUpdatesCoreDev', 'allow_dev_auto_core_updates' );
                    // Automatic Major Core Updates
                    $this->add_filter_bool( $settings, 'PolicyUpdatesCoreMajor', 'allow_major_auto_core_updates' );
                    // Automatic Minor Core Updates
                    $this->add_filter_bool( $settings, 'PolicyUpdatesCoreMinor', 'allow_minor_auto_core_updates' );
                }
                
                // Automatic Plugin Updates
                $this->add_filter_bool( $settings, 'PolicyUpdatesPlugin', 'auto_update_plugin' );
                // Automatic Theme Updates
                $this->add_filter_bool( $settings, 'PolicyUpdatesTheme', 'auto_update_theme' );
            }
            
            // version_compare()
        }
        
        // $settings['on']
        // Memory Cleanup
        unset( $settings );
    }
    
    // files()
    /**
     * Content Policies
     * @since  0.2.0
     */
    private function content()
    {
        $this->log( 'running content().' );
        $settings = $this->settings['content'];
        
        if ( $settings['on'] == "1" ) {
            // Disable Text Highlighting
            $this->add_policy( $settings, 'PolicyDisableTextHighlight', 'disable_text_highlight' );
            // Disable Right Click
            $this->add_policy( $settings, 'PolicyDisableRightClick', 'disable_right_click' );
            // Hide Password Protected Posts
            $this->add_policy( $settings, 'PolicyHidePasswordProtectedPosts', 'hide_password_protected_posts' );
        }
        
        // $settings['on']
        // Memory Cleanup
        unset( $settings );
    }
    
    // content()
    /**
     * Access Policies
     * @since  0.2.0
     */
    private function access()
    {
        $this->log( 'running access().' );
        $settings = $this->settings['access'];
        
        if ( $settings['on'] == "1" ) {
            // Disable xmlrpc.php
            $this->add_policy( $settings, 'PolicyXMLRPC', 'xml_rpc' );
            // Check only if not logged in
            
            if ( !$this->logged_in ) {
                // Force Local Login
                $this->add_policy( $settings, 'PolicyLoginLocal', 'login_local' );
                // Generic Login Errors
                $this->add_policy( $settings, 'PolicyLoginErrors', 'login_errors' );
                // Disable Login Password Reset
                $this->add_policy( $settings, 'PolicyLoginPasswordReset', 'login_password_reset' );
                // Disable Login Remember Me Checkbox
                $this->add_policy( $settings, 'PolicyLoginRememberMe', 'login_remember_me' );
            }
            
            // ! $this->logged_in
        }
        
        // $settings['on']
        // Memory Cleanup
        unset( $settings );
    }
    
    // access()
    /**
     * Firewall Policies
     * @since  0.2.0
     */
    private function firewall()
    {
        $this->log( 'running firewall().' );
        return;
        // Disable functionality
        $settings = $this->settings['firewall'];
        if ( $settings['on'] == "1" ) {
            // Security Policies Go Here
        }
        // $settings['on']
        // Memory Cleanup
        unset( $settings );
    }
    
    // firewall()
    /**
     * Backups Policies
     * @since  0.2.0
     */
    private function backups()
    {
        $this->log( 'running backups().' );
        return;
        // Disable functionality
        $settings = $this->settings['backups'];
        if ( $settings['on'] == "1" ) {
            // Security Policies Go Here
        }
        // $settings['on']
        // Memory Cleanup
        unset( $settings );
    }
    
    // backups()
    /**
     * Runs specified policy class then adds it to the policies list.
     * @since  0.2.0
     * @param $plan Is used to distinguish premium files
     */
    private function add_policy(
        $settings,
        $policy,
        $slug,
        $plan = ''
    )
    {
        
        if ( isset( $settings[$slug] ) && $settings[$slug] ) {
            // Include Policy
            require_once $this->plugin['dir_policies'] . '/' . $policy . $plan . '.php';
            $policy = __NAMESPACE__ . '\\' . $policy;
            new $policy();
            $this->policies[] = $policy;
            $this->log( $policy );
        }
        
        // Memory Cleanup
        unset(
            $settings,
            $policy,
            $slug,
            $temp
        );
    }
    
    // add_policy()
    /**
     * Adds policy hook and returns a boolean value then adds it to the policies list.
     * @since  0.2.0
     */
    private function add_hook_policy(
        $policy,
        $slug,
        $action,
        $type,
        $value = ''
    )
    {
        
        if ( $policy && $slug && $value != '' ) {
            // Force Specific Actions / types
            $action = ( $action == 'remove' ? $action : 'add' );
            $type = ( $type == 'action' ? $type : 'filter' );
            $hook = $action . '_' . $type;
            
            if ( $hook == 'remove_action' ) {
                $hook( $value, $slug );
            } else {
                $hook( $slug, '__return_' . $value );
            }
            
            // $hook
            $this->policies[] = $policy;
        }
        
        // $policy
        // Memory Cleanup
        unset(
            $policy,
            $slug,
            $action,
            $type,
            $value,
            $hook
        );
    }
    
    // add_hook_policy()
    /**
     * Adds policy constant variable and then adds it to the policies list.
     * @since  0.2.0
     */
    private function add_constant_policy(
        $settings,
        $policy,
        $slug,
        $value = ''
    )
    {
        
        if ( is_array( $settings ) && $policy && $slug && $value ) {
            
            if ( isset( $settings[$slug] ) && $settings[$slug] ) {
                
                if ( !defined( $slug ) ) {
                    define( $slug, true );
                    $this->policies[] = $policy;
                } else {
                    $this->log( $slug . ' already defined' );
                }
                
                // !defined()
            } else {
                $this->log( $slug . ': Setting not set.' );
            }
            
            // isset()
        } else {
            $this->log( $slug . ': Problem adding Constant.' );
        }
        
        // is_array()
        // Memory Cleanup
        unset(
            $settings,
            $policy,
            $slug,
            $value
        );
    }
    
    // add_constant_policy()
    /**
     * Adds a filter with a forced boolean result.
     * @since  0.2.0
     */
    private function add_filter_bool( $settings, $policy, $slug )
    {
        // Get Value
        $value = ( isset( $settings[$slug] ) && $settings[$slug] == '1' ? '__return_true' : '__return_false' );
        // Add Filter
        add_filter( $slug, $value, 1 );
        // Add Policy
        $this->policies[] = $policy . $value;
        // Memory Cleanup
        unset(
            $settings,
            $policy,
            $slug,
            $value
        );
    }
    
    // add_filter_bool()
    /**
     * Throws a 403 Forbidden error to the browser and server. Hopefully the Forbidden will get noticed by firewall automatically.
     * @since  0.2.0
     */
    static function forbidden()
    {
        $this->log( 'running forbidden().' );
        header( 'Status: 403 Forbidden' );
        header( 'HTTP/1.1 403 Forbidden' );
        exit;
    }

}
// Security()