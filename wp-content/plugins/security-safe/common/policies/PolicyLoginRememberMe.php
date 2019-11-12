<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyLoginRememberMe
 * @package SecuritySafe
 */
class PolicyLoginRememberMe {


    /**
     * PolicyLoginRememberMe constructor.
     */
	function __construct(){

        // Clear Cache Attempt
        add_action( 'login_form', array( $this, 'login_form' ), 99 );

        // Clear Variable Attempt
        add_action( 'login_head', array( $this, 'reset' ), 99 );

	} // __construct()


    /**
     * Unsets the GET variable rememberme
     */
    public function reset() {

      // Remove the rememberme post value
      if( isset( $_POST['rememberme'] ) ) {

        unset( $_POST['rememberme'] );

      }

    } // reset()


    /**
     * Filters the html before it reaches the browser.
     */ 
    public function login_form() {

      ob_start( array( $this, 'remove' ) );

    } // login_form()


    /**
     * Removes the content from html
     */ 
    public function remove( $html ) {

      return preg_replace( '/<p class="forgetmenot">(.*)<\/p>/', '', $html );

    } // remove()


} // PolicyLoginRememberMe()
