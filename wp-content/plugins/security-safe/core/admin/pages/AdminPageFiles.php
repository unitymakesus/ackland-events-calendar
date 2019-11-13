<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * Class AdminPageFiles
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageFiles extends AdminPage
{
    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */
    protected function set_page()
    {
        // Fix Permissions
        $this->fix_permissions();
        $this->slug = 'security-safe-files';
        $this->title = __( 'Files & Folders', SECSAFE_SLUG );
        $this->description = __( 'It is essential to keep all files updated and ensure only authorized users can access them.', SECSAFE_SLUG );
        $this->tabs[] = [
            'id'               => 'settings',
            'label'            => __( 'Settings', SECSAFE_SLUG ),
            'title'            => __( 'File Settings', SECSAFE_SLUG ),
            'heading'          => false,
            'intro'            => false,
            'content_callback' => 'tab_settings',
        ];
        $this->tabs[] = [
            'id'               => 'core',
            'label'            => __( 'Core', SECSAFE_SLUG ),
            'title'            => __( 'WordPress Base Directory & Files', SECSAFE_SLUG ),
            'heading'          => __( 'Check to make sure all file permissions set correctly.', SECSAFE_SLUG ),
            'intro'            => __( 'Incorrect directory or file permission values can lead to security vulnerabilities or even plugins or themes not functioning as intended. If you are not sure what values to set for a file or directory, use the standard recommended value.', SECSAFE_SLUG ),
            'classes'          => [ 'full' ],
            'content_callback' => 'tab_core',
        ];
        $this->tabs[] = [
            'id'               => 'theme',
            'label'            => __( 'Theme', SECSAFE_SLUG ),
            'title'            => __( 'Theme Audit', SECSAFE_SLUG ),
            'heading'          => __( 'Check to make sure all theme file permissions set correctly.', SECSAFE_SLUG ),
            'intro'            => __( 'If you use "Secure" permission settings, and experience problems, just set the file permissions back to "Standard."', SECSAFE_SLUG ),
            'classes'          => [ 'full' ],
            'content_callback' => 'tab_theme',
        ];
        $this->tabs[] = [
            'id'               => 'uploads',
            'label'            => __( 'Uploads', SECSAFE_SLUG ),
            'title'            => __( 'Uploads Directory Audit', SECSAFE_SLUG ),
            'heading'          => __( 'Check to make sure all uploaded files have proper permissions.', SECSAFE_SLUG ),
            'intro'            => '',
            'classes'          => [ 'full' ],
            'content_callback' => 'tab_uploads',
        ];
        $tab_plugins_intro = __( 'WordPress sets file permissions to minimum safe values by default when you install or update plugins. You will likely find file permission issues after migrating a site from one server to another. The file permissions for a plugin will get fixed when you perform an update on that particular plugin. We would recommend correcting any issues labeled "bad" immediately, versus waiting for an update.', SECSAFE_SLUG );
        
        if ( security_safe()->is_not_paying() ) {
            $tab_plugins_intro .= '<br /><br /><b>' . __( 'Batch Plugin Permissions', SECSAFE_SLUG ) . '</b> (<a href="' . SECSAFE_URL_MORE_INFO_PRO . '">' . __( 'Pro Feature', SECSAFE_SLUG ) . '</a>) - ' . __( 'You can change all plugin permissions to Standard or Secure permissions with one click.', SECSAFE_SLUG );
            $tab_plugins_intro .= '<br /><br /><b>' . __( 'Prevent Plugin Version Snooping', SECSAFE_SLUG ) . '</b> (<a href="' . SECSAFE_URL_MORE_INFO_PRO . '">' . __( 'Pro Feature', SECSAFE_SLUG ) . '</a>) - ' . __( 'Prevent access to plugin version files.', SECSAFE_SLUG );
            $tab_plugins_intro .= '<br /><br /><b>' . __( 'Maintain Secure Permissions', SECSAFE_SLUG ) . '</b> (<a href="' . SECSAFE_URL_MORE_INFO_PRO . '">' . __( 'Pro Feature', SECSAFE_SLUG ) . '</a>) - ' . __( 'Pro will automatically fix your file permissions after an core, plugin, and theme update.', SECSAFE_SLUG );
        }
        
        $this->tabs[] = [
            'id'               => 'plugins',
            'label'            => __( 'Plugins', SECSAFE_SLUG ),
            'title'            => __( 'Plugins Audit', SECSAFE_SLUG ),
            'heading'          => __( 'When plugin updates run, they will overwrite your permission changes.', SECSAFE_SLUG ),
            'intro'            => $tab_plugins_intro,
            'classes'          => [ 'full' ],
            'content_callback' => 'tab_plugins',
        ];
        $this->tabs[] = [
            'id'               => 'server',
            'label'            => __( 'Server', SECSAFE_SLUG ),
            'title'            => __( 'Server Information', SECSAFE_SLUG ),
            'heading'          => __( "It is your hosting provider's job to keep your server up-to-date.", SECSAFE_SLUG ),
            'intro'            => __( 'This table below will help identify the software versions currently on your hosting server. <br>NOTE: System administrators often do server updates once per month. If something is a version behind, then you might be between update cycles or there may be compatibility issues due to version dependencies.', SECSAFE_SLUG ),
            'classes'          => [ 'full' ],
            'content_callback' => 'tab_server',
        ];
    }
    
    // set_page()
    /**
     * This tab displays file settings.
     * @since  0.2.0
     */
    function tab_settings()
    {
        global  $wp_version ;
        $html = '';
        // Shutoff Switch - All File Policies
        $classes = ( $this->settings['on'] ? '' : 'notice-warning' );
        $rows = $this->form_select(
            $this->settings,
            __( 'File Policies', SECSAFE_SLUG ),
            'on',
            [
            '0' => __( 'Disabled', SECSAFE_SLUG ),
            '1' => __( 'Enabled', SECSAFE_SLUG ),
        ],
            __( 'If you experience a problem, you may want to temporarily turn off all file policies at once to troubleshoot the issue.', SECSAFE_SLUG ),
            $classes
        );
        $html .= $this->form_table( $rows );
        // Automatic WordPress Updates ================
        $rows = '';
        $html .= $this->form_section( __( 'Automatic WordPress Updates', SECSAFE_SLUG ), __( 'Updates are one of the main culprits to a compromised website.', SECSAFE_SLUG ) );
        
        if ( version_compare( $wp_version, '3.7.0' ) >= 0 && !defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
            $disabled = ( defined( 'WP_AUTO_UPDATE_CORE' ) ? true : false );
            $classes = '';
            $rows .= ( $disabled ? $this->form_text( __( '<b>NOTICE:</b> WordPress Automatic Core Updates are being controlled by the constant variable WP_AUTO_UPDATE_CORE in the wp-config.php file or by another plugin. As a result, Automatic Core Update feature settings for this plugin have been disabled.', SECSAFE_SLUG ), 'notice-info' ) : '' );
            $rows .= $this->form_checkbox(
                $this->settings,
                __( 'Dev Core Updates', SECSAFE_SLUG ),
                'allow_dev_auto_core_updates',
                __( 'Automatic Nightly Core Updates', SECSAFE_SLUG ),
                __( 'Select this option if the site is in development only.', SECSAFE_SLUG ),
                $classes,
                $disabled
            );
            $rows .= $this->form_checkbox(
                $this->settings,
                __( 'Major Core Updates', SECSAFE_SLUG ),
                'allow_major_auto_core_updates',
                __( 'Automatic Major Core Updates', SECSAFE_SLUG ),
                __( 'If you feel very confident in your code, you could automate the major version upgrades. (not recommended in most cases)', SECSAFE_SLUG ),
                $classes,
                $disabled
            );
            $rows .= $this->form_checkbox(
                $this->settings,
                __( 'Minor Core Updates', SECSAFE_SLUG ),
                'allow_minor_auto_core_updates',
                __( 'Automatic Minor Core Updates', SECSAFE_SLUG ),
                __( 'This is enabled by default in WordPress and only includes minor version and security updates.', SECSAFE_SLUG ),
                $classes,
                $disabled
            );
            $rows .= $this->form_checkbox(
                $this->settings,
                __( 'Plugin Updates', SECSAFE_SLUG ),
                'auto_update_plugin',
                __( 'Automatic Plugin Updates', SECSAFE_SLUG ),
                $classes,
                false
            );
            $rows .= $this->form_checkbox(
                $this->settings,
                __( 'Theme Updates', SECSAFE_SLUG ),
                'auto_update_theme',
                __( 'Automatic Theme Updates', SECSAFE_SLUG ),
                $classes,
                false
            );
        } else {
            if ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
                $rows .= $this->form_text( __( '<b>NOTICE:</b> WordPress Automatic Updates are disabled by the constant variable AUTOMATIC_UPDATER_DISABLED in the wp-config.php file or by another plugin. As a result, Automatic Update features for this plugin have been disabled.', SECSAFE_SLUG ), 'notice-info' );
            }
            // AUTOMATIC_UPDATER_DISABLED
            if ( version_compare( $wp_version, '3.7.0' ) < 0 ) {
                $rows .= $this->form_text( sprintf( __( '<b>NOTICE:</b> You are using WordPress Version %s. The WordPress Automatic Updates feature controls require version 3.7 or greater.', SECSAFE_SLUG ), $wp_version ), 'notice-info' );
            }
            // version_compare()
        }
        
        // version_compare()
        $html .= $this->form_table( $rows );
        // File Access
        $html .= $this->form_section( __( 'File Access', SECSAFE_SLUG ), false );
        $classes = '';
        $rows = $this->form_checkbox(
            $this->settings,
            __( 'Theme File Editing', SECSAFE_SLUG ),
            'DISALLOW_FILE_EDIT',
            __( 'Disable Theme Editing', SECSAFE_SLUG ),
            __( 'Disable the ability for admin users to edit your theme files from the WordPress admin.', SECSAFE_SLUG ),
            $classes,
            false
        );
        $rows .= $this->form_checkbox(
            $this->settings,
            __( 'WordPress Version Files', SECSAFE_SLUG ),
            'version_files_core',
            __( 'Prevent Access', SECSAFE_SLUG ),
            sprintf( __( 'Prevent access to files that disclose WordPress versions: readme.html and license.txt. <a href="%s">Also, see Software Privacy</a>', SECSAFE_SLUG ), admin_url( 'admin.php?page=security-safe-privacy#software-privacy' ) ),
            $classes,
            false
        );
        
        if ( !security_safe()->can_use_premium_code() ) {
            $rows .= $this->form_checkbox(
                $this->settings,
                __( 'Plugin Version Files', SECSAFE_SLUG ),
                'version_files_plugins',
                sprintf( __( 'Prevent Access (<a href="%1$s">Pro Feature</a>)', SECSAFE_SLUG ), SECSAFE_URL_MORE_INFO_PRO ),
                __( 'Prevent access to files that disclose plugin versions.', SECSAFE_SLUG ),
                $classes,
                true
            );
            $rows .= $this->form_checkbox(
                $this->settings,
                __( 'Theme Version Files', SECSAFE_SLUG ),
                'version_files_themes',
                sprintf( __( 'Prevent Access (<a href="%s">Pro Feature</a>)', SECSAFE_SLUG ), SECSAFE_URL_MORE_INFO_PRO ),
                __( 'Prevent access to files that disclose plugin versions.', SECSAFE_SLUG ),
                $classes,
                true
            );
        }
        
        $html .= $this->form_table( $rows );
        // Save Button
        $html .= $this->button( __( 'Save Settings', SECSAFE_SLUG ) );
        return $html;
    }
    
    // tab_settings()
    /**
     * This tab displays current and suggested file permissions.
     * @since  1.0.3
     */
    function tab_core()
    {
        // Determine File Structure
        $plugins_dir = ( defined( 'WP_PLUGIN_DIR' ) ? WP_PLUGIN_DIR : dirname( dirname( __DIR__ ) ) );
        $content_dir = ( defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content' );
        $muplugins_dir = ( defined( 'WPMU_PLUGIN_DIR' ) ? WPMU_PLUGIN_DIR : $content_dir . '/mu-plugins' );
        $uploads_dir = wp_upload_dir();
        $uploads_dir = $uploads_dir["basedir"];
        $themes_dir = dirname( get_template_directory() );
        // Array of Files To Be Checked
        $paths = [
            $uploads_dir,
            $plugins_dir,
            $muplugins_dir,
            $themes_dir
        ];
        // Remove Trailing Slash
        $base = str_replace( '//', '', ABSPATH . '/' );
        // Get All Files / Folders In Base Directory
        $base = $this->get_dir_files( $base, false );
        // Combine File List
        $paths = array_merge( $base, $paths );
        // Get Rid of Duplicates
        $paths = array_unique( $paths );
        return $this->display_permissions_table( $paths );
    }
    
    // tab_core()
    /**
     * This tab displays current and suggested file permissions.
     * @since  1.0.3
     */
    function tab_theme()
    {
        $theme_parent = get_template_directory();
        $theme_child = get_stylesheet_directory();
        $files = $this->get_dir_files( $theme_parent );
        
        if ( $theme_parent != $theme_child ) {
            // Child Theme Present
            $child_files = $this->get_dir_files( $theme_child );
            $files = array_merge( $child_files, $files );
        }
        
        return $this->display_permissions_table( $files, 'tab_theme' );
    }
    
    // tab_theme()
    /**
     * This tab displays current and suggested file permissions.
     * @since  1.1.0
     */
    function tab_uploads()
    {
        $uploads_dir = wp_upload_dir();
        return $this->display_permissions_table( $this->get_dir_files( $uploads_dir["basedir"] ) );
    }
    
    // tab_uploads()
    /**
     * This tab displays current and suggested file permissions.
     * @since  1.0.3
     */
    function tab_plugins()
    {
        $plugins_dir = ( defined( 'WP_PLUGIN_DIR' ) ? WP_PLUGIN_DIR : dirname( dirname( __DIR__ ) ) );
        return $this->display_permissions_table( $this->get_dir_files( $plugins_dir ), 'tab_plugins' );
    }
    
    // tab_plugins()
    /**
     * This tab displays software installed on the server.
     * @since  1.0.3
     */
    function tab_server()
    {
        $html = '';
        // Latest Versions
        $latest_versions = [];
        // https://endoflife.software/programming-languages/server-side-scripting/php
        // https://secure.php.net/ChangeLog-7.php
        $latest_versions['PHP'] = [
            '7.3.0' => '7.3.10',
            '7.2.0' => '7.2.23',
            '7.1.0' => '7.1.32',
        ];
        $php_min = '7.1.0';
        $ok = [];
        $ok['php'] = false;
        $bad = [];
        $bad['php'] = false;
        $PHP_VERSION = ( defined( 'PHP_VERSION' ) ? PHP_VERSION : false );
        //$PHP_VERSION = '7.2.16'; // test only
        $notice_class = '';
        $html .= '
            <table class="wp-list-table widefat fixed striped file-perm-table" cellpadding="10px">
                <thead>
                    <tr>
                        <th class="manage-column">' . __( 'Description', SECSAFE_SLUG ) . '</th>
                        <th class="manage-column" style="width: 250px;">' . __( 'Current Version', SECSAFE_SLUG ) . '</th>
                        <th class="manage-column" style="width: 250px;">' . __( 'Recommend', SECSAFE_SLUG ) . '</th>
                        <th class="manage-column" style="width: 75px;">' . __( 'Status', SECSAFE_SLUG ) . '</th>
                    </tr>
                </thead>';
        $versions = [];
        // PHP Version
        
        if ( $PHP_VERSION ) {
            $status = '';
            $recommend = '';
            
            if ( in_array( $PHP_VERSION, $latest_versions['PHP'] ) ) {
                // PHP Version Is Secure
                $status = __( 'Secure', SECSAFE_SLUG );
                $recommend = $PHP_VERSION;
            } else {
                
                if ( version_compare( $PHP_VERSION, $php_min, '<' ) ) {
                    // This Version Is Vulnerable
                    $status = __( 'Bad', SECSAFE_SLUG );
                    $recommend = $latest_versions['PHP'][$php_min];
                    $bad['php'] = [ $PHP_VERSION, $php_min ];
                    $notice_class = 'notice-error';
                } else {
                    // Needs Update To Latest Secure Patch Version
                    foreach ( $latest_versions['PHP'] as $minor => $patch ) {
                        
                        if ( version_compare( $PHP_VERSION, $minor, '>=' ) ) {
                            
                            if ( $PHP_VERSION >= $patch ) {
                                // Prevent us from recommending a lower version
                                $status = __( 'Secure', SECSAFE_SLUG );
                                $recommend = $PHP_VERSION;
                            } else {
                                $status = __( 'OK', SECSAFE_SLUG );
                                $recommend = $patch;
                                $ok['php'] = [ $PHP_VERSION, $patch ];
                                $notice_class = 'notice-warning';
                            }
                            
                            break;
                        }
                    
                    }
                    // foreach()
                }
            
            }
            
            // endif
            $versions[] = [
                'name'      => 'PHP',
                'current'   => $PHP_VERSION,
                'recommend' => $recommend,
                'status'    => $status,
                'class'     => $notice_class,
            ];
        }
        
        // PHP_VERSION
        // Get All Versions From phpinfo
        $phpinfo = $this->get_phpinfo( 8 );
        
        if ( !empty($phpinfo) ) {
            foreach ( $phpinfo as $name => $section ) {
                foreach ( $section as $key => $val ) {
                    
                    if ( strpos( strtolower( $key ), 'version' ) !== false && strpos( strtolower( $key ), 'php version' ) === false ) {
                        
                        if ( is_array( $val ) ) {
                            $current = $val[0];
                        } elseif ( is_string( $key ) ) {
                            $current = $val;
                        }
                        
                        // is_[]
                        // Remove Duplicate Text
                        $name = $name . ': ' . str_replace( $name, '', $key );
                        $versions[] = [
                            'name'      => $name,
                            'current'   => $current,
                            'recommend' => '-',
                            'status'    => '-',
                            'class'     => '',
                        ];
                    }
                    
                    // strpos()
                }
                // foreach()
            }
            // foreach()
        }
        
        // ! empty()
        // Display All Version
        foreach ( $versions as $v ) {
            $html .= '<tr class="' . esc_html( $v['class'] ) . '">
                        <td>' . esc_html( $v['name'] ) . '</td>
                        <td style="text-align: center;">' . esc_html( $v['current'] ) . '</td>
                        <td style="text-align: center;">' . esc_html( $v['recommend'] ) . '</td>
                        <td ' . strtolower( esc_html( $v['status'] ) ) . '" style="text-align: center;">' . esc_html( $v['status'] ) . '</td>
                        </tr>';
        }
        // foreach
        // If phpinfo is disabled, display notice
        if ( empty($phpinfo) ) {
            $html .= '<tr><td colspan="4">' . sprintf( __( 'The phpinfo() function is disabled. You may need to contact the hosting provider to enable this function for more advanced version details. <a href="%s">See the documentation.</a>', SECSAFE_SLUG ), 'https://php.net/manual/en/function.phpinfo.php' ) . '</td></tr>';
        }
        // ! empty()
        $html .= '</table>';
        // Display Notices
        $this->display_notices_perms( false, $ok, $bad );
        return $html;
    }
    
    // tab_server()
    /**
     * Returns phpinfo as an array
     * @since  1.0.3
     */
    private function get_phpinfo( $type = 1 )
    {
        ob_start();
        phpinfo( $type );
        $phpinfo = [];
        $pattern = '#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\\s*</t[hd]>)?)?</tr>)#s';
        
        if ( preg_match_all(
            $pattern,
            ob_get_clean(),
            $matches,
            PREG_SET_ORDER
        ) ) {
            foreach ( $matches as $m ) {
                
                if ( strlen( $m[1] ) ) {
                    $phpinfo[$m[1]] = [];
                } else {
                    $keys = array_keys( $phpinfo );
                    
                    if ( isset( $m[3] ) ) {
                        $phpinfo[end( $keys )][$m[2]] = ( isset( $m[4] ) ? [ $m[3], $m[4] ] : $m[3] );
                    } else {
                        $phpinfo[end( $keys )][] = $m[2];
                    }
                    
                    // isset()
                }
                
                // strlen()
            }
            // foreach()
        }
        
        // preg_match_all()
        return $phpinfo;
    }
    
    // get_phpinfo()
    /**
     * Display all file permissions in a table
     * @param  array $paths An array of absolute paths
     * @param  string $tab Tab identification: used to determine features for one tab versus another.
     * @since  1.0.3
     */
    private function display_permissions_table( $paths = false, $tab = false )
    {
        $html = '';
        $tr_bad = '';
        $tr_ok = '';
        $tr_good = '';
        $tr_secure = '';
        $table = '
            <table class="wp-list-table widefat fixed striped file-perm-table">
                <thead>
                    <tr>
                        <th class="manage-column">' . __( 'Relative Location', SECSAFE_SLUG ) . '</th>
                        <th class="manage-column" style="width: 100px;">' . __( 'Type', SECSAFE_SLUG ) . '</th>
                        <th class="manage-column" style="width: 75px;">' . __( 'Current', SECSAFE_SLUG ) . '</th>
                        <th class="manage-column" style="width: 70px;">' . __( 'Status', SECSAFE_SLUG ) . '</th>
                        <th class="manage-column" style="width: 160px;">' . __( 'Modify', SECSAFE_SLUG ) . '</th>
                    </tr>
                </thead>';
        $show_row = false;
        
        if ( is_array( $paths ) && !empty($paths) ) {
            $file_count = 0;
            $good = [];
            $good['dirs'] = 0;
            $good['files'] = 0;
            $bad = [];
            $bad['dirs'] = 0;
            $bad['files'] = 0;
            $ok = [];
            $ok['dirs'] = 0;
            $ok['files'] = 0;
            foreach ( $paths as $p ) {
                
                if ( file_exists( $p ) ) {
                    // Get Relative Path
                    $rel_path = str_replace( [ ABSPATH, '//' ], '/', $p );
                    // Get File Type
                    $is_dir = is_dir( $p );
                    // Get Details of Path
                    $info = @stat( $p );
                    $permissions = sprintf( '%o', $info['mode'] );
                    // Get all info about permissions
                    $current = substr( $permissions, -3 );
                    // Get current o/g/w permissions
                    $perm = str_split( $current );
                    // Convert permissions to an array
                    // Specific Role Permissions
                    $owner = ( isset( $perm[0] ) ? $perm[0] : 0 );
                    $group = ( isset( $perm[1] ) ? $perm[1] : 0 );
                    $world = ( isset( $perm[2] ) ? $perm[2] : 0 );
                    $notice_class = '';
                    
                    if ( $rel_path == '/' ) {
                        $type = 'directory';
                        $status = 'default';
                    } else {
                        // Determine Directory or File
                        
                        if ( $is_dir ) {
                            $type = 'directory';
                            $min = '775';
                            // Standard
                            $sec = $this->get_secure_perms( $p, 'dir' );
                            
                            if ( $current == $min || $current == $sec ) {
                                $status = ( $current == $sec ? 'secure' : 'good' );
                                if ( !security_safe()->can_use_premium_code() ) {
                                    
                                    if ( $status == 'good' && ($tab != 'tab_plugins' && $tab != 'tab_theme') ) {
                                        $good['dirs'] = $good['dirs'] + 1;
                                        $notice_class = 'notice-info';
                                    }
                                
                                }
                            } else {
                                // Ceiling
                                $status = ( $world > 5 ? 'bad' : 'ok' );
                                
                                if ( $status == 'bad' ) {
                                    $bad['dirs'] = $bad['dirs'] + 1;
                                    $notice_class = 'notice-error';
                                } else {
                                    $ok['dirs'] = $ok['dirs'] + 1;
                                    $notice_class = 'notice-warning';
                                }
                            
                            }
                            
                            // $current
                        } else {
                            $type = 'file';
                            $min = '644';
                            // Standard
                            $sec = $this->get_secure_perms( $p, 'file' );
                            
                            if ( $current == $min || $current == $sec ) {
                                
                                if ( $min == $sec ) {
                                    $status = 'secure';
                                } else {
                                    $status = ( $current == $sec ? 'secure' : 'good' );
                                    if ( !security_safe()->can_use_premium_code() ) {
                                        
                                        if ( $status == 'good' && ($tab != 'tab_plugins' && $tab != 'tab_theme') ) {
                                            $good['files'] = $good['files'] + 1;
                                            $notice_class = 'notice-info';
                                        }
                                    
                                    }
                                }
                            
                            } else {
                                // Ceiling
                                $status = ( $owner > 6 || $group > 4 || $world > 4 ? 'bad' : 'ok' );
                                // Floor
                                $status = ( $owner < 4 || $group < 0 || $world < 0 ? 'bad' : $status );
                                
                                if ( $status == 'bad' ) {
                                    $bad['files'] = $bad['files'] + 1;
                                    $notice_class = 'notice-error';
                                } else {
                                    $ok['files'] = $ok['files'] + 1;
                                    $notice_class = 'notice-warning';
                                }
                            
                            }
                            
                            // $current
                        }
                        
                        // $is_dir
                        // Create Standard Option
                        $option_min = ( $status != 'good' && $min != $current ? '<option value="' . esc_html( $min ) . '|' . esc_html( $rel_path ) . '">' . esc_html( $min ) . ' - ' . __( 'Standard', SECSAFE_SLUG ) . '</option>' : false );
                        if ( !security_safe()->can_use_premium_code() ) {
                            
                            if ( $tab != 'tab_plugins' && $tab != 'tab_theme' ) {
                                // Create Secure Option
                                $option_sec = ( $status != 'secure' ? '<option value="' . esc_html( $sec ) . '|' . esc_html( $rel_path ) . '">' . esc_html( $sec ) . ' - ' . __( 'Secure', SECSAFE_SLUG ) . '</option>' : false );
                                $option_sec = ( $min == $sec ? false : $option_sec );
                            } else {
                                $option_sec = false;
                            }
                        
                        }
                        $show_row = true;
                        
                        if ( $option_min || $option_sec ) {
                            $file_count++;
                            // Create Select Dropdown
                            $select = '<select name="file-' . esc_html( $file_count ) . '"><option value="-1"> -- ' . __( 'Select One', SECSAFE_SLUG ) . ' -- </option>';
                            $select .= ( $option_min ? $option_min : '' );
                            $select .= ( $option_sec ? $option_sec : '' );
                            $select .= '</select>';
                        } else {
                            $select = '-';
                        }
                        
                        // $option_min
                    }
                    
                    // $rel_path
                    
                    if ( $show_row ) {
                        $groups = '<tr class="' . esc_html( $notice_class ) . '">
                                        <td>' . esc_html( $rel_path ) . '</td>
                                        <td style="text-align: center;">' . esc_html( $type ) . '</td>
                                        <td style="text-align: center;">' . esc_html( $owner . $group . $world ) . '</td>
                                        <td class="' . strtolower( esc_html( $status ) ) . '" style="text-align: center;">' . Self::display_status( $status ) . '</td>';
                        $groups .= ( $rel_path == '/' ? '<td style="text-align: center;"> - </td>' : '<td style="text-align: center;">' . $select . '</td>' );
                        $groups .= '</tr>';
                        // Separate types of problems into groups
                        
                        if ( $notice_class == 'notice-error' ) {
                            $tr_bad .= $groups;
                        } else {
                            
                            if ( $notice_class == 'notice-warning' ) {
                                $tr_ok .= $groups;
                            } else {
                                
                                if ( $notice_class == 'notice-info' ) {
                                    $tr_good .= $groups;
                                } else {
                                    $tr_secure .= $groups;
                                }
                            
                            }
                        
                        }
                    
                    }
                    
                    // $show_row
                }
                
                // file_exists()
            }
            // foreach()
        } else {
            $table .= '<tr><td colspan="5">' . __( 'Error: There were not any files to check.', SECSAFE_SLUG ) . '</td></tr>';
        }
        
        // is_[]
        // Display Notices
        $this->display_notices_perms( $good, $ok, $bad );
        // Display Table
        $html .= $table . $tr_bad . $tr_ok . $tr_good . $tr_secure;
        // Show Update Permissions Button
        $html .= '<tr><td colspan="4"></td><td>' . $this->button( __( 'Update Permissions', SECSAFE_SLUG ) ) . '</td></tr>
                </table>';
        return $html;
    }
    
    // display_permissions_table()
    /**
     * Grabs all the files and folders for a provided directory. It scans in-depth by default.
     * @since  1.0.3
     */
    private function get_dir_files( $folder, $deep = true )
    {
        // Scan All Files In Directory
        $files = scandir( $folder );
        $results = [];
        foreach ( $files as $file ) {
            
            if ( in_array( $file, [ '.', '..' ] ) ) {
                
                if ( $file == '.' ) {
                    $abspath = $folder . '/';
                    
                    if ( $abspath == ABSPATH ) {
                        $results[] = ABSPATH;
                    } else {
                        $results[] = $folder;
                    }
                
                }
                
                // $file
            } elseif ( is_dir( $folder . '/' . $file ) ) {
                
                if ( $deep ) {
                    //It is a dir; let's scan it
                    $array_results = $this->get_dir_files( $folder . '/' . $file );
                    foreach ( $array_results as $r ) {
                        $results[] = $r;
                    }
                    // foreach()
                } else {
                    // Add folder to list and do not scan it.
                    $results[] = $folder . '/' . $file;
                }
                
                // $deep
            } else {
                //It is a file
                $results[] = $folder . '/' . $file;
            }
        
        }
        // foreach()
        return $results;
    }
    
    // get_dir_files()
    /**
     * Fix File Permissions
     * @since  1.1.0
     */
    private function fix_permissions()
    {
        global  $SecuritySafe ;
        
        if ( isset( $_POST ) && !empty($_POST) ) {
            
            if ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], [
                'core',
                'theme',
                'plugins',
                'uploads'
            ] ) ) {
                
                if ( isset( $_POST['fixall'] ) && ($_POST['fixall'] == '1' || $_POST['fixall'] == '2') ) {
                } else {
                    // Add Notice To Look At Process Log
                    $SecuritySafe->messages[] = [ __( 'Please review the Process Log below for details.', SECSAFE_SLUG ), 1, 0 ];
                    // Sanitize $_POST Before We Do Anything
                    $post = filter_var_array( $_POST, FILTER_SANITIZE_STRING );
                    foreach ( $post as $name => $value ) {
                        $v = explode( '|', $value );
                        
                        if ( strpos( $name, 'file-' ) === false || $v[0] == '0' ) {
                            // Pass On This One
                        } else {
                            $this->set_permissions( $v[1], $v[0] );
                        }
                        
                        // strpos()
                    }
                    // foreach()
                }
                
                // $_POST['fixall']
            }
            
            // $_GET['tab']
        }
        
        // $_POST
    }
    
    /** 
     * Set Permissions For File or Directory
     * @param $path Absolute path to file or directory
     * @param $perm Desired permissions value 3 chars
     * @param $errors_only When set to true, only errors will be recorded in the Process Log
     * @param $sanitize Set to false to skip sanitization (for fix_all)
     */
    private function set_permissions(
        $path,
        $perm,
        $errors_only = false,
        $sanitize = true
    )
    {
        
        if ( $sanitize ) {
            // Get File Path With A Baseline Sanitization
            $path = esc_url( $path );
            // Cleanup Path ( bc WP doesn't have a file path sanitization filter )
            $path = str_replace( [
                ABSPATH,
                'http://',
                'https://',
                '..',
                '"',
                "'",
                ')',
                '('
            ], '', $path );
            // Add ABSPATH
            $path = ABSPATH . $path;
            // Cleanup Path Again..
            $path = str_replace( [
                '/./',
                '////',
                '///',
                '//'
            ], '/', $path );
            // Get Permissions
            $perm = sanitize_text_field( $perm );
        }
        
        // Relative Path (clean)
        $rel_path = str_replace( ABSPATH, '/', $path );
        $result = false;
        
        if ( file_exists( $path ) ) {
            // Permissions Be 3 Chars In Length
            
            if ( strlen( $perm ) == 3 ) {
                // Perm Value Must Be Octal; Not A String
                
                if ( $perm == '775' ) {
                    $result = chmod( $path, 0775 );
                } elseif ( $perm == '755' ) {
                    $result = chmod( $path, 0755 );
                } elseif ( $perm == '711' ) {
                    $result = chmod( $path, 0711 );
                } elseif ( $perm == '644' ) {
                    $result = chmod( $path, 0644 );
                } elseif ( $perm == '640' ) {
                    $result = chmod( $path, 0640 );
                } elseif ( $perm == '604' ) {
                    $result = chmod( $path, 0604 );
                } elseif ( $perm == '600' ) {
                    $result = chmod( $path, 0600 );
                }
                
                $result = true;
            }
            
            // strlen()
        } else {
            $this->messages[] = [ sprintf( __( 'Error: File does not exist - %s', SECSAFE_SLUG ), $path ), 3, 0 ];
        }
        
        // file_exists()
        
        if ( $result ) {
            if ( !$errors_only ) {
                $this->messages[] = [ sprintf( __( 'File permissions were successfully updated to %1$s for file: %2$s', SECSAFE_SLUG ), $perm, $rel_path ), 0, 0 ];
            }
        } else {
            $this->messages[] = [ sprintf( __( 'Error: File permissions could not be updated to %1$s for file: %2$s. Please contact your hosting provider or a developer for assistance.', SECSAFE_SLUG ), $perm, $rel_path ), 3, 0 ];
        }
        
        // $result
    }
    
    // set_permissions()
    /**
     * Retrieves secure permissions value for a particular type of file
     * @since  1.2.0
     * @param  $p Path of file
     * @param  $type file or dir
     * @return  returns the recommended secure permissions value or false if bad input
     */
    function get_secure_perms( $p, $type )
    {
        $sec = false;
        // Force lowercase for faster search
        $p = strtolower( $p );
        
        if ( $type == 'file' ) {
            $sec = '644';
            // Secure
            // Secure Permissions for certain files
            // https://codex.wordpress.org/Changing_File_Permissions#Finding_Secure_File_Permissions
            
            if ( strpos( $p, '.txt' ) ) {
                $sec = ( strpos( $p, 'readme.txt' ) ? '640' : $sec );
                $sec = ( $sec == '644' && strpos( $p, 'changelog.txt' ) ? '640' : $sec );
                $sec = ( $sec == '644' && strpos( $p, 'license.txt' ) ? '640' : $sec );
            } else {
                
                if ( strpos( $p, '.md' ) ) {
                    $sec = ( strpos( $p, 'readme.md' ) ? '640' : $sec );
                    $sec = ( $sec == '644' && strpos( $p, 'changelog.md' ) ? '640' : $sec );
                } else {
                    $sec = ( strpos( $p, 'readme.html' ) ? '640' : $sec );
                    $sec = ( $sec == '644' && strpos( $p, 'wp-config.php' ) ? '600' : $sec );
                    $sec = ( $sec == '644' && strpos( $p, 'php.ini' ) ? '600' : $sec );
                }
            
            }
        
        } else {
            if ( $type == 'dir' ) {
                $sec = '755';
            }
        }
        
        return $sec;
    }
    
    // get_secure_perms()
    /**
     * Displays the current status of files that are not secure.
     * @since  1.1.4
     */
    private function display_notices_perms( $good, $ok, $bad )
    {
        global  $SecuritySafe ;
        // Good Directories
        
        if ( isset( $good['dirs'] ) && $good['dirs'] > 0 ) {
            
            if ( $good['dirs'] > 1 ) {
                // Plural
                $message = sprintf( __( 'You have %d directories that could be more secure.', SECSAFE_SLUG ), $good['dirs'] );
            } else {
                // Singular
                $message = sprintf( __( 'You have %d directory that could be more secure.', SECSAFE_SLUG ), $good['dirs'] );
            }
            
            $SecuritySafe->messages[] = [ $message, 1, 1 ];
        }
        
        // Good Files
        
        if ( isset( $good['files'] ) && $good['files'] > 0 ) {
            
            if ( $good['files'] > 1 ) {
                // Plural
                $message = sprintf( __( 'You have %d files that could be more secure.', SECSAFE_SLUG ), $good['files'] );
            } else {
                // Singular
                $message = sprintf( __( 'You have %d file that could be more secure.', SECSAFE_SLUG ), $good['files'] );
            }
            
            $SecuritySafe->messages[] = [ $message, 1, 1 ];
        }
        
        // OK Directories
        
        if ( isset( $ok['dirs'] ) && $ok['dirs'] > 0 ) {
            
            if ( $ok['dirs'] > 1 ) {
                // Plural
                $message = sprintf( __( 'You have %d directories with safe but unique file permissions. This might cause functionality issues.', SECSAFE_SLUG ), $ok['dirs'] );
            } else {
                // Singular
                $message = sprintf( __( 'You have %d directory with safe but unique file permissions. This might cause functionality issues.', SECSAFE_SLUG ), $ok['dirs'] );
            }
            
            $SecuritySafe->messages[] = [ $message, 2, 1 ];
        }
        
        // OK Files
        
        if ( isset( $ok['files'] ) && $ok['files'] > 0 ) {
            
            if ( $ok['files'] > 1 ) {
                // Plural
                $message = sprintf( __( 'You have %d files with safe but unique file permissions. This might cause functionality issues.', SECSAFE_SLUG ), $ok['files'] );
            } else {
                // Singular
                $message = sprintf( __( 'You have %d file with safe but unique file permissions. This might cause functionality issues.', SECSAFE_SLUG ), $ok['files'] );
            }
            
            $SecuritySafe->messages[] = [ $message, 2, 1 ];
        }
        
        // Bad Directories
        
        if ( isset( $bad['dirs'] ) && $bad['dirs'] > 0 ) {
            
            if ( $bad['dirs'] > 1 ) {
                // Plural
                $message = sprintf( __( 'You have %d directories that are vulnerable.', SECSAFE_SLUG ), $bad['dirs'] );
            } else {
                // Singular
                $message = sprintf( __( 'You have %d directory that is vulnerable.', SECSAFE_SLUG ), $bad['dirs'] );
            }
            
            $SecuritySafe->messages[] = [ $message, 3, 0 ];
        }
        
        // Bad Files
        
        if ( isset( $bad['files'] ) && $bad['files'] > 0 ) {
            
            if ( $bad['files'] > 1 ) {
                // Plural
                $message = sprintf( __( 'You have %d files that are vulnerable.', SECSAFE_SLUG ), $bad['files'] );
            } else {
                // Singular
                $message = sprintf( __( 'You have %d files that is vulnerable.', SECSAFE_SLUG ), $bad['files'] );
            }
            
            $SecuritySafe->messages[] = [ $message, 3, 0 ];
        }
        
        // PHP Notices
        
        if ( isset( $ok['php'] ) && is_array( $ok['php'] ) ) {
            $PHP_major = substr( $ok['php'][1], 0, 1 );
            $PHP_changelog = 'https://secure.php.net/ChangeLog-' . $PHP_major . '.php';
            $message = sprintf(
                __( 'You have PHP version %1$s and it needs to be updated to version %2$s or higher. If version %2$s was released more than 30 days ago and there is more than a 90-day timespan between PHP version %1$s and %2$s (<a href="%3$s" target="_blank">see changelog</a>), contact your hosting provider to upgrade PHP.', SECSAFE_SLUG ),
                $ok['php'][0],
                $ok['php'][1],
                $PHP_changelog
            );
            $SecuritySafe->messages[] = [ $message, 2, 0 ];
        }
        
        // $bad['php']
        
        if ( isset( $bad['php'] ) && is_array( $bad['php'] ) ) {
            $message = sprintf( __( 'You are using PHP version %1$s, which is no longer supported or has critical vulnerabilities. Immediately contact your hosting company to upgrade PHP to version %2$s or higher.', SECSAFE_SLUG ), $bad['php'][0], $bad['php'][1] );
            $SecuritySafe->messages[] = [ $message, 3, 0 ];
        }
        
        // $bad['php']
        // Display Notices Created In This File
        $SecuritySafe->display_notices( true );
    }
    
    // display_notices_perms()
    /**
     * Returns the label of the status that is I18n compatible
     * @since  2.2.0
     */
    private static function display_status( $current )
    {
        $status = [
            'ok'     => __( 'OK', SECSAFE_SLUG ),
            'bad'    => __( 'Bad', SECSAFE_SLUG ),
            'good'   => __( 'Good', SECSAFE_SLUG ),
            'secure' => __( 'Secure', SECSAFE_SLUG ),
        ];
        return ( isset( $status[$current] ) ? $status[$current] : ucwords( esc_html( $current ) ) );
    }

}
// AdminPageFiles()