<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * Class Admin
 * @package SecuritySafe
 */
class Admin extends Security
{
    protected  $page ;
    /**
     * Admin constructor.
     */
    function __construct( $plugin )
    {
        
        if ( is_array( $plugin ) ) {
            // Run parent class constructor first
            parent::__construct( $plugin );
            $this->log( 'running Admin.php' );
            $this->check_settings();
            $this->policy_notices();
            // Display Admin Notices
            add_action( 'admin_notices', array( $this, 'display_notices' ) );
            // Load CSS / JS
            add_action( 'admin_init', array( $this, 'scripts' ) );
            // Body Class
            add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
            // Create Admin Menus
            add_action( 'admin_menu', array( $this, 'admin_menus' ) );
            // Add Action Links
            
            if ( is_network_admin() ) {
                add_filter( 'network_admin_plugin_action_links_security-safe/security-safe.php', array( $this, 'plugin_action_links' ) );
            } else {
                add_filter( 'plugin_action_links_security-safe/security-safe.php', array( $this, 'plugin_action_links' ) );
            }
            
            if ( $this->debug ) {
                $this->messages[] = array( 'Plugin Debug Mode is on.', 1, 0 );
            }
            // $this->debug
        } else {
            $this->log( 'ERROR: Cannot load plugin. $plugin is not an array in Admin.php' );
        }
        
        // $plugin
    }
    
    // __construct()
    /**
     * Initializes admin scripts
     */
    public function scripts()
    {
        
        if ( isset( $_GET['page'] ) ) {
            // Shorten Code References
            $plugin = $this->plugin;
            // See if the page is one of ours
            $local_page = strpos( $_GET['page'], $this->plugin['slug'] );
            // Only load CSS and JS for our admin pages.
            
            if ( $local_page !== false ) {
                // Load CSS
                wp_register_style(
                    $plugin['slug'] . '-admin',
                    $plugin['url'] . 'css/admin.css',
                    array(),
                    $plugin['version'],
                    'all'
                );
                wp_enqueue_style( $plugin['slug'] . '-admin' );
                // Load JS
                wp_enqueue_script( 'common' );
                wp_enqueue_script( 'wp-lists' );
                wp_enqueue_script( 'postbox' );
                wp_enqueue_script(
                    $plugin['slug'] . '-admin',
                    $plugin['url'] . 'js/admin.js',
                    array( 'jquery' ),
                    $plugin['version'],
                    true
                );
            }
            
            // $local_page
            // Memory Cleanup
            unset( $plugin, $local_page );
        }
        
        // isset()
    }
    
    //scripts()
    /**
     * Adds a class to the body tag
     * @since  0.2.0
     */
    public function admin_body_class( $classes )
    {
        $classes .= ' ' . $this->plugin['slug'];
        return $classes;
    }
    
