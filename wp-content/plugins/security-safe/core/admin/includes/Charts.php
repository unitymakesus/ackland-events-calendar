<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class Charts
 * @package SecuritySafe
 */
class Charts {


    public static function display_charts( $args ) {

        $stats = ( isset( $args['date_days'] ) ) ? Self::get_stats( $args ) : false;

        // Bail if bad input
        if( ! $stats || ! isset( $args['charts'][0] ) ) { return; }

        $html = '';

        foreach ( $args['charts'] as $chart ) {

            if ( isset( $chart['id'] ) && isset( $chart['type'] ) ) {

                if ( $chart['type'] == 'line' ) {

                    $html .= Self::display_line_chart( $chart, $stats );

                }

                if ( $chart['type'] == 'pie' ) {
                    
                    $html .= Self::display_pie_chart( $chart, $stats );

                }

                if ( $chart['type'] == 'guage' ) {
                    
                    $html .= Self::display_guage_chart( $chart, $stats );

                }

            }

        } // foreach()
        
        if ( $html != '' ) {

            Self::dependencies();

            echo '<script>' . $html . '</script>';

        }

    } // display_charts()


    /**
     * Loads dependents for the charts.
     *
     * @since 2.0.0
     * @return  html
     */
    private static function display_line_chart( $chart = false, $stats = false ) {

        if ( ! $chart || ! $stats ) { return false; }

        $colors = $types = $groups = $columns = '';

        $count = count( $chart['columns'] );
        $num = 0;

        foreach( $chart['columns'] as $c ) {

            if ( isset( $c['color'] ) ){
            
                $colors .= '"' . $c['label'] . '": "' . $c['color'] . '",';

            }

            if ( isset( $c['type'] ) ){
            
                $types .= '"' . $c['label'] . '": "' . $c['type'] . '",';

            }

            $groups .= '"' . $c['label'] . '"';

            $num++;

            if ( $count != $num ) { 

                $groups .= ',';

            } 

            $columns .= $stats[ $c['id'] ];

        } // foreach()

        $var = str_replace( '-', '_', $chart['id'] );

        return '     
            var ' . $var . ' = c3.generate({
                bindto: "#' . $chart['id'] . '",
                data: {
                    x: "x",
                    xFormat: "%Y-%m-%d",
                    columns: [
                        ' . $stats['x'] . '
                        ' . $columns . '
                    ],
                    colors: {' . $colors . '},
                    types: {' . $types . '},
                },
                axis: {
                    x: {
                        type: "timeseries",
                        localtime: true,
                        tick: {
                            format: "%m-%d",
                            rotate: -45,
                            multiline: false
                        }
                      },
                    y: {
                        label: {
                            text: "' . $chart['y-label'] . '",
                            position: "outer-middle"
                        },
                    }
                }
            });
        ';

    } // display_line_chart()


    /**
     * Loads dependents for the charts.
     *
     * @since 2.0.0
     * @return  html
     */
    private static function display_pie_chart( $chart, $stats ) {

        if ( ! $chart || ! $stats ) { return false; }

        $columns = $colors = '';

        foreach( $chart['columns'] as $c ) {

            if ( isset( $c['color'] ) ){
            
                $colors .= '"' . $c['label'] . '": "' . $c['color'] . '",';

            }

            $columns .= $stats[ $c['id'] ];

        } // foreach()

        $var = str_replace( '-', '_', $chart['id'] );

        return '
            var ' . $var . ' = c3.generate({
                bindto: "#' . $chart['id'] . '",
                data: {
                    columns: [' . $columns . '],
                    type : "pie",
                    colors: {' . $colors . '},
                },
                pie: {
                    label: {
                        format: function (value, ratio, id) {
                            return d3.format("")(value);
                        }
                    }
                },
                tooltip: {
                    format: {
                        value: function (value, ratio, id) {
                            var percent = Math.round( 1000 * ratio ) / 10;
                            return ( percent + "% (" + value + ")" );
                        }
                    }
                }
            });
        ';

    } // display_pie_chart()


