<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyLoginLocal
 * @package SecuritySafe
 */
class PolicyLoginLocal {


    /**
     * PolicyLoginLocal constructor.
     */
	function __construct() {

        add_action( 'init', array( $this, 'force_local' ) );

	} // __construct()


    /**
     * This forces the user to actually be on the website when authenticating.
     * @since  0.2.0
     */ 
    function force_local() {
        
        // If Attempt to Login
        if ( strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false && isset( $_POST['log'] ) && isset( $_POST['pwd'] ) ) {

            // If Refferrer is not set or does not contain site's domain name
            if ( 
                ! isset( $_SERVER['HTTP_REFERER'] ) || 
                ! isset( $_SERVER['HTTP_HOST'] ) ||
                ! strpos( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] )
            ) {

                // TODO: Need to add logging of this activity in the future.

                // Return 403 Forbidden Header Response
                Security::forbidden();

            } // isset()

        } // isset()

    } // force_local()


} // PolicyLoginLocal()
