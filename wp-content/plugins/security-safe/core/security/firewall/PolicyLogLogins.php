<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyLogLogins
 * @package SecuritySafe
 * @since  2.0.0
 */
class PolicyLogLogins extends Firewall {


    /**
     * PolicyLogLogins constructor.
     */
	function __construct() {

        // Run parent class constructor first
        parent::__construct();

        add_action( 'wp_login_failed', [ $this, 'failed' ] ); 
        add_action( 'wp_login',  [ $this, 'success' ] , 10, 2 );
        add_filter( 'authenticate', [ $this, 'blacklist_check' ], 30, 3 );  // Normal Login
        add_filter( 'xmlrpc_enabled', [ $this, 'disable' ], 60 );           // XML-RPC Login

	} // __construct()


    /**
     * Logs a Failed Login Attempt
     * @since  2.0.0
     * @uses  $this->record
     */
    public function failed( $username ) {

        $this->record( $username, 'failed' );

    } // failed()


    /**
     * Logs a successful login
     * @since  2.0.0
     * @uses  $this->record
     */
    public function success( $username, $user ) {

        $this->record( $username, 'success' );

    } // success()


    /**
     * Logs the login attempt.
     * @since  2.0.0
     */ 
    private function record( $username, $status ) {

        // Compatible with WP Super Cache and W3 Total Cache
        if( ! defined('DONOTCACHEPAGE') ) {

            define( 'DONOTCACHEPAGE', true );
        
        }

        $args = [];
        $args['type'] = 'logins';
        $args['username'] = ( $username ) ? filter_var( $username, FILTER_SANITIZE_STRING ) : '';
        $args['status'] = ( $status == 'success' ) ? 'success' : 'failed';

        if ( defined('XMLRPC_REQUEST') ) {

            $args['threats'] = 1;

            $args['details'] = ( $args['status'] == 'failed' ) ? __( 'XML-RPC Login Attempt.', SECSAFE_SLUG ) : __( 'XML-RPC Login Successful.', SECSAFE_SLUG );
            
        }

        // Check usernames
        $args['threats'] = ( isset( $args['threats'] ) ) ? $args['threats'] : Threats::is_username( $username );

        // Check Login
        $args['threats'] = ( $args['threats'] ) ? $args['threats'] : Threats::is_login( $username );

        // Log Login Attempt
        $this->add_entry( $args );

    } // record()


    /**
     * Checks if IP has been blacklisted and if so, prevents the login attempt.
     * @since  2.0.0
     * @uses  $this->block
     */
    public function blacklist_check( $user, $username, $password ) {

        if ( Yoda::is_blacklisted() ) { 
            
            $args = [];
            $args['type'] = 'logins';
            $args['details'] = __( 'IP is blacklisted.', SECSAFE_SLUG ) . '[' . __LINE__ . ']';
            $args['username'] = ( $username ) ? filter_var( $username, FILTER_SANITIZE_STRING ) : '';

            // Block the attempt
            $this->block( $args );

        }

        return $user;

    } // blacklist_check()


    /**
     * Checks to see if the XML-RPC request is blacklisted by IP
     * @since  2.2.0
     * @uses  $this->block
     */ 
    function xmlrpc_blacklist_check() {

        if ( Yoda::is_blacklisted() ) { 

            $args = [];
            $args['type'] = 'logins';
            $args['details'] = __( 'IP is blacklisted.', SECSAFE_SLUG ) . '[' . __LINE__ . ']';

            // Get Username
            $data = file_get_contents( 'php://input' );
            $xml= simplexml_load_string( $data );
            $username = ( $xml && isset( $xml->params->param[2]->value->string ) ) ? $xml->params->param[2]->value->string : '';
            $args['username'] = ( $username ) ? filter_var( $username, FILTER_SANITIZE_STRING ) : '';

            // Block the attempt
            $this->block( $args );

        }

    } // xmlrpc_blacklist_check()


} // PolicyLogLogins()