    // admin_body_class()
    /**
     * Creates Admin Menus
     */
    public function admin_menus()
    {
        $this->log( 'Creating Admin Menus.' );
        $page = array();
        // Add the menu page
        $page['menu_title'] = 'Security Safe';
        $page['title'] = $page['menu_title'] . ' Dashboard';
        $page['capability'] = 'activate_plugins';
        $page['slug'] = $this->plugin['slug'];
        $page['function'] = array( $this, 'page_dashboard' );
        $page['icon_url'] = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iODMuNDExcHgiIGhlaWdodD0iOTQuMTNweCIgdmlld0JveD0iMC4wMDEgMzQ4LjkzNSA4My40MTEgOTQuMTMiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMC4wMDEgMzQ4LjkzNSA4My40MTEgOTQuMTMiDQoJIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGc+DQoJPHBhdGggZmlsbD0iI0YyNjQxOSIgZD0iTTgzLjI3MSwzNTYuODk2YzAsMC0yMC41NjItNy45NjEtNDEuNjI4LTcuOTYxYy0yMS4wNjcsMC00MS42MjksNy45NjEtNDEuNjI5LDcuOTYxDQoJCXMtMC43OTUsMzAuMDMsMTAuMDMyLDUxLjgwNGMxMC44MjUsMjEuNzcxLDMyLjA5OSwzNC4zNjUsMzIuMDk5LDM0LjM2NXMyMS4wNzgtMTMuMjI3LDMyLjEtMzYuODU0DQoJCUM4NS4yNjYsMzgyLjU4MSw4My4yNzEsMzU2Ljg5Niw4My4yNzEsMzU2Ljg5NnogTTUuMjksMzYxLjgxNGwwLjAzOC0xLjQ4M2wxLjQwNi0wLjQ4MWMwLjQ0OS0wLjE1NCw3LjQzMS0yLjUwNywxNi45NTktNC4xOQ0KCQljLTIuMTU0LDEuMjcxLTQuMjQ0LDIuNzc1LTUuNjQyLDMuODk5Yy01LjU0OSw0LjQ1NC0xMC4wMTgsOS4wOTktMTIuNDg4LDExLjgzMUM1LjIwMSwzNjUuOTM1LDUuMjgsMzYyLjIwOSw1LjI5LDM2MS44MTR6DQoJCSBNNi4wMTIsMzc2LjYzMWMyLjQ2OCwyLjM1LDYuODU1LDUuNzk1LDEzLjc2Nyw4Ljg2OWMxMS40MDgsNS4wNzIsMjEuODIyLDcuMTc2LDIxLjgyMiw3LjE3NnM4LjgxLTIuNTYxLDE4LjA2MS03LjkyNg0KCQlzMTEuNTI2LTcuNTg4LDExLjUyNi03LjU4OHMtMTMuMjkzLDAuNzA3LTI0LjA4LTEuMTQ5Yy0xMi45MTktMi4yMjQtMTcuMzI1LTUuNDQtMTcuMzI1LTUuNDRzNC40MDYtNC4wNjIsMTAuNDI1LTcuNjY2DQoJCWM2LjMxNC0zLjc3NywxMy45MzctNi43NDIsMTYuNTQ1LTcuNzA5YzEwLjkzOCwxLjY3NiwxOS4yNzMsNC40ODQsMTkuNzY0LDQuNjUzbDEuMzM2LDAuNDU0bDAuMTA0LDEuNDA4DQoJCWMwLjAzMywwLjQ1NSwwLjQxMyw2LjAwMi0wLjMwNCwxMy44NzljLTIuNzUyLDIuNjUtMTMuMzc0LDEyLjAzMS0zMi41OTgsMTkuMTk5Yy0xOC4zNTQsNi44NDQtMjkuOTA2LDguNzU2LTMyLjQ4NCw5LjEyNQ0KCQlDOC42OTUsMzk0Ljk2Myw2Ljg2NiwzODQuNzYsNi4wMTIsMzc2LjYzMXogTTY5LjMyLDQwNi40ODljLTMuODQ4LDIuNDA2LTEyLjA2Nyw3LjA2MS0yMy41MzQsMTAuOTENCgkJYy0xMi41NDYsNC4yMTUtMTguNDY4LDUuMzAxLTIwLjM1OSw1LjU2NmMtMC42OTMtMC43MjktMS4zODUtMS40OTQtMi4wNzUtMi4yODVjMi40MDUtMC41OTIsMTEuNzkzLTIuOTk4LDIzLjkwMy03LjM0Ng0KCQljMTEuMDU4LTMuOTY5LDIwLjU1NS05LjgyNiwyNC42MTctMTIuNTFjLTAuNDczLDEuMTg4LTAuOTc5LDIuMzc3LTEuNTI2LDMuNTU3QzcwLjAxNCw0MDUuMDk4LDY5LjY3LDQwNS43OTcsNjkuMzIsNDA2LjQ4OXoiLz4NCjwvZz4NCjwvc3ZnPg0K';
        $page['position'] = '999';
        add_menu_page(
            $page['title'],
            $page['menu_title'],
            $page['capability'],
            $page['slug'],
            $page['function'],
            $page['icon_url'],
            $page['position']
        );
        $subpages = $this->get_admin_pages();
        $this->add_submenu_pages( $subpages, $page );
        // Memory Cleanup
        unset( $page, $subpages );
    }
    
    //admin_menus()
    /**
     * Get all admin pages as an array
     * @return  array An array of all the admin pages
     * @uses  get_category_pages()
     * @since  0.1.0
     */
    private function get_admin_pages()
    {
        // All Admin Pages
        return $this->get_category_pages();
    }
    
    // get_admin_pages()
    /**
     * Get Category Pages
     * @return  $pages array
     * @since  0.2.0
     */
    private function get_category_pages( $disabled = false )
    {
        // All Category Pages
        $pages = array();
        // Return Plugin Landing Page
        if ( $disabled ) {
            $pages[] = 'Plugin';
        }
        $pages[] = 'Privacy';
        $pages[] = 'Files';
        $pages[] = 'User Access';
        $pages[] = 'Content';
        // Return Disabled Menus Also
        // Disabled values are arrays for each of checking
        
        if ( $disabled ) {
            $pages[] = array( 'Firewall' );
            //$pages[] = array( 'Backups' );
        }
        
        // $disabled
        // Memory Cleanup
        unset( $disabled );
        return $pages;
    }
    
