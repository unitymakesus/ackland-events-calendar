<?php

namespace SovereignStack\SecuritySafe;

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
     * Is the current IP whitelisted?
     * @var bool
     * @since  2.0.0
     */
    protected  $whitelisted = false ;
    /**
     * Is the current IP blacklisted?
     * @var bool
     * @since  2.0.0
     */
    protected  $blacklisted = false ;
    /**
     * Security constructor.
     */
    function __construct( $session )
    {
        // Run parent class constructor first
        parent::__construct( $session );
        //Janitor::log( 'running Security.php' );
        
        if ( isset( $this->settings['general']['on'] ) && $this->settings['general']['on'] == '1' ) {
            // Run All Policies
            $this->firewall();
            $this->access();
            $this->privacy();
            $this->files();
            $this->content();
            //$this->backups();
        }
        
        // $this->settings['general']['on']
    }
    
    // __construct()
    /**
     * Firewall Policies
     * @since  0.2.0
     */
    private function firewall()
    {
        //Janitor::log( 'running firewall().' );
        $firewall = new Firewall();
        
        if ( !$this->logged_in ) {
            // Determine Whitelist / Blacklist
            
            if ( $firewall->is_whitelisted() ) {
                define( 'SECSAFE_WHITELISTED', true );
            } else {
                if ( $firewall->is_blacklisted() ) {
                    define( 'SECSAFE_BLACKLISTED', true );
                }
            }
            
            // Log Logins
            $this->add_firewall_policy( false, 'PolicyLogLogins' );
        }
        
        // Log 404s
        $this->add_firewall_policy( false, 'PolicyLog404s' );
    }
    
    // firewall()
    /**
     * Access Policies
     * @since  0.2.0
     */
    private function access()
    {
        //Janitor::log( 'running access().' );
        $settings = $this->settings['access'];
        
        if ( $settings['on'] == "1" ) {
            // Disable xmlrpc.php
            $this->add_firewall_policy( $settings, 'PolicyXMLRPC', 'xml_rpc' );
            // Check only if not logged in
            
            if ( !$this->logged_in ) {
                // Force Local Login
                $this->add_firewall_policy( $settings, 'PolicyLoginLocal', 'login_local' );
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
    }
    
    // access()
    /**
     * Privacy Policies
     * @since  0.2.0
     */
    private function privacy()
    {
        //Janitor::log( 'running privacy().' );
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
    }
    
    // privacy()
    /**
     * File Policies
     * @since  0.2.0
     */
    private function files()
    {
        //Janitor::log( 'running files().' );
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
    }
    
    // files()
    /**
     * Content Policies
     * @since  0.2.0
     */
    private function content()
    {
        //Janitor::log( 'running content().' );
        $settings = $this->settings['content'];
        $skip = false;
        
        if ( $settings['on'] == "1" ) {
            if ( isset( $this->user['roles']['author'] ) || isset( $this->user['roles']['editor'] ) || isset( $this->user['roles']['administror'] ) || isset( $this->user['roles']['super_admin'] ) ) {
                // Skip Conditional Policies
                $skip = true;
            }
            
            if ( !$skip ) {
                // Disable Text Highlighting
                $this->add_policy( $settings, 'PolicyDisableTextHighlight', 'disable_text_highlight' );
                // Disable Right Click
                $this->add_policy( $settings, 'PolicyDisableRightClick', 'disable_right_click' );
            }
            
            // Hide Password Protected Posts
            $this->add_policy( $settings, 'PolicyHidePasswordProtectedPosts', 'hide_password_protected_posts' );
        }
        
        // $settings['on']
    }
    
    // content()
    /**
     * Backups Policies
     * @since  0.2.0
     */
    private function backups()
    {
        //Janitor::log( 'running backups().' );
        return;
        // Disable functionality
        $settings = $this->settings['backups'];
        if ( $settings['on'] == "1" ) {
            // Security Policies Go Here
        }
        // $settings['on']
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
        $slug = '',
        $plan = ''
    )
    {
        //Janitor::log( 'add policy().' );
        
        if ( $slug == '' || isset( $settings[$slug] ) && $settings[$slug] ) {
            // Include Specific Policy
            require_once SECSAFE_DIR_PRIVACY . '/' . $policy . $plan . '.php';
            //Janitor::log( 'add policy ' . $policy );
            $policy = __NAMESPACE__ . '\\' . $policy;
            new $policy();
            $this->policies[] = $policy;
            //Janitor::log( $policy );
        }
    
    }
    
    // add_policy()
    /**
     * Runs specified firewall policy class then adds it to the policies list.
     * @since  2.0.0
     * @param $plan Is used to distinguish premium files
     */
    private function add_firewall_policy(
        $settings,
        $policy,
        $slug = '',
        $plan = ''
    )
    {
        //Janitor::log( 'add policy().' );
        // Include Specific Policy
        require_once SECSAFE_DIR_FIREWALL . '/' . $policy . $plan . '.php';
        //Janitor::log( 'add policy ' . $policy );
        $policy = __NAMESPACE__ . '\\' . $policy;
        
        if ( isset( $settings[$slug] ) ) {
            // Pass setting value
            new $policy( $settings[$slug] );
        } else {
            new $policy();
        }
        
        $this->policies[] = $policy;
        //Janitor::log( $policy );
    }
    
    // add_firewall_policy()
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
                    //Janitor::log( $slug . ' already defined' );
                }
                
                // !defined()
            } else {
                //Janitor::log( $slug . ': Setting not set.' );
            }
            
            // isset()
        } else {
            //Janitor::log( $slug . ': Problem adding Constant.' );
        }
        
        // is_array()
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
    }

}
// Security()