<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyDisableTextHighlight
 * @package SecuritySafe
 * @since 1.1.0
 */
class PolicyDisableTextHighlight {


    /**
     * PolicyDisableTextHighlight constructor.
     */
	function __construct(){

        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

	} // __construct()


    /**
     * Loads CSS To Prevent Highlighting.
     */ 
    function scripts(){

        global $SecuritySafe;

        $plugin = $SecuritySafe->plugin;
      
        // Load CSS
        wp_register_style( 'ss-pdth', $plugin['url'] . 'css/pdth.css', array(), $plugin['version'], 'all' );
        wp_enqueue_style( 'ss-pdth' );

        unset( $plugin );

    } // scripts()


} // PolicyDisableTextHighlight()
