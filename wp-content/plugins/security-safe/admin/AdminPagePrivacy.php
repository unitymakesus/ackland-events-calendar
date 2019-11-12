<?php

namespace SecuritySafe;

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

        $this->slug = 'security-safe-privacy';
        $this->title = 'Privacy';
        $this->description = "Anonymity is one of your fundamental rights. Embody it in principle.";

        $this->tabs[] = array(
            'id' => 'settings',
            'label' => 'Settings',
            'title' => 'Privacy Settings',
            'heading' => false,
            'intro' => false,
            'content_callback' => 'tab_settings',
        );

    } // set_page()


    /**
     * This populates all the metaboxes for this specific page.
     * @since  0.2.0
     */ 
    function tab_settings() {

        $html = '';

        // Shutoff Switch - All Privacy Policies
        $classes = ( $this->settings['on'] ) ? '' : 'notice-warning';
        $rows = $this->form_select( $this->settings, 'Privacy Policies', 'on', array( '0' => 'Disabled', '1' => 'Enabled' ), 'If you experience a problem, you may want to temporarily turn off all privacy policies at once to troubleshoot the issue.', $classes );
        $html .= $this->form_table( $rows );

        // Source Code Versions ================
        $html .= $this->form_section( 'Software Privacy', 'It is important to conceal what versions of software you are using.' );
            
            // WordPress Version
            $classes = '';
            $rows = $this->form_checkbox( $this->settings, 'WordPress Version', 'wp_generator', 'Hide WordPress Version Publicly', 'WordPress leaves little public footprints about the version of your site in multiple places visible to the public. This feature removes the WordPress version from the generator tag and RSS feed.', $classes, false );
            
            $classes = '';
            $rows .= $this->form_checkbox( $this->settings, '', 'wp_version_admin_footer', 'Hide WordPress Version in Admin Footer', 'WordPress places the version number at the bottom of the WP-Admin screen.', $classes, false );
            
            // Script Versions
            $classes = '';
            $rows .= $this->form_checkbox( $this->settings, 'Script Versions', 'hide_script_versions', 'Hide Script Versions', 'This replaces all script versions appended to the enqueued JS and CSS files with the current date (YYYYMMDD).', $classes, false );
            
        $rows .= '<tr><td colspan="2"><i>NOTICE: You can also <a href="admin.php?page=security-safe-files#file-access">deny access to files</a> that disclose software versions.</i></td></tr>';
        $html .= $this->form_table( $rows );

        // Website Privacy ================
        $html .= $this->form_section( 'Website Privacy', 'Do not share unnecessary information about your website.' );
            
            // Website Information
            $classes = '';
            $rows = $this->form_checkbox( $this->settings, 'Website Information', 'http_headers_useragent', 'Make Website Anonymous', 'When checking for updates, WordPress gets access to your current version and your website URL. The default info looks like this: "WordPress/X.X; http://www.example.com" This feature removes your URL address from the information sent.', $classes, false );
        
        $html .= $this->form_table( $rows );

        // Save Button ================
        $html .= $this->button( 'Save Settings' );

        // Memory Cleanup
        unset( $rows );

        return $html;

    } // tab_settings()


} // AdminPagePrivacy()
