<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class PolicyWordPressVersionFiles
 * @package SecuritySafe
 * @since 1.1.4
 */
class PolicyWordPressVersionFiles {


    /**
     * PolicyWordPressVersionFiles constructor.
     */
	function __construct(){

        add_action( 'upgrader_process_complete', array( $this, 'protect_files' ) , 10, 2 );

	} // __construct()

    
    /**
     * Changes the permissions of each file so that the world cannot read them.
     */
    public function protect_files( $upgrader_object, $options ) {

        if ( $options['action'] == 'update' && $options['type'] == 'core' ) {
            
            //echo 'Checking WordPress core permissions.<br />';

            $this->set_permissions( ABSPATH . 'readme.html' );
            $this->set_permissions( ABSPATH . 'license.txt' );

        } // $options['action']
        
    } // protect_files()



    /** 
     * Set Permissions For File or Directory
     * @param $path Absolute path to file or directory
     */
    private function set_permissions( $path ) {

        // Cleanup Path
        $path = str_replace( array( '/./', '////', '///', '//' ), '/', $path );

        if ( file_exists( $path ) ) {

            $result = chmod( $path, 0640 );

        }

        //echo $path .' - Updated permissions to ' . $perm . '.<br />';

        // Cleanup Memory
        unset( $path, $result );

    } // set_permissions()


} // PolicyWordPressVersionFiles()
