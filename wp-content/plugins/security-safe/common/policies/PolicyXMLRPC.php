<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class PolicyXMLRPC
 * @package SecuritySafe
 */
class PolicyXMLRPC {


    /**
     * PolicyXMLRPC constructor.
     */
	function __construct(){

        // Disable XMLRPC
        add_filter( 'xmlrpc_enabled', '__return_false' );
        
        // Remove Link From Head
        remove_action( 'wp_head', 'rsd_link' );

	} // __construct()


} // PolicyXMLRPC()
