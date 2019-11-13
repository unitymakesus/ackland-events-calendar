<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * Class AdminPageGeneral
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageGeneral extends AdminPage
{
    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */
    protected function set_page()
    {
        $plugin_name = SECSAFE_NAME;
        $this->slug = 'security-safe';
        $this->title = sprintf( __( 'Welcome to %s', SECSAFE_SLUG ), $plugin_name );
        $this->description = sprintf( __( 'Thank you for choosing %s to help protect your website.', SECSAFE_SLUG ), $plugin_name );
        $this->tabs[] = [
            'id'               => 'settings',
            'label'            => __( 'Settings', SECSAFE_SLUG ),
            'title'            => __( 'Plugin Settings', SECSAFE_SLUG ),
            'heading'          => __( 'These are the general plugin settings.', SECSAFE_SLUG ),
            'intro'            => '',
            'content_callback' => 'tab_general',
        ];
        $this->tabs[] = [
            'id'               => 'debug',
            'label'            => __( 'Debug', SECSAFE_SLUG ),
            'title'            => __( 'Plugin Information', SECSAFE_SLUG ),
            'heading'          => __( 'This information may be useful when troubleshooting compatibility issues.', SECSAFE_SLUG ),
            'intro'            => '',
            'content_callback' => 'tab_info',
        ];
    }
    
    // set_page()
    /**
     * All General Tab Content
     * @since  0.3.0
     * @return html
     */
    public function tab_general()
    {
        // General Settings ================
        $html = $this->form_section( __( 'General Settings', SECSAFE_SLUG ), false );
        // Shutoff Switch - All Security Policies
        $classes = ( $this->settings['on'] ? '' : 'notice-warning' );
        $rows = $this->form_select(
            $this->settings,
            __( 'All Security Policies', SECSAFE_SLUG ),
            'on',
            [
            '0' => __( 'Disabled', SECSAFE_SLUG ),
            '1' => __( 'Enabled', SECSAFE_SLUG ),
        ],
            __( 'If you experience a problem, you may want to temporarily turn off all security policies at once to troubleshoot the issue. You can temporarily disable each type of policy at the top of each settings tab.', SECSAFE_SLUG ),
            $classes
        );
        // Reset Settings
        $classes = '';
        $rows .= $this->form_button(
            __( 'Reset Settings', SECSAFE_SLUG ),
            'link-delete',
            admin_url( 'admin.php?page=security-safe&reset=1&_nonce_reset_settings=' . wp_create_nonce( SECSAFE_SLUG . '-reset-settings' ) ),
            __( 'Click this button to reset the settings back to default. WARNING: You will lose all configuration changes you have made.', SECSAFE_SLUG ),
            $classes
        );
        // Cleanup Database
        $classes = '';
        $rows .= $this->form_checkbox(
            $this->settings,
            __( 'Cleanup Database When Disabling Plugin', SECSAFE_SLUG ),
            'cleanup',
            __( 'Remove Settings, Logs, and Stats When Disabled', SECSAFE_SLUG ),
            __( 'If you ever decide to permanently disable this plugin, you may want to remove our settings, logs, and stats from the database. WARNING: Do not check this box if you are temporarily disabling the plugin, you will loase all data associated with this plugin.', SECSAFE_SLUG ),
            $classes,
            false
        );
        $html .= $this->form_table( $rows );
        // Save Button
        $html .= $this->button( __( 'Save Settings', SECSAFE_SLUG ) );
        return $html;
    }
    
    // tab_general()
    /**
     * All General Tab Content
     * @since  1.1.0
     * @return html
     */
    public function tab_info()
    {
        // Get Plugin Settings
        $settings = get_option( 'securitysafe_options' );
        $html = '<h3>' . __( 'Current Settings', SECSAFE_SLUG ) . '</h3>
                <table class="wp-list-table widefat fixed striped file-perm-table" cellpadding="10px">
                <thead><tr><th>' . __( 'Policies', SECSAFE_SLUG ) . '</th><th>' . __( 'Setting', SECSAFE_SLUG ) . '</th><th>' . __( 'Value', SECSAFE_SLUG ) . '</th></tr></thead>';
        $labels = [
            'privacy'  => __( 'Privacy', SECSAFE_SLUG ),
            'files'    => __( 'Files', SECSAFE_SLUG ),
            'content'  => __( 'Content', SECSAFE_SLUG ),
            'access'   => __( 'User Access', SECSAFE_SLUG ),
            'firewall' => __( 'Firewall', SECSAFE_SLUG ),
            'backups'  => __( 'Backups', SECSAFE_SLUG ),
            'general'  => __( 'General', SECSAFE_SLUG ),
            'plugin'   => __( 'Plugin', SECSAFE_SLUG ),
        ];
        foreach ( $settings as $label => $section ) {
            if ( $label == 'plugin' ) {
                $html .= '<tr style="background: #e5e5e5;"><td><b>' . strtoupper( $labels[$label] ) . '</b></td><td colspan="2"></td></tr>';
            }
            foreach ( $section as $setting => $value ) {
                if ( $setting != 'version_history' ) {
                    
                    if ( $setting == 'on' ) {
                        $html .= '<tr style="background: #e5e5e5;"><td><b>' . strtoupper( $labels[$label] ) . '</b></td><td>' . esc_html( $setting ) . '</td><td>' . esc_html( $value ) . '</td></tr>';
                    } else {
                        $html .= '<tr><td></td><td>' . esc_html( $setting ) . '</td><td>' . esc_html( $value ) . '</td></tr>';
                    }
                
                }
            }
            // foreach()
        }
        // foreach()
        $html .= '</table>
                <p></p>
                <h3>' . __( 'Installed Plugin Version History', SECSAFE_SLUG ) . '</h3>
                <ul>';
        $history = $settings['plugin']['version_history'];
        foreach ( $history as $past ) {
            $html .= '<li>' . esc_html( $past ) . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

}
// AdminPageGeneral()