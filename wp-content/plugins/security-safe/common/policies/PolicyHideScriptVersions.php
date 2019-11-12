<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyHideScriptVersions
 * @package SecuritySafe
 * @since 1.1.3
 */
class PolicyHideScriptVersions {


    /**
     * PolicyHideWPVersion constructor.
     */
	function __construct(){

        // Cache Busting
        add_action( 'upgrader_process_complete', array( $this, 'increase_cache_busting' ) , 10, 2 );

        // Remove Version From Scripts
        add_filter( 'style_loader_src', array( $this, 'css_js_version' ), 99999 );
        add_filter( 'script_loader_src', array( $this, 'css_js_version' ), 99999 );

	} // __construct()


    /** 
     * Remove All Versions From Enqueued Scripts
     * @param  string $src Original source of files with versions
     * @return string      Modified version
     * @since  1.1.3
     */
    function css_js_version( $src ) {

        global $SecuritySafe;

        $cache_buster = $SecuritySafe->get_cache_busting();

        $version = 'ver=' . date('Ymd') . $cache_buster;

        if ( strpos( $src, 'ver=' ) ) {
            $src = preg_replace("/ver=(.*)/", $version , $src );
        }

        return $src;

    } // css_js_version()



    /** 
     * Increase Cache Busting value wrapper
     */
    function increase_cache_busting( $upgrader_object, $options ) {

        global $SecuritySafe;

        $SecuritySafe->increase_cache_busting();

    } // increase_cache_busting()


} // PolicyHideScriptVersions()
