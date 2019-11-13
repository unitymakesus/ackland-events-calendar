<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

require_once( SECSAFE_DIR_ADMIN_TABLES . '/Table.php' );

/**
 * Class Table404s
 * @package SecuritySafe
 */
final class Table404s extends Table {


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
        
        return [
            'date'          => __( 'Date / Time', SECSAFE_SLUG ),
            'uri'           => __( 'URL', SECSAFE_SLUG ),
            'user_agent'    => __( 'User Agent', SECSAFE_SLUG ),
            'referer'       => __( 'HTTP Referer', SECSAFE_SLUG ),
            'ip'            => __( 'IP Address', SECSAFE_SLUG ),
            'status'        => __( 'Status', SECSAFE_SLUG ),
            'threats'       => __( 'Threat', SECSAFE_SLUG )
        ];


    } // get_columns()


    /**
     * Get the array of searchable columns in the database
     * @since  2.0.0
     * @return  array An unassociated array.
     */ 
    protected function get_searchable_columns() {
        
        return [
            'uri',
            'ip',
            'referer'
        ];

    } // get_searchable_columns()


    protected function get_status() {

        return [ 
            //  'value'     => 'label'
                'blocked'   => __( 'Blocked', SECSAFE_SLUG )
            ];

    } // get_status()


    /**
     * Add filters and per_page options
     */ 
    protected function bulk_actions( $which = '' ) {

        $this->bulk_actions_load( $which );

    } // bulk_actions()


    public function display_charts() {

        if ( $this->hide_charts() ) { return; }

        $days = 7;
        $days_ago = $days - 1;

        $charts = [];

        $columns = [
                        [
                            'id'            => 'errors',
                            'label'         => __( '404 Errors', SECSAFE_SLUG ),
                            'color'         => '#dc3232',
                            'type'          => 'area-spline',
                            'db'            => '404s'

                        ]
                    ];

        $charts[] = [ 
            'id'            => 'chart-line',
            'type'          => 'line',
            'columns'       => $columns,
            'y-label'       => __( '# 404 Errors', SECSAFE_SLUG )
        ];

        $args = [
            'date_start'    => date( 'Y-m-d 00:00:00', strtotime( '-' . $days_ago . ' days' ) ),
            'date_end'      => date( 'Y-m-d 23:59:59', time() ),
            'date_days'     => $days,
            'date_days_ago' => $days_ago,
            'charts'        => $charts
        ];

        echo '
        <div class="table">
            <div class="tr">

                <div class="chart chart-404s-line td td-12 center">

                    <h3>' . sprintf( __( '404 Errors Over The Past %d Days', SECSAFE_SLUG ), $days ) . '</h3>
                    <p>' . substr( $args['date_start'], 0, 10 ) . ' - ' . substr( $args['date_end'], 0, 10 ) . '
                    <div id="chart-line"></div>

                </div>

            </div>
        </div>';
        

        // Load Charts
        Admin::load_charts( $args );

    } // display_charts()


} // Table404s()