    private static function display_guage_chart( $chart, $stats ) {

        $total = $stats[ $chart['columns'][0]['id'] ];
        $total = ( substr( $total, -1 ) == ',' ) ? substr( $total, 0, -1) : $total;

        $used = $stats[ $chart['columns'][1]['id'] ];
        $used = ( substr( $used, -1 ) == ',' ) ? substr( $used, 0, -1) : $used;

        $var = str_replace( '-', '_', $chart['id'] );

        return '
        var total = ' . $total . ';
        var used = ' . $used . ';
        var ntotal = 0;
        var nused = 0;

        total.forEach(function(t) {

            if ( isNaN(t) == false ) {
                ntotal = ntotal + t;
            }

        });

        used.forEach(function(u) {

            if ( isNaN(u) == false ) {
                nused = nused + u;
            }

        });

        var percent = nused / ntotal;
        var percent = Math.round( 1000 * percent ) / 10;

        var ' . $var . ' = c3.generate({
                bindto: "#' . $chart['id'] . '",
                data: {
                    columns: [
                        ["' . $chart['columns'][1]['label'] . '", percent]
                    ],
                    type: "gauge"
                },
                color: {
                    pattern: ["#FF0000", "#F97600", "#F6C600", "#60B044"],
                    threshold: {
                        values: [85, 90, 95, 100]
                    }
                },
                size: {
                    height: 200
                }
            });';

    } // display_guage_chart()


    /**
     * Loads dependents for the charts.
     *
     * @since 2.0.0
     * @return  html
     */
    static function dependencies() {

        /** 
         * Get updated css/JS here: https://cdnjs.com/libraries
         * Current Installed Version
         * https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.0/c3.min.css
         * https://cdnjs.cloudflare.com/ajax/libs/d3/5.9.2/d3.min.js 
         * https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.0/c3.min.js
         */
        
        echo '
        <link rel="stylesheet" href="' . SECSAFE_URL_ADMIN_ASSETS . 'css/c3.min.css?ver=' . SECSAFE_VERSION . '" />
        <script src="' . SECSAFE_URL_ADMIN_ASSETS . 'js/d3.min.js?ver=' . SECSAFE_VERSION . '"></script>
        <script src="' . SECSAFE_URL_ADMIN_ASSETS . 'js/c3.min.js?ver=' . SECSAFE_VERSION . '"></script>
        ';

    } // dependencies()

    /**
     * The stats are pulled from columns defined in the first chart in the charts array.
     * @since  2.0.0
     * @return  array
     */ 
    private static function get_stats( $args = false ) {

        global $wpdb;

        $table = Yoda::get_table_stats();

        $start = esc_sql( $args['date_start'] );
        $end = esc_sql( $args['date_end'] );
        $day = $args['date_days_ago'];

        $query = "  SELECT * FROM `" . $table . "` 
                    WHERE `date` 
                    BETWEEN '$start' 
                    AND '$end'
                    ORDER BY `date` ASC";

        $results = $wpdb->get_results( $query );

        if ( $results ) {

            $stats = [];
            $dates = [];

            // Converting object to associative array 
            $results = json_decode( json_encode( $results ), true );

            $results_num = 0;

            while ( $day >= 0 ) {

                $date = ( $day == 0 ) ? date('Y-m-d') : date('Y-m-d', strtotime('-' . $day . ' days') );
                $day = $day - 1;

                if ( isset( $results[ $results_num ]['date'] ) ) {

                    $result_date = substr( $results[ $results_num ]['date'], 0, 10 );

                    if ( $result_date == $date ) {

                        $dates[ $date ] = $results[ $results_num ];

                        $results_num++;

                    }

                }

                foreach( $args['charts'][0]['columns'] as $column ) {

                    if ( $day == ( $args['date_days_ago'] - 1 ) ) {

                        // Beginning
                        $stats[ $column['id'] ] = '["' . $column['label'] . '", ';

                    }

                    $stats[ $column['id'] ] .= isset( $dates[ $date ] ) ? $dates[ $date ][ $column['db'] ] : 0;

                    if ( $day >= 0 ) {

                        // Comma
                        $stats[ $column['id'] ] .= ', ';

                    } else {

                        // End
                        $stats[ $column['id'] ] .= '],';

                    }

                } // foreach()

                if ( $day == ( $args['date_days_ago'] - 1 ) ) {

                    // Beginning
                    $stats['x'] = '["x", ';

                }

                $stats['x'] .= '"' . $date . '"';

                if ( $day >= 0 ) {

                    // Add Comma
                    $stats['x'] .= ', ';

                } else {

                    // End
                    $stats['x'] .= '],';

                }

            } // while()

            return $stats;

        }

        return false;

    } // get_stats()



} // Charts()
