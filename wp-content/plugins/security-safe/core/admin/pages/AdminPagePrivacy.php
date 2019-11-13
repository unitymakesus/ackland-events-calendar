<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class AdminPagePrivacy
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPagePrivacy extends AdminPage {


    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */  
    protected function set_page() {

        $this->slug             = 'security-safe-privacy';
        $this->title            = __( 'Privacy', SECSAFE_SLUG );
        $this->description      = __( 'Anonymity is one of your fundamental rights. Embody it in principle.', SECSAFE_SLUG );

        $this->tabs[] = [
            'id'                => 'settings',
            'label'             => __( 'Settings', SECSAFE_SLUG ),
            'title'             => __( 'Privacy Settings', SECSAFE_SLUG ),
            'heading'           => false,
            'intro'             => false,
            'content_callback'  => 'tab_settings',
        ];

    } // set_page()


    /**
     * This populates all the metaboxes for this specific page.
     * @since  0.2.0
     */ 
    function tab_settings() {

        $html = '';

        // Shutoff Switch - All Privacy Policies
        $classes = ( $this->settings['on'] ) ? '' : 'notice-warning';

        $rows = $this->form_select( 
            $this->settings, 
            __( 'Privacy Policies', SECSAFE_SLUG ), 
            'on', 
            [ '0' => __( 'Disabled', SECSAFE_SLUG ), '1' => __( 'Enabled', SECSAFE_SLUG ) ], 
            __( 'If you experience a problem, you may want to temporarily turn off all privacy policies at once to troubleshoot the issue.', SECSAFE_SLUG ), 
            $classes );

        $html .= $this->form_table( $rows );

        // Source Code Versions ================
        $html .= $this->form_section( 
            __( 'Software Privacy', SECSAFE_SLUG ), 
            __( 'It is important to conceal what versions of software you are using.', SECSAFE_SLUG ) 
        );
            
            // WordPress Version
            $classes = '';

            $rows = $this->form_checkbox( 
                $this->settings, 
                __( 'WordPress Version', SECSAFE_SLUG ), 
                'wp_generator', 
                __( 'Hide WordPress Version Publicly', SECSAFE_SLUG ), 
                __( 'WordPress leaves little public footprints about the version of your site in multiple places visible to the public. This feature removes the WordPress version from the generator tag and RSS feed.', SECSAFE_SLUG ), 
                $classes, 
                false );
            
            $classes = '';

            $rows .= $this->form_checkbox( 
                $this->settings, 
                '', 
                'wp_version_admin_footer', 
                __( 'Hide WordPress Version in Admin Footer', SECSAFE_SLUG ), 
                __( 'WordPress places the version number at the bottom of the WP-Admin screen.', SECSAFE_SLUG ), 
                $classes, 
                false );
            
            // Script Versions
            $classes = '';

            $rows .= $this->form_checkbox( 
                $this->settings, 
                __( 'Script Versions', SECSAFE_SLUG ), 
                'hide_script_versions', 
                __( 'Hide Script Versions', SECSAFE_SLUG ), 
                __( 'This replaces all script versions appended to the enqueued JS and CSS files with the current date (YYYYMMDD).', SECSAFE_SLUG ), 
                $classes, 
                false );
            
        $rows .= '<tr><td colspan="2">' . sprintf( __( '<i>NOTICE: You can also <a href="%s">deny access to files</a> that disclose software versions.</i>', SECSAFE_SLUG ), admin_url( 'admin.php?page=security-safe-files#file-access' ) ) . '</td></tr>';
        
        $html .= $this->form_table( $rows );

        // Website Privacy ================
        $html .= $this->form_section( 
            __( 'Website Privacy', SECSAFE_SLUG ), 
            __( 'Do not share unnecessary information about your website.', SECSAFE_SLUG ) 
        );
            
            // Website Information
            $classes = '';
            $rows = $this->form_checkbox( 
                $this->settings, 
                __( 'Website Information', SECSAFE_SLUG ), 
                'http_headers_useragent', 
                __( 'Make Website Anonymous', SECSAFE_SLUG ), 
                __( 'When checking for updates, WordPress gets access to your current version and your website URL. The default info looks like this: "WordPress/X.X; https://www.your-website.com" This feature removes your URL address from the information sent.', SECSAFE_SLUG ), 
                $classes, 
                false );
        
        $html .= $this->form_table( $rows );

        // Save Button ================
        $html .= $this->button( __( 'Save Settings', SECSAFE_SLUG ) );

        return $html;

    } // tab_settings()


} // AdminPagePrivacy()
