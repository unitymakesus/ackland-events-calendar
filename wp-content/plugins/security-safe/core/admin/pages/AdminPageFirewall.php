<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class AdminPageFirewall
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageFirewall extends AdminPage {


    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */  
    protected function set_page() {

        $this->slug         = 'security-safe-firewall';
        $this->title        = __( 'Firewall', SECSAFE_SLUG );
        $this->description  = __( 'This area provides you the ability to log activity on the site and block future attempts if desired.', SECSAFE_SLUG );

        /**
         * @todo  Add auto blocking settings
        $this->tabs[] = [
            'id' => 'settings',
            'label' => __( 'Settings', SECSAFE_SLUG ),
            'title' => __( 'Firewall Settings', SECSAFE_SLUG ),
            'heading' => false,
            'intro' => false,
            'content_callback' => 'tab_settings',
        ];
        */

        $this->tabs[] = [
            'id'                => 'blocked',
            'label'             => __( 'Threats', SECSAFE_SLUG ),
            'title'             => __( 'Detected Threats', SECSAFE_SLUG ),
            'heading'           => false,
            'intro'             => false,
            'classes'           => [ 'full' ],
            'content_callback'  => 'tab_threats',
        ];

        $this->tabs[] = [
            'id'                => 'allow_deny',
            'label'             => __( 'Allow / Deny IP', SECSAFE_SLUG ),
            'title'             => __( 'Allow / Deny IP Addresses', SECSAFE_SLUG ),
            'heading'           => false,
            'intro'             => false,
            'classes'           => [ 'full' ],
            'content_callback'  => 'tab_allow_deny_ips',
        ];


    } // set_page()


    /**
     * This tab displays firewall settings.
     * @since  2.0.0
     */ 
    function tab_settings() {

        $html = '';

        // Shutoff Switch - All Privacy Policies
        $classes = ( $this->settings['on'] ) ? '' : 'notice-warning';

        $rows = $this->form_select( 
            $this->settings, 
            __( 'Firewall Policies', SECSAFE_SLUG ), 
            'on', 
            [ '0' => __( 'Disabled', SECSAFE_SLUG ), '1' => __( 'Enabled', SECSAFE_SLUG ) ], 
            __( 'If you experience a problem, you may want to temporarily turn off all firewall policies at once to troubleshoot the issue.', SECSAFE_SLUG ), 
            $classes );

        $html .= $this->form_table( $rows );

        // Block Activity ================
        $html .= $this->form_section( 
            __( 'Block Activity', SECSAFE_SLUG ), 
            __( 'Block activity that meets the situations below:', SECSAFE_SLUG ) 
        );
            
            // 5-Min Block
            $classes = '';

            $rows = $this->form_checkbox( 
                $this->settings, 
                __( '5-min Login Block', SECSAFE_SLUG ), 
                'block_login_5min', 
                __( '5 Failed Login Attempts', SECSAFE_SLUG ), 
                __( 'The firewall blocks the IP address after 5 failed login attempts within the past 2 minute.', SECSAFE_SLUG ), 
                $classes, 
                false );

            // 24-Hr Block
            $classes = '';

            $rows .= $this->form_checkbox( 
                $this->settings, 
                __( '24-hr Login Block', SECSAFE_SLUG ), 
                'block_login_24hr', 
                __( 'The third 5-min Block', SECSAFE_SLUG ), 
                __( 'If the firewall finds a total of three 5-minute login blocks within the past hour, it will block the IP address for the next 24 hours.', SECSAFE_SLUG ), 
                $classes, 
                false );

            // 30-Day Block
            $classes = '';

            $rows .= $this->form_checkbox( 
                $this->settings, 
                __( '30-day', SECSAFE_SLUG ), 
                'block_login_30day', 
                __( 'The third 24-hr Block', SECSAFE_SLUG ), 
                __( 'The third 24-hr login block within the past 3 days becomes a 30-day block.', SECSAFE_SLUG ), 
                $classes, 
                false );
            
        $html .= $this->form_table( $rows );

        // Save Button ================
        $html .= $this->button( __( 'Save Settings', SECSAFE_SLUG ) );

        return $html;

    } // tab_settings()


    /**
     * This tab displays the IP addresses black and white listed.
     * @since  2.0.0
     */ 
    function tab_allow_deny_ips() {

        require_once( SECSAFE_DIR_ADMIN_TABLES . '/TableAllowDeny.php' );

        ob_start();

        $table = new TableAllowDeny();
            
        $table->add_ip();
        $table->prepare_items();
        $table->check_whitelist();
        $table->extra_tools();
        $table->display();

        return ob_get_clean();

    } // tab_allow_deny_ips()


    /**
     * This tab displays threats and blocks.
     * @since  2.2.0
     */ 
    function tab_threats() {

        require_once( SECSAFE_DIR_ADMIN_TABLES . '/TableBlocked.php' );

        ob_start();

        $table = new TableBlocked();
        $table->prepare_items();
        $table->display_charts();
        $table->search_box( __( 'Search threats', SECSAFE_SLUG ), 'log' );
        $table->display();

        return ob_get_clean();

    } // tab_threats()


} // AdminPageFirewall()
