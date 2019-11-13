<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyDisableRightClick
 * @package SecuritySafe
 * @since 1.1.0
 */
class PolicyDisableRightClick {


    /**
     * PolicyDisableRightClick constructor.
     */
	function __construct(){

        if ( ! is_admin() ) {

            add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

        }

	} // __construct()


    /**
     * Loads JS To Disable Right Click.
     */ 
    function scripts(){
        
        // JS File
        wp_enqueue_script( 'ss-pdrc', SECSAFE_URL_ASSETS . 'js/pdrc.js', array( 'jquery' ), SECSAFE_VERSION, true );

    } // scripts()


} // PolicyDisableRightClick()