    // get_category_pages()
    /**
     * Creates all the subpages for the menu
     * @param array $subpages
     * @since  0.1.0
     */
    private function add_submenu_pages( $subpages = false, $page = false )
    {
        $this->log( 'Function add_submenu_pages().' );
        
        if ( is_array( $subpages ) && is_array( $page ) ) {
            foreach ( $subpages as $title ) {
                $title_lc = strtolower( $title );
                $title_uscore = str_replace( ' ', '_', $title_lc );
                $title_hyphen = str_replace( ' ', '-', $title_lc );
                add_submenu_page(
                    $page['slug'],
                    // Parent Slug
                    $page['menu_title'] . ' ' . $title,
                    // Page Title
                    $title,
                    // Menu Title
                    $page['capability'],
                    // Capability
                    $page['slug'] . '-' . $title_hyphen,
                    // Menu Slug
                    array( $this, 'page_' . $title_uscore )
                );
                $this->log( 'Created submenu page ' . $title . '.' );
            }
            // endforeach
            // Memory Cleanup
            unset(
                $title,
                $title_lc,
                $title_uscore,
                $title_hyphen
            );
        } else {
            $this->log( 'ERROR: Variable $subpages is not an array.', __FILE__, __LINE__ );
        }
        
        // endif
        $this->log( 'Finished function add_submenu_pages().' );
        // Memory Cleanup
        unset( $subpages, $pages );
    }
    
    // add_submenu_pages()
    /**
     * Gets the admin page
     * @param  string $title The title of the submenu
     * @since  0.2.0
     */
    private function get_page( $page_slug = false )
    {
        
        if ( $page_slug ) {
            $this->log( 'Getting ' . $page_slug . ' Page' );
            // Format Title
            $title_camel = str_replace( ' ', '', $page_slug );
            // Include Admin Page
            require_once $this->plugin['dir_admin'] . '/AdminPage.php';
            require_once $this->plugin['dir_admin'] . '/AdminPage' . $title_camel . '.php';
            // Class For The Page
            $class = __NAMESPACE__ . '\\AdminPage' . $title_camel;
            $page_slug = strtolower( $page_slug );
            // Get Page Specific Settings
            $page_settings = $this->settings[$page_slug];
            
            if ( is_array( $page_settings ) ) {
                $this->page = new $class( $page_settings );
                $this->display_page();
            }
            
            // is_array()
            $this->log( $page_slug . ' Page Finished' );
            // Memory Cleanup
            unset( $title_camel, $class, $page_settings );
        } else {
            $this->log( 'ERROR: Parameter title is empty.', __FILE__, __LINE__ );
        }
        
        // Memory Cleanup
        unset( $page_slug );
    }
    
    // get_page()
    /**
     * Wrapper for creating Dashboard page
     * @since  0.1.0
     */
    public function page_dashboard()
    {
        $this->get_page( 'General' );
    }
    
    // page_dashboard()
    /**
     * Wrapper for creating Privacy page
     * @since  0.2.0
     */
    public function page_privacy()
    {
        $this->get_page( 'Privacy' );
    }
    
    // page_privacy()
    /**
     * Wrapper for creating Files page
     * @since  0.2.0
     */
    public function page_files()
    {
        $this->get_page( 'Files' );
    }
    
    // page_files()
    /**
     * Wrapper for creating Content page
     * @since  0.2.0
     */
    public function page_content()
    {
        $this->get_page( 'Content' );
    }
    
    // page_content()
    /**
     * Wrapper for creating User Access page
     * @since  0.2.0
     */
    public function page_user_access()
    {
        $this->get_page( 'Access' );
    }
    
    // page_user_access()
    /**
     * Wrapper for creating Firewall page
     * @since  0.2.0
     */
    public function page_firewall()
    {
        $this->get_page( 'Firewall' );
    }
    
    // page_firewall()
    /**
     * Wrapper for creating Backups page
     * @since  0.2.0
     */
    public function page_backups()
    {
        $this->get_page( 'Backups' );
    }
    
