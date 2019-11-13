<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyLoginErrors
 * @package SecuritySafe
 */
class PolicyLoginErrors {


    /**
     * PolicyLoginErrors constructor.
     */
	function __construct(){

        add_filter( 'login_errors', array( $this, 'login_errors' ) );

	} // __construct()


    /**
     * Makes the error message generic.
     */ 
    function login_errors(){
      
      return __( 'Invalid username or password.', SECSAFE_SLUG );

    } // login_errors()


} // PolicyLoginErrors()
