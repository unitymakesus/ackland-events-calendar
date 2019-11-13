<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyLog404s
 * @package SecuritySafe
 * @since  2.0.0
 */
class PolicyLog404s extends Firewall {


    /**
     * PolicyLog404s constructor.
     */
	function __construct() {

        // Run parent class constructor first
        parent::__construct();

        add_action( 'wp', array( $this, 'error' ) );

	} // __construct()


    /**
     * Logs the 404 error.
     * @since  2.0.0
     */ 
    function error() {

        if ( is_404() ) {

            if ( Yoda::is_blacklisted() ) { 
                
                // Block display of any 404 errors
                $this->block( '404s', __( 'IP is blacklisted.', SECSAFE_SLUG ) . ' [' . __LINE__ . ']' );
                return; 

            }

            $args = [];
            $args['type'] = '404s';
            $args['threats'] = Threats::is_404();

            // Add 404 Entry
            $this->add_entry( $args );

        } // is_404()

    } // error()


} // PolicyLog404s()
