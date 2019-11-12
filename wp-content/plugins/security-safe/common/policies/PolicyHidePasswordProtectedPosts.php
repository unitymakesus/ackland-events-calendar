<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyHidePasswordProtectedPosts
 * @package SecuritySafe
 * @since 1.1.7
 */
class PolicyHidePasswordProtectedPosts {


    /**
     * PolicyPasswordProtectChildren constructor.
     */
	function __construct() {

        add_action('pre_get_posts', array( $this, 'exclude' ) );

	} // __construct()


    /**
     * Add to the query to require all posts that do not have a password.
     */
    function query( $where ) {

        global $wpdb;

        return $where .= " AND {$wpdb->posts}.post_password = '' ";

    } // query()
 

    /**
     * Exclude the password protected pages
     */
    function exclude( $query ) {
        
        if ( ! is_single() && ! is_page() && ! is_admin() ) {

            add_filter( 'posts_where', array( $this, 'query' ) );

        }

    } // exclude()


} // PolicyHidePasswordProtectedPosts()
