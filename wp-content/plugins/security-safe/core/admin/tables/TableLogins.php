<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

require_once( SECSAFE_DIR_ADMIN_TABLES . '/Table.php' );

/**
 * Class TableLogins
 * @package SecuritySafe
 */
final class TableLogins extends Table {


    /**
     * Set the type of data to display
     * 
     * @since  2.0.0
     */
    protected function set_type() {

        $this->type = 'logins';

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
            'date'          => __( 'Date', SECSAFE_SLUG ),
            'username'      => __( 'Username', SECSAFE_SLUG ),
            'ip'            => __( 'IP Address', SECSAFE_SLUG ),
            'user_agent'    => __( 'User Agent', SECSAFE_SLUG ),
            'status'        => __( 'Status', SECSAFE_SLUG ),
            'threats'       => __( 'Threat', SECSAFE_SLUG ),
            'details'       => __( 'Details', SECSAFE_SLUG )
        ];
        
        return $columns;

    } // get_columns()


    /**
     * Get the array of searchable columns in the database
     * @since  2.0.0
     * @return  array An unassociated array.
     */ 
    protected function get_searchable_columns() {
        
        $columns = [
            'uri',
            'ip',
            'username'
        ];
        
        return $columns;

    } // get_searchable_columns()


    protected function get_status() {

        return [ 
            //  'key'       => 'label'
                'success'   => __( 'Success', SECSAFE_SLUG ),
                'failed'    => __( 'Failed', SECSAFE_SLUG ),
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

        echo '
        <div class="table">
            <div class="tr">

                <div class="chart chart-logins-line td td-9 center">

                    <h3>' . sprintf( __( 'Login Attempts Over The Past %d Days', SECSAFE_SLUG ), $days ) . '</h3>
                    <div id="chart-line"></div>

                </div><div class="chart chart-logins-pie td td-3 center">

                    <h3>' . __( 'Login Distribution', SECSAFE_SLUG ) . '</h3>
                    <div id="chart-pie"></div>

                </div>

            </div>
        </div>';

        $charts = [];

        $columns = [
                        [
                            'id'            => 'total',
                            'label'         => __( 'Total', SECSAFE_SLUG ),
                            'color'         => '#aaaaaa',
                            'type'          => 'area-spline',
                            'db'            => 'logins'

                        ],
                        [
                            'id'            => 'threats',
                            'label'         => __( 'Threats', SECSAFE_SLUG ),
                            'color'         => '#f6c600',
                            'type'          => 'bar',
                            'db'            => 'logins_threats'
                        ],
                        [
                            'id'            => 'blocked',
                            'label'         => __( 'Blocked', SECSAFE_SLUG ),
                            'color'         => '#0073aa',
                            'type'          => 'bar',
                            'db'            => 'logins_blocked'
                        ],
                        [
                            'id'            => 'failed',
                            'label'         => __( 'Failed', SECSAFE_SLUG ),
                            'color'         => '#dc3232',
                            'type'          => 'bar',
                            'db'            => 'logins_failed'
                        ],
                        [
                            'id'            => 'success',
                            'label'         => __( 'Success', SECSAFE_SLUG ),
                            'color'         => '#029e45',
                            'type'          => 'bar',
                            'db'            => 'logins_success'
                        ]  
                    ];

        $charts[] = [ 
            'id'            => 'chart-line',
            'type'          => 'line',
            'columns'       => $columns,
            'y-label'       => __( '# Login Attempts', SECSAFE_SLUG )
        ];

        // Remove unused columns total, threats
        unset( $columns[0], $columns[1] );

        $charts[] = [ 
            'id'            => 'chart-pie',
            'type'          => 'pie',
            'columns'       => $columns
        ];

        $args = [
            'date_start'    => date('Y-m-d 00:00:00', strtotime('-' . $days_ago . ' days') ),
            'date_end'      => date( 'Y-m-d 23:59:59', time() ),
            'date_days'     => $days,
            'date_days_ago' => $days_ago,
            'charts'        => $charts
        ];
        

        // Load Charts
        Admin::load_charts( $args );

    } // display_charts()


} // TableLogins()
