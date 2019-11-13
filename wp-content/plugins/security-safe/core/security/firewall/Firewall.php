<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class Firewall
 * @package SecuritySafe
 * @since 2.0.0
 */
class Firewall {


    /**
     * Firewall constructor.
     */
	function __construct() {

	} // __construct()


    /**
     * Retrieves entries from database
     * @since  2.0.0
     */
    protected function get_entries( $args ) {

        global $wpdb;

        if ( 
            $args['type'] == 'allow_deny' && 
            isset( $args['status'] ) && 
            in_array( $args['status'], [ 'allow', 'deny' ] ) 
        ) {

            $type = $args['type'];
            $status = $args['status'];
            $ip = Yoda::get_ip();
            $table_main = Yoda::get_table_main();

            // Needs to be sanitized
            return $wpdb->get_results( "SELECT * FROM $table_main WHERE type = '$type' AND status = '$status' AND ip = '$ip'", ARRAY_A );

        }

    } // get_entries()



    /**
     * Add entry to database
     * @todo  Update references to Janitor instead of object
     * @since  2.0.0
     */ 
    protected function add_entry( $args ) {

        Janitor::add_entry( $args );

    } // add_entry()


    /**
     * Logs the blocked attempt.
     * @since  2.0.0
     */ 
    protected function block( $args = [] ) {

        global $wpdb;

        // Bail if whitelisted
        if ( Yoda::is_whitelisted() ) { return; }

        // Compatible with WP Super Cache and W3 Total Cache
        if( ! defined('DONOTCACHEPAGE') ) {

            define( 'DONOTCACHEPAGE', true );
        
        }

        $args['status']     = 'blocked';
        $args['threats']    = 1;

        // Add blocked Entry
        $result = $this->add_entry( $args );

        $message = sprintf( __( '%s: Access blocked.', SECSAFE_SLUG ), SECSAFE_NAME );
        $message .= ( SECSAFE_DEBUG ) ? ' - ' . $args['type'] . ': ' . $args['details'] : '';

        status_header( '406', $message );

        // Block Attempt
        die( $message );

    } // block()



    /**
     * Logs the blocked attempt.
     * @since  2.0.0
     */ 
    protected function threat( $type, $details = '' ) {

        global $wpdb;

        // Bail if whitelisted
        if ( Yoda::is_whitelisted() ) { return; }

        // Compatible with WP Super Cache and W3 Total Cache
        if( ! defined('DONOTCACHEPAGE') ) {

            define( 'DONOTCACHEPAGE', true );
        
        }

        $args = [];
        $args['type']       = $type;
        $args['details']    = ( $details ) ? $details : '';
        $args['threats']    = 1;

        // Add blocked Entry
        $this->add_entry( $args );

    } // threat()


    /**
     * Detrmine if user is whitelisted
     * @since  2.0.0
     */
    public function is_whitelisted() {

        $args = [];
        $args['type'] = 'allow_deny';
        $args['status'] = 'allow';

        $whitelisted = $this->get_entries( $args );

        return ( isset( $whitelisted[0] ) ) ? true : false;

    } // is_whitelisted()


    /**
     * Detrmine if user is blacklisted
     * @since  2.0.0
     */
    public function is_blacklisted() {

        $args = [];
        $args['type'] = 'allow_deny';
        $args['status'] = 'deny';

        $blacklisted = $this->get_entries( $args );

        return ( isset( $blacklisted[0] ) ) ? true : false;

    } // is_blacklisted()



} // Firewall()