    // page_backups()
    /**
     * Page template
     * @return string
     * @since  0.2.0
     */
    protected function display_page()
    {
        $plugin = $this->plugin;
        $page = $this->page;
        ?>
        <div class="wrap">

            <div class="intro">
                            
                <h1><?php 
        printf( __( '%s', 'security-safe' ), $page->title );
        ?></h1>
                
                <p class="desc"><?php 
        printf( __( '%s', 'security-safe' ), $page->description );
        ?></p>
            
                <a href="<?php 
        echo  $plugin['url_more_info'] ;
        ?>" target="_blank" class="ss-logo"><img src="<?php 
        echo  $plugin['url'] ;
        ?>/img/logo.svg" alt="<?php 
        echo  $plugin['name'] ;
        ?>"><br /><span class="version"><?php 
        ?>Version <?php 
        echo  $plugin['version'] ;
        ?></span></a>

            </div><!-- .intro -->

            <?php 
        $this->display_heading_menu();
        $page->display_tabs();
        // Build action URL
        $action_url = 'admin.php?page=' . $page->slug;
        $action_url .= ( isset( $_GET['tab'] ) ? '&tab=' . sanitize_text_field( $_GET['tab'] ) : '' );
        $enctype = ( isset( $_GET['tab'] ) && $_GET['tab'] == 'export-import' ? ' enctype="multipart/form-data"' : '' );
        ?>

            <form method="post" action="<?php 
        echo  admin_url( $action_url ) ;
        ?>"<?php 
        echo  $enctype ;
        ?>>
    
                <div class="all-tab-content">

                    <?php 
        $page->display_tabs_content();
        $tabs_with_sidebars = array( 'settings', 'export-import', 'debug' );
        
        if ( !isset( $_GET['tab'] ) || isset( $_GET['tab'] ) && in_array( $_GET['tab'], $tabs_with_sidebars ) ) {
            ?>
                        <div id="sidebar" class="sidebar">

                            <div class="follow-us widget">
                                <p><a href="https://twitter.com/wpsecuritysafe" class="icon-twitter" target="_blank">Follow Security Safe</a></p>
                            </div>
                            <?php 
            
            if ( security_safe()->is_not_paying() ) {
                ?>
                            <div class="upgrade-pro widget">
                                
                                <h5>Get More Features</h5>
                                <p>Pro features give you more control and save you time.</p>
                                <p class="cta"><a href="<?php 
                echo  $plugin['url_more_info_pro'] ;
                ?>" target="_blank" class="icon-right-open">Upgrade to Pro!</a></p>
                            </div>
                            <?php 
            }
            
            ?>
                            <div class="rate-us widget">
                                <h5>Like Security Safe?</h5>
                                <p>Share your positive experience!</p>
                                <p class="cta ratings"><a href="https://wordpress.org/plugins/security-safe/#reviews" target="_blank" class="rate-stars"><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span></a></p>
                            </div>
                        </div>
                    <?php 
        }
        
        ?>

                    <div id="tab-content-footer" class="footer tab-content"></div>

                </div><!-- .all-tab-content -->

            </form>

            <div class="wrap-footer full clear">

                <hr />

                <p>If you like <?php 
        echo  $plugin['name'] ;
        ?>, please <a href="https://wordpress.org/support/plugin/security-safe/reviews/#new-post" target="_blank">post a review</a>.</p>
            
                <p>Need help? Visit the <a href="https://wordpress.org/support/plugin/security-safe/" target="_blank">support forum</a>.</p>
            
            </div>
        </div><!-- .wrap -->
        <?php 
        // Memory Cleanup
        unset(
            $page,
            $plugin,
            $action_url,
            $enctype
        );
    }
    
    // display_page()
    /**
     * Display Heading Menu
     * @since  0.2.0
     */
    protected function display_heading_menu()
    {
        $menus = $this->get_category_pages( true );
        echo  '<ul class="featured-menu">' ;
        foreach ( $menus as $m ) {
            // Add Class For Disabled Menus
            $disabled = '';
            
            if ( is_array( $m ) ) {
                $disabled = ' disabled';
                $m = $m[0];
            }
            
            // is_array()
            $class = strtolower( str_replace( ' ', '-', $m ) );
            
            if ( $m == 'Plugin' ) {
                $href = 'href="admin.php?page=' . $this->plugin['slug'] . '"';
            } else {
                $href = ( $disabled ? '' : 'href="admin.php?page=' . $this->plugin['slug'] . '-' . $class . '"' );
            }
            
            // Highlight Active Menu
            
            if ( $_GET['page'] == 'security-safe' && $m == 'Plugin' ) {
                $active = ' active';
            } else {
                $active = ( strpos( $_GET['page'], $class ) !== false ? ' active' : '' );
            }
            
            $class .= $active . $disabled;
            // Convert All Menus to A Single Line
            $m = ( $m == 'User Access' ? 'Access' : $m );
            echo  '<li><a ' . $href . 'class="icon-' . $class . '"><span>' . $m . '</span></a></li>' ;
        }
        // foreach
        echo  '</ul>' ;
        // Memory Cleanup
        unset(
            $menus,
            $m,
            $disabled,
            $class,
            $href,
            $active
        );
    }
    
