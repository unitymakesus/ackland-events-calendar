<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class AdminPageBackups
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageBackups extends AdminPage {


    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */  
    protected function set_page() {

        $this->slug = 'security-safe-backups';
        $this->title = 'Backups';
        $this->description = 'It is crucial to backup your website periodically for emergency restoration purposes.';

        $this->tabs[] = array(
            'id' => 'settings',
            'label' => 'Settings',
            'title' => 'Backup Settings',
            'heading' => false,
            'intro' => false,
            'content_callback' => 'tab_settings',
        );

        if ( $this->settings['daily'] == 1 ) {

            $this->tabs[] = array(
                'id' => 'daily',
                'label' => 'Daily',
                'title' => 'Daily Backup Details',
                'heading' => false,
                'intro' => false,
                'content_callback' => 'tab_daily',
            );

        }

        if ( $this->settings['weekly'] == 1 ) {

            $this->tabs[] = array(
                'id' => 'weekly',
                'label' => 'Weekly',
                'title' => 'Weekly Backup Details',
                'heading' => false,
                'intro' => false,
                'content_callback' => 'tab_weekly',
            );

        }

        if ( $this->settings['monthly'] == 1 ) {

            $this->tabs[] = array(
                'id' => 'monthly',
                'label' => 'Monthly',
                'title' => 'Monthly Backup Details',
                'heading' => false,
                'intro' => false,
                'content_callback' => 'tab_monthly',
            );

        }

        $this->tabs[] = array(
            'id' => 'log',
            'label' => 'Log',
            'title' => 'Backup Log',
            'heading' => 'See exactly what happened during the backup process.',
            'intro' => 'The log will truncate to 500 lines once it reaches 100k file size. If you would like to get notifications when backups run, add your email to the <a href="/wp-admin/admin.php?page=security-safe-backups#notifications">notifications area</a> in settings.',
            'content_callback' => 'tab_log',
        );


    } // set_page()


    /**
     * This tab displays file settings.
     * @since  0.2.0
     */ 
    function tab_settings() {

        global $wp_version;

        $html = '';

        // Shutoff Switch - All Security Policies
        $classes = ( $this->settings['on'] ) ? '' : 'notice-warning';
        $rows = $this->form_select( $this->settings, 'Backup Policies', 'on', array( '0' => 'Disabled', '1' => 'Enabled' ), 'If you experience a problem, you may want to temporarily turn off all packup policies at once to troubleshoot the issue. Be sure to clear your cache as well.', $classes );
        $html .= $this->form_table( $rows );
        $classes = '';
        
        // Scheduled Backups
        $html .= $this->form_section( 'Scheduled Backups', 'Active which the frequency of backups.' );
        $rows = $this->form_checkbox( $this->settings, 'Backup Frequency:', 'daily', 'Daily Backups', '' );
        $rows .= $this->form_checkbox( $this->settings, '', 'weekly', 'Weekly Backups', '' );
        $rows .= $this->form_checkbox( $this->settings, '', 'monthly', 'Monthly Backups', '' );
        $html .= $this->form_table( $rows );

        // Storage Settings
        $html .= $this->form_section( 'Storage Settings', '' );
        $rows = $this->form_input( $this->settings, 'Local Location', 'storage_location', 'security-safe', 'This is relative to the wp-content directory. Do not place this location within uploads or any other directory to prevent an infinte backup loop.', 'width:150px' , false );
        $rows .= $this->form_checkbox( $this->settings, 'Remote Backups', 'storage_delete_local', 'Delete Local Copy When Using Remote Backup', 'If you are using a remote backup location, it is redundant to have a local copy also. Check this option to save yourself disk space on your hosting account.' );
        $html .= $this->form_table( $rows );

        // Notifications
        $html .= $this->form_section( 'Notifications', '' );
        $rows = $this->form_input( $this->settings, 'Your Email', 'notifications_email', 'you@email.com', 'Get notifications via email. For multiple emails, just separate each by a comma ",".', 'width:100%' , false );
        $rows .= $this->form_checkbox( $this->settings, 'Get Notifications When:', 'notifications_errors', 'When a backup fails.', '' );
        $rows .= $this->form_checkbox( $this->settings, '', 'notifications_confirmation', 'After a backup successfully runs.', '' );
        $html .= $this->form_table( $rows );

        // Save Button
        $html .= $this->button( 'Save Settings' );

        // Memory Cleanup
        unset ( $rows );

        return $html;

    } // tab_settings()


    /**
     * This tab displays daily scheduled backup settings.
     * @since  1.8.0
     */ 
    function tab_daily() {

        global $wp_version;

        $html = $this->schedule_settings( 'daily' );

        return $html;

    } // tab_daily()


    /**
     * This tab displays weekly scheduled backup settings.
     * @since  1.8.0
     */ 
    function tab_weekly() {

        global $wp_version;

        $html = $this->schedule_settings( 'weekly' );

        return $html;

    } // tab_weekly()


    /**
     * This tab displays monthly scheduled backup settings.
     * @since  1.8.0
     */ 
    function tab_monthly() {

        global $wp_version;

        $html = $this->schedule_settings( 'monthly' );

        return $html;

    } // tab_monthly()


    /**
     * Grabs all the settings for scheduled backup tabs.
     * @since  1.8.0
     */ 
    function schedule_settings( $freq ) {

        global $wp_version;

        $html = '';

        if ( ! $freq ) { return $html; }

        // Shutoff Switch
        $rows = $this->form_select( $this->settings, 'Backup Status', $freq . '_on', array( '0' => 'Paused', '1' => 'Scheduled' ), 'The status is paused by default to prevent it from running before your settings are configured.' );
        $rows .= $this->form_button( 'Run Backup Now', 'link-delete', get_admin_url() . '?page=security-safe-backups&tab=monthly&mannual=1', 'Force this backup process to run now.' );
        $html .= $this->form_table( $rows );

        // File Settings
        $html .= $this->form_section( 'File Settings', 'Choose which files that you would like to backup.' );
        $rows = $this->form_checkbox( $this->settings, 'Backup these:', $freq . '_files_plugins', 'Plugins', '' );
        $rows .= $this->form_checkbox( $this->settings, '', $freq . '_files_theme', 'Active Theme', '' );
        $rows .= $this->form_checkbox( $this->settings, '', $freq . '_files_themes', 'All Themes', '' );
        $rows .= $this->form_checkbox( $this->settings, '', $freq . '_files_uploads', 'Uploads Directory', '' );
        $rows .= $this->form_checkbox( $this->settings, '', $freq . '_files_core', 'Backup Core (including all non wp files/dirs)', '' );
        $rows .= $this->form_checkbox( $this->settings, '', $freq . '_files_wp_content', 'wp-content Directory (excludes plugins, themes, uploads)', '' );
        $rows .= $this->form_input( $this->settings, 'Exclude Files/Dirs', $freq . '_files_exclude', 'wp-content/backups/, readme.html', 'Sepearate each file or directory with a comma ",".', 'width:100%' , false );
        $html .= $this->form_table( $rows );

        // Database Settings
        $html .= $this->form_section( 'Database Settings', 'Choose how you would like to backup your database.' );
        $rows = $this->form_checkbox( $this->settings, '', $freq . '_database_sql', 'SQL File', '' );
        $rows .= $this->form_checkbox( $this->settings, '', $freq . '_database_xml', 'XML Export File', '' );
        $html .= $this->form_table( $rows );

        // Storage Settings
        $html .= $this->form_section( 'Storage Settings', 'Choose where you would like to store your backups.' );
        $rows = $this->form_select( $this->settings, 'Type', $freq . '_storage_type', array('local' => 'Local Backup', 's3' => 'Amazon S3 Bucket'), '' );
        $rows .= $this->form_input( $this->settings, 'Retention', $freq . '_storage_retention', 'wp-content/security-safe/', '', 'width:100%' , false );
        $html .= $this->form_table( $rows );

        // Save Button
        $html .= $this->button( 'Save Settings' );

        // Memory Cleanup
        unset ( $rows );

        return $html;

    } // schedule_settings()


    /**
     * This tab displays backup log on the screen.
     * @since  1.8.0
     */ 
    function tab_log() {

        global $wp_version;

        $log_empty = false;

        $html = '';

        // Get Plugin Settings
        ob_start();

        echo '<textarea id="backup-log" style="width: 100%; height: 250px;">';

        // Determine File Structure
        $content_dir = ( defined( 'WP_CONTENT_DIR' ) ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';

        $log_dir = ( isset( $this->settings['backups']['storage_location'] ) ) ? $this->settings['backups']['storage_location'] : 'security-safe';
        
        $log_dir = $content_dir . '/' . $log_dir;

        // Create Directory
        if ( file_exists( $log_dir ) && is_dir( $log_dir ) ) {

            // Log file
            $log_path = $log_dir . '/backup.log';

            if ( file_exists( $log_path ) ) {

                $log = file_get_contents( $log_path );

                echo $log;

            } else {

                $log_empty = true;

            }

        } else {

            $log_empty = true;

        } // file_exists()

        if ( $log_empty ) { 

            echo "The backup log file is empty. You might not have backups enabled."; 

        }

        echo '</textarea>';

        // Scroll Textarea to bottom
        echo '<script>
        jQuery(document).ready(function($){
            $("#backup-log").scrollTop($("#backup-log")[0].scrollHeight);    
        });
        </script>';

        $html .= ob_get_clean();

        return $html;

    } // tab_log()




} // AdminPageBackups()
