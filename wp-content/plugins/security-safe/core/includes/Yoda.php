<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * Class Yoda - Whats up, Yoda knows.
 * @package SecuritySafe
 * @since 2.0.0
 */
class Yoda
{
    /**
     * Yoda constructor.
     */
    // Construct, Yoda does not.
    /** 
     * Constant variables, this method sets.
     * @since  2.0.0
     */
    static function set_constants()
    {
        define( 'SECSAFE_SLUG', 'security-safe' );
        define( 'SECSAFE_DIR_LANG', dirname( plugin_basename( SECSAFE_FILE ) ) . '/languages/' );
        define( 'SECSAFE_NAME', __( 'WP Security Safe', SECSAFE_SLUG ) );
        define( 'SECSAFE_NAME_PRO', __( 'WP Security Safe Pro', SECSAFE_SLUG ) );
        define( 'SECSAFE_OPTIONS', 'securitysafe_options' );
        define( 'SECSAFE_DB_FIREWALL', 'sovstack_logs' );
        define( 'SECSAFE_DB_STATS', 'sovstack_stats' );
        define( 'SECSAFE_DIR_SECURITY', SECSAFE_DIR_CORE . '/security' );
        define( 'SECSAFE_DIR_PRIVACY', SECSAFE_DIR_SECURITY . '/privacy' );
        define( 'SECSAFE_DIR_FIREWALL', SECSAFE_DIR_SECURITY . '/firewall' );
        define( 'SECSAFE_DIR_ADMIN', SECSAFE_DIR_CORE . '/admin' );
        define( 'SECSAFE_DIR_ADMIN_INCLUDES', SECSAFE_DIR_ADMIN . '/includes' );
        define( 'SECSAFE_DIR_ADMIN_PAGES', SECSAFE_DIR_ADMIN . '/pages' );
        define( 'SECSAFE_DIR_ADMIN_TABLES', SECSAFE_DIR_ADMIN . '/tables' );
        define( 'SECSAFE_URL', plugin_dir_url( SECSAFE_FILE ) );
        define( 'SECSAFE_URL_ASSETS', SECSAFE_URL . 'core/assets/' );
        define( 'SECSAFE_URL_ADMIN_ASSETS', SECSAFE_URL . 'core/admin/assets/' );
        define( 'SECSAFE_URL_AUTHOR', 'https://sovstack.com/' );
        define( 'SECSAFE_URL_MORE_INFO', 'https://wpsecuritysafe.com/' );
        define( 'SECSAFE_URL_MORE_INFO_PRO', admin_url( 'admin.php?page=security-safe-pricing' ) );
        define( 'SECSAFE_URL_TWITTER', 'https://twitter.com/wpsecuritysafe' );
        define( 'SECSAFE_URL_WP', 'https://wordpress.org/plugins/security-safe/' );
        define( 'SECSAFE_URL_WP_REVIEWS', SECSAFE_URL_WP . '#reviews' );
        define( 'SECSAFE_URL_WP_REVIEWS_NEW', SECSAFE_URL_WP . 'reviews/#new-post' );
    }
    
    // set_constants()
    /**
     * Retrieves the array of data types
     * @since  2.0.0
     */
    static function get_types()
    {
        return [
            '404s'       => __( '404s Errors', SECSAFE_SLUG ),
            'logins'     => __( 'Login Attempts', SECSAFE_SLUG ),
            'comments'   => __( 'Comments', SECSAFE_SLUG ),
            'allow_deny' => __( 'Firewall Rules', SECSAFE_SLUG ),
            'activity'   => __( 'User Activity', SECSAFE_SLUG ),
            'blocked'    => __( 'Blocked Activity', SECSAFE_SLUG ),
            'threats'    => __( 'Threats', SECSAFE_SLUG ),
        ];
    }
    
    // get_types()
    /**
     * Retrieves the visitor's IP address
     * @since  2.0.0
     */
    static function get_ip()
    {
        $ip = false;
        $ip = ( !$ip && isset( $_SERVER['HTTP_CLIENT_IP'] ) && $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : $ip );
        $ip = ( !$ip && isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $ip );
        $ip = ( !$ip && isset( $_SERVER['HTTP_X_FORWARDED'] ) && $_SERVER['HTTP_X_FORWARDED'] ? $_SERVER['HTTP_X_FORWARDED'] : $ip );
        $ip = ( !$ip && isset( $_SERVER['HTTP_FORWARDED_FOR'] ) && $_SERVER['HTTP_FORWARDED_FOR'] ? $_SERVER['HTTP_FORWARDED_FOR'] : $ip );
        $ip = ( !$ip && isset( $_SERVER['HTTP_FORWARDED'] ) && $_SERVER['HTTP_FORWARDED'] ? $_SERVER['HTTP_FORWARDED'] : $ip );
        $ip = ( !$ip && isset( $_SERVER['REMOTE_ADDR'] ) && $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : $ip );
        
        if ( !$ip ) {
            $ip = __( 'IP Unavailable', SECSAFE_SLUG );
        } else {
            $ip = filter_var( $ip, FILTER_VALIDATE_IP );
            $ip = ( !$ip ? __( 'Not Valid IP', SECSAFE_SLUG ) : $ip );
        }
        
        return $ip;
    }
    
    // get_ip()
    /**
     * Gets the User Agent of the current session
     * @since  2.1.0
     */
    static function get_user_agent()
    {
        $ua = ( defined( 'DOING_CRON' ) ? 'WP Cron' : false );
        $ua = ( !$ua && isset( $_SERVER['HTTP_USER_AGENT'] ) ? filter_var( $_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRING ) : '' );
        return $ua;
    }
    
    // get_user_agent()
    static function is_whitelisted()
    {
        return defined( 'SECSAFE_WHITELISTED' );
    }
    
    // is_whitelisted()
    static function is_blacklisted()
    {
        return defined( 'SECSAFE_BLACKLISTED' );
    }
    
    // is_blacklisted()
    /**
     * Retrieves the name of the table for firewall
     * @since  2.0.0
     */
    static function get_table_main()
    {
        global  $wpdb ;
        return $wpdb->prefix . SECSAFE_DB_FIREWALL;
    }
    
    // get_table_main()
    /**
     * Retrieves the name of the table for stats
     * @since  2.0.0
     */
    static function get_table_stats()
    {
        global  $wpdb ;
        return $wpdb->prefix . SECSAFE_DB_STATS;
    }
    
    // get_table_stats()
    /**
     * Retrieves the limit of data types
     * @since  2.0.0
     */
    static function get_display_limits( $type, $mx = false )
    {
        //Janitor::log( 'get_display_limits()' );
        $types = Self::get_types();
        // Require Valid Type
        
        if ( isset( $types[$type] ) ) {
            //Janitor::log( 'get_display_limits(): Valid Type' );
            $limits = array(
                '404s'       => 500,
                'logins'     => 100,
                'allow_deny' => 10,
                'activity'   => 1000,
            );
            if ( isset( $limits[$type] ) ) {
                return $limits[$type];
            }
        }
        
        //Janitor::log( 'get_display_limits(): Default' );
        // Default lowest value / false
        return 0;
    }

}
// Yoda()