<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class PolicyXMLRPC
 * @package SecuritySafe
 */
class PolicyXMLRPC extends Firewall {

    var $setting_on = false;

    /**
     * PolicyXMLRPC constructor.
     */
	function __construct( $setting = false ){

        if ( $setting ) {

            add_filter( 'xmlrpc_enabled', [ $this, 'disable' ], 50 );
            
            // Remove Link From Head
            remove_action( 'wp_head', 'rsd_link' );

        }

	} // __construct()


    function disable() {

        $args = [];
        $args['type'] = 'logins';
        $args['details'] = __( 'XML-RPC Disabled.', SECSAFE_SLUG );

        // Get Username
        $data = file_get_contents( 'php://input' );
        $xml= simplexml_load_string( $data );
        $username = ( $xml && isset( $xml->params->param[2]->value->string ) ) ? $xml->params->param[2]->value->string : 'unknown';
        $args['username'] = filter_var( $username, FILTER_SANITIZE_STRING );
        
        // Block the attempt
        $this->block( $args );

    } // disable()


} // PolicyXMLRPC()
