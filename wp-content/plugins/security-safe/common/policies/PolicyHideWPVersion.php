<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyHideWPVersion
 * @package SecuritySafe
 * @since 1.1.3
 */
class PolicyHideWPVersion {


    /**
     * PolicyHideWPVersion constructor.
     */
	function __construct(){

        // Remove Version From RSS
        add_filter( 'the_generator', array( $this, 'rss_version' ) );

        // Remove Generator Tag in HTML
        remove_action('wp_head', 'wp_generator');

	} // __construct()


    /**
     * Remove WordPress Version From RSS
     * @since  1.1.3
     * @return string
     */ 
    function rss_version(){

        return '';

    } // rss_version()


} // PolicyHideWPVersion()
