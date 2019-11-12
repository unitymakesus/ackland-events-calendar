<?php

namespace SecuritySafe;

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
      
      return 'Invalid username or password.';

    } // login_errors()


} // PolicyLoginErrors()
