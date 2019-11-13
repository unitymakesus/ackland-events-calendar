<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class PolicyLoginLocal
 * @package SecuritySafe
 */
class PolicyLoginLocal extends Firewall {

    var $setting_on = false;

    var $nonce = '_nonce_login_local';

    /**
     * PolicyLoginLocal constructor.
     */
	function __construct( $setting = false ) {

        // Run parent class constructor first
        parent::__construct();

        if ( $setting && ! defined('XMLRPC_REQUEST') ) {

            add_action( 'login_form', [ $this, 'add_nonce' ] ); // Main login
            add_filter( 'login_form_top', [ $this, 'add_nonce_login_form_top' ], 10, 2 ); // Login using wp_login_form()
            add_filter( 'authenticate', [ $this, 'verify_nonce' ], 30, 3 );

        }
        

	} // __construct()


    /**
     * This adds a nonce to the login form.
     * @since  2.2.0
     */ 
    function add_nonce() {

        // Prevent caching of this login page
        Janitor::prevent_caching();

        wp_nonce_field( SECSAFE_SLUG . '-login-local', $this->nonce );

    } // add_nonce()


    /**
     * This adds a nonce to the login form created by wp_login_form().
     * @since  2.2.3
     */ 
    function add_nonce_login_form_top( $content = '', $args = '' ) {

        ob_start();

        $this->add_nonce();

        $content .= ob_get_contents();

        ob_end_clean();

        return $content;

    } // add_nonce_front_end()


    /**
     * Verifies the nonce
     * @since  2.2.0
     */ 
    function verify_nonce( $user, $username, $password ) {

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            $nonce = ( isset( $_POST[ $this->nonce ] ) ) ? $_POST[ $this->nonce ] : false;

            if ( ! $nonce ) {

                $error = __( 'Error: Local login required and Nonce missing.', SECSAFE_SLUG ) . '[' . __LINE__ . ']';
            
            } else if ( ! isset( $_POST['_wp_http_referer'] ) ) {

                $error = __( 'Error: Local login required and Referer missing.', SECSAFE_SLUG ) . '[' . __LINE__ . ']';

            } else {

                // Check nonce les than 12 hours old
                if ( ! wp_verify_nonce( $nonce, SECSAFE_SLUG . '-login-local' ) ) {

                    $error = __( 'Error: Local login required and Nonce not valid.', SECSAFE_SLUG ) . '[' . __LINE__ . ']';

                }
                
            }
        
            if ( isset( $error ) ) {

                $args = [];
                $args['type'] = 'logins';
                $args['details'] = $error;
                $args['username'] = filter_var( $username, FILTER_SANITIZE_STRING );

                // Block the attempt
                $this->block( $args );

            }

        }
     
        return $user;
    
    } // verify_nonce()


} // PolicyLoginLocal()
