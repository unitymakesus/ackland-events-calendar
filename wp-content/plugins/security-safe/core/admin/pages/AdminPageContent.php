<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class AdminPageContent
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageContent extends AdminPage {


    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */  
    protected function set_page() {

        $this->slug = 'security-safe-content';

        $this->title = __( 'Content Protection', SECSAFE_SLUG );
        $this->description = __( 'Deter visitors from stealing your content.', SECSAFE_SLUG );

        $this->tabs[] = [
            'id'                => 'settings',
            'label'             => __( 'Settings', SECSAFE_SLUG ),
            'title'             => __( 'Content Settings', SECSAFE_SLUG ),
            'heading'           => false,
            'intro'             => false,
            'content_callback'  => 'tab_settings',
        ];

        $this->tabs[] = [
            'id'                => '404s',
            'label'             => __( '404 Errors', SECSAFE_SLUG ),
            'title'             => __( '404 Error Log', SECSAFE_SLUG ),
            'heading'           => false,
            'intro'             => false,
            'classes'           => [ 'full' ],
            'content_callback'  => 'tab_404s',
        ];

    } // set_page()

    /**
     * This tab displays file settings.
     * @since  0.2.0
     */ 
    function tab_settings() {

        global $wp_version;

        $html = '';

        // Shutoff Switch - All Content Policies
        $classes = ( $this->settings['on'] ) ? '' : 'notice-warning';

        $rows = $this->form_select( 
            $this->settings, 
            __( 'Content Policies', SECSAFE_SLUG ), 
            'on', 
            [ '0' => __( 'Disabled', SECSAFE_SLUG ), '1' => __( 'Enabled', SECSAFE_SLUG ) ], 
            __( 'If you experience a problem, you may want to temporarily turn off all content policies at once to troubleshoot the issue. Be sure to clear your cache as well.', SECSAFE_SLUG ), 
            $classes 
        );

        $html .= $this->form_table( $rows );

        $classes = '';
        
        // Copyright Protection
        $html .= $this->form_section( 
            __( 'Copyright Protection', SECSAFE_SLUG ), 
            __( 'Copyright protection is meant to deter the majority of users from copying your content. These settings do not affect the admin area.', SECSAFE_SLUG ) 
        );

        $rows = $this->form_checkbox( 
            $this->settings, 
            __( 'Highlight Text', SECSAFE_SLUG ), 
            'disable_text_highlight', 
            __( 'Disable Text Highlighting', SECSAFE_SLUG ), 
            __( 'Prevent users from highlighting your content text.', SECSAFE_SLUG ) 
        );

        $rows .= $this->form_checkbox( 
            $this->settings, 
            __( 'Right-Click', SECSAFE_SLUG ), 
            'disable_right_click', 
            __( 'Disable Right-Click', SECSAFE_SLUG ), 
            __( 'Prevent users from right-clicking on your site to save images or copy text.', SECSAFE_SLUG ) 
        );

        $html .= $this->form_table( $rows );

        // Password Protection
        $html .= $this->form_section( 
            __( 'Password Protected Content', SECSAFE_SLUG ), 
            __( 'Sometimes, it is necessary to password protect content for special access without requiring a user to log in. The settings below enhance this WordPress feature.', SECSAFE_SLUG ) 
        );

        $rows = $this->form_checkbox( 
            $this->settings, 
            __( 'Hide Posts', SECSAFE_SLUG ), 
            'hide_password_protected_posts',
            __( 'Hide All Protected Posts', SECSAFE_SLUG ), 
            __( 'Prevent password protected content from being listed in the blog, search results, and any other public areas. (only affects the loop)', SECSAFE_SLUG ) 
        );

        $html .= $this->form_table( $rows );

        $html .= '<p>' . __( '<b>NOTICE:</b> Be sure to clear your cache after changing these settings.', SECSAFE_SLUG ) . '</p>';

        // Save Button
        $html .= $this->button( __( 'Save Settings', SECSAFE_SLUG ) );

        return $html;

    } // tab_settings()


    /**
     * This tab displays the 404 error log.
     * @since  2.0.0
     */ 
    function tab_404s() {

        require_once( SECSAFE_DIR_ADMIN_TABLES . '/Table404s.php' );

        ob_start();

        $table = new Table404s();
        $table->prepare_items();
        $table->display_charts();
        $table->search_box( __( 'Search 404s', SECSAFE_SLUG ), 'log' );
        $table->display();

        return ob_get_clean();

    } // tab_404s()


} // AdminPageContent()
