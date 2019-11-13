<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

require_once( SECSAFE_DIR_ADMIN_TABLES . '/Table.php' );

/**
 * Class TableFilePerms
 * @package SecuritySafe
 */
final class TableFilePerms extends Table {


    /**
     * Set the type of data to display
     * 
     * @since  2.0.0
     */
    protected function set_type() {

        $this->type = '404s';

    } // set_type()

    /**
     * Get a list of columns. The format is:
     * 'internal-name' => 'Title'
     *
     * @package WordPress
     * @since 3.1.0
     * @abstract
     *
     * @return array
     */
    function get_columns() {
        
        $columns = [
            'location'      => __( 'Relative Location', SECSAFE_SLUG ),
            'type'          => __( 'Type', SECSAFE_SLUG ),
            'current'       => __( 'Current', SECSAFE_SLUG ),
            'status'        => __( 'Status', SECSAFE_SLUG ),
            'modify'        => __( 'Modify', SECSAFE_SLUG )
        ];
        
        return $columns;

    } // get_columns()


    /**
     * Get the array of searchable columns in the database
     * @since  2.0.0
     * @return  array An unassociated array.
     */ 
    protected function get_searchable_columns() {
        
        return [];

    } // get_searchable_columns()


    function get_sortable_columns() {

        return [];

    } // get_sortable_columns()


} // Table404s()