    // display_heading_menu()
    /**
     * Displays all messages
     * @since  0.2.0
     */
    public function display_notices()
    {
        $this->log( 'display_notices()' );
        
        if ( is_array( $this->messages ) ) {
            foreach ( $this->messages as $m ) {
                $message = ( isset( $m[0] ) ? $m[0] : false );
                $status = ( isset( $m[1] ) ? $m[1] : 0 );
                $dismiss = ( isset( $m[2] ) ? $m[2] : 0 );
                if ( $message ) {
                    // Display Message
                    $this->admin_notice( $message, $status, $dismiss );
                }
                // $message
            }
            // foreach ()
            // Reset Messages
            $this->messages = array();
            // Memory Cleanup
            unset(
                $m,
                $message,
                $status,
                $dismiss
            );
        }
        
        // is_array()
    }
    
    // display_notices()
    /**
     * Displays a message at the top of the screen.
     * @return  html code
     * @since  0.1.0
     */
    protected function admin_notice( $message, $status = 0, $dismiss = 0 )
    {
        $this->log( 'admin_notice()' );
        // Set Classes
        $class = 'notice-success';
        $class = ( $status == 1 ? 'notice-info' : $class );
        $class = ( $status == 2 ? 'notice-warning' : $class );
        $class = ( $status == 3 ? 'notice-error' : $class );
        $class = 'active notice ' . $class;
        if ( $dismiss ) {
            $class .= ' is-dismissible';
        }
        // Add Message
        $message = __( $message, 'security-safe' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
        // Memory Cleanup
        unset(
            $message,
            $status,
            $dismiss,
            $class
        );
    }
    
    //admin_notice()
    /**
     * Checks settings and determines whether they need to be reset to default
     * @since  0.1.0
     */
    function check_settings()
    {
        $this->log( 'running check_settings()' );
        
        if ( isset( $_POST ) && !empty($_POST) ) {
            $this->log( 'form was submitted' );
            
            if ( isset( $_GET['page'] ) && strpos( $_GET['page'], $this->plugin['slug'] ) !== false && !in_array( $_GET['page'], array( 'security-safe-pricing', 'security-safe-account' ) ) ) {
                $this->log( 'we are on a Security Safe page' );
                
                if ( !isset( $_GET['tab'] ) || $_GET['tab'] == 'settings' ) {
                    $this->log( 'we are on a settings tab' );
                    // Remove Reset Variable
                    if ( isset( $_GET['reset'] ) ) {
                        unset( $_GET['reset'] );
                    }
                    // Create Page Slug
                    $page_slug = filter_var( $_GET['page'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
                    $page_slug = str_replace( array( 'security-safe-', 'security-safe' ), '', $page_slug );
                    // Compensation For Oddball Scenarios
                    $page_slug = ( $page_slug == '' ? 'general' : $page_slug );
                    $page_slug = ( $page_slug == 'user-access' ? 'access' : $page_slug );
                    $this->post_settings( $page_slug );
                    // Memory Cleanup
                    unset( $page_slug );
                } else {
                    
                    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'export-import' ) {
                        $this->log( 'on Export/Import tab' );
                        
                        if ( isset( $_POST['export-settings'] ) ) {
                            $this->export_settings();
                        } else {
                            if ( isset( $_POST['import-settings'] ) ) {
                                $this->import_settings();
                            }
                        }
                    
                    }
                
                }
                
                // isset( $_GET['tab'] )
            }
            
            // isset( $_GET['page'] )
        } else {
            
            if ( isset( $_GET['page'] ) && $_GET['page'] == $this->plugin['slug'] && isset( $_GET['reset'] ) && $_GET['reset'] == 1 ) {
                $this->log( 'Reset requested.' );
                // Reset On Plugin Settings Only
                $this->reset_settings();
            }
        
        }
        
        // isset( $_POST )
    }
    
    //check_settings()
    /**
     * Sets notices for policies that are disabled as a group.
     * @since  1.1.10
     */
    protected function policy_notices()
    {
        // All Plugin Policies
        
        if ( !isset( $this->settings['general']['on'] ) || $this->settings['general']['on'] != "1" ) {
            $message = 'Security Safe: All security policies are disabled.';
            $message .= ( isset( $_GET['page'] ) && $_GET['page'] == 'security-safe' ? '' : ' You can enable them in <a href="admin.php?page=security-safe&tab=settings#settings">Plugin Settings</a>. If you are experiencing an issue, <a href="admin.php?page=security-safe&reset=1">reset your settings.</a>' );
            $this->messages['general'] = array( $message, 2, 0 );
        } else {
            // Privacy Policies
            
            if ( !isset( $this->settings['privacy']['on'] ) || $this->settings['privacy']['on'] != "1" ) {
                $message = 'Security Safe: All privacy policies are disabled.';
                $message .= ( isset( $_GET['page'] ) && $_GET['page'] == 'security-safe-privacy' ? '' : ' You can enable them at the top of <a href="admin.php?page=security-safe-privacy&tab=settings#settings">Privacy Settings</a>.' );
                $this->messages['privacy'] = array( $message, 2, 0 );
            }
            
            // privacy
            // Files Policies
            
            if ( !isset( $this->settings['files']['on'] ) || $this->settings['files']['on'] != "1" ) {
                $message = 'Security Safe: All file policies are disabled.';
                $message .= ( isset( $_GET['page'] ) && $_GET['page'] == 'security-safe-files' ? '' : ' You can enable them at the top of <a href="admin.php?page=security-safe-files&tab=settings#settings">File Settings</a>.' );
                $this->messages['files'] = array( $message, 2, 0 );
            }
            
            // files
            // Access Policies
            
            if ( !isset( $this->settings['access']['on'] ) || $this->settings['access']['on'] != "1" ) {
                $message = 'Security Safe: All user access policies are disabled.';
                $message .= ( isset( $_GET['page'] ) && $_GET['page'] == 'security-safe-user-access' ? '' : ' You can enable them at the top of <a href="admin.php?page=security-safe-user-access&tab=settings#settings">User Access Settings</a>.' );
                $this->messages['access'] = array( $message, 2, 0 );
            }
            
            // access
            // Content Policies
            
            if ( !isset( $this->settings['content']['on'] ) || $this->settings['content']['on'] != "1" ) {
                $message = 'Security Safe: All content policies are disabled.';
                $message .= ( isset( $_GET['page'] ) && $_GET['page'] == 'security-safe-content' ? '' : ' You can enable them at the top of <a href="admin.php?page=security-safe-content&tab=settings#settings">Content Settings</a>.' );
                $this->messages['content'] = array( $message, 2, 0 );
            }
            
            // content
            /* // These Features Do Not Exist Yet =========
            
                            // Firewall Policies
                            if ( ! isset( $this->settings['firewall']['on'] ) || $this->settings['firewall']['on'] != "1" ) {
            
                                $message = 'Security Safe: The firewall is disabled.';
            
                                $message .= ( isset( $_GET['page'] ) && $_GET['page'] == 'security-safe-firewall' ) ? '' : ' You can enable it at the top of <a href="admin.php?page=security-safe-firewall&tab=settings#settings">Firewall Settings</a>.';
            
                                $this->messages['firewall'] = array( $message, 2, 0 );
            
                            } // firewall
            
                            // Backups Policies
                            if ( ! isset( $this->settings['backups']['on'] ) || $this->settings['backups']['on'] != "1" ) {
            
                                $message = 'Security Safe: Backups are disabled.';
            
                                $message .= ( isset( $_GET['page'] ) && $_GET['page'] == 'security-safe-backups' ) ? '' : ' You can enable them at the top of <a href="admin.php?page=security-safe-backups&tab=settings#settings">Backup Settings</a>.';
            
                                $this->messages['backups'] = array( $message, 2, 0 );
            
                            } // backups
                    
                    ============================================= */
        }
        
        // endif
    }
    
    /**
     * Plugin action links filter
     *
     * @param array $links Array of links for each plugin
     * @return array
     */
    function plugin_action_links( $links )
    {
        // Add
        array_unshift( $links, '<a style="color: #f56e28;" href="https://wordpress.org/plugins/security-safe/#reviews">Rate Us</a>' );
        return $links;
    }

}
// Admin()