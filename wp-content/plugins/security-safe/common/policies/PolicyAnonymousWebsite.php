<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyAnonymousWebsite
 * @package SecuritySafe
 * @since 1.1.0
 */
class PolicyAnonymousWebsite {


    /**
     * PolicyAnonymousWebsite constructor.
     */
	function __construct(){

        add_filter( 'http_headers_useragent', array( $this, 'make_anonymous' ) );

	} // __construct()


    /**
     * Make Website Anonymous When Updates Are Performed
     */ 
    function make_anonymous(){

        global $wp_version;

        return 'WordPress/' . $wp_version . '; URL protected by Security Safe.';

    } // make_anonymous()


} // PolicyAnonymousWebsite()
