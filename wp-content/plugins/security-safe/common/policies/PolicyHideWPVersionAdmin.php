<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyHideWPVersionAdmin
 * @package SecuritySafe
 * @since 1.2.0
 */
class PolicyHideWPVersionAdmin {


    /**
     * PolicyHideWPVersionAdmin constructor.
     */
	function __construct(){

        // Update footer
        add_action( 'admin_init', array( $this, 'update_footer' ) );

	} // __construct()


    /**
     * Update WordPress Admin Footer Version
     * @since  1.2.0
     * @return string
     */ 
    function update_footer(){

        add_filter( 'admin_footer_text',    array( $this, 'custom_footer' ), 11 );
        add_filter( 'update_footer',        '__return_false', 11 );

    } // update_footer()

    /**
     * Set a custom string value for the footer
     * @since  1.2.0
     * @return string
     */ 
    function custom_footer(){

        // NOTE: Will add the ability to customize this in the future.

        return '';

    } // remove_footer()


} // PolicyHideWPVersionAdmin()
