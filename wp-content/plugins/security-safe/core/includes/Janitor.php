<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }


/**
 * Class Janitor - Cleans up after the plugin
 * @package SecuritySafe
 * @since 2.0.0
 */
class Janitor {


    /**
     * Janitor constructor.
     */
	function __construct() {

        // Setup Tables & Crons on Plugin Enable
        register_activation_hook( SECSAFE_FILE, [ $this, 'enable_plugin' ] );
        add_action( 'upgrader_process_complete', [ $this, 'upgrade_complete' ], 10, 2);

        // Cleanup Settings, Database, & Crons on Plugin Disable
        register_deactivation_hook( SECSAFE_FILE, [ $this, 'disable_plugin' ] );

        // Add Cleanup Action
        add_action( 'secsafe_cleanup_tables_daily', [ $this, 'cleanup_tables' ] );

        // Schedule Cleanup Services
        if ( ! wp_next_scheduled ( 'secsafe_cleanup_tables_daily' ) ) {

            wp_schedule_event( time(), 'daily', 'secsafe_cleanup_tables_daily' );
        
        }

	} // __construct()


    /**
     * Run functions after upgrade
     * @since  2.0.1
     */ 
    public function upgrade_complete( $upgrader_object, $options ) {

        if ( 
            isset( $options['action'] ) && $options['action'] == 'update' && 
            isset( $options['type'] ) && $options['type'] == 'plugin' 
        ) {

            if ( isset( $options['plugins'] ) ) {

                if ( is_array() ) {

                    foreach( $options['plugins'] as $plugin ) {

                      if ( $plugin == SECSAFE_SLUG ) {

                        // Log Activity
                        $args = [];
                        $args['details'] = sprintf( __( '%s plugin updated.', SECSAFE_SLUG ), SECSAFE_NAME ) . '[2]';
                        $this->enable_plugin( $args );

                      }

                    } // foreach()

                } else {

                    /**
                     * @todo  I am making an assumption here that needs to be verified.
                     * I have noticed in the past the variable can be an array or a string depending 
                     * on how the update was initiated by the user. I wish WP would make this 
                     * functionality consistent. 
                     */ 
                    if ( $options['plugins'] == SECSAFE_SLUG ) {

                        // Log Activity
                        $args = [];
                        $args['details'] = sprintf( __( '%s plugin updated.', SECSAFE_SLUG ), SECSAFE_NAME ) . '[1]';
                        $this->enable_plugin( $args );

                    }

                }

            }

        }

    } // upgrade_complete()


    /**
     * Creates database tables
     * @since  2.0.0
     */
    public function enable_plugin( $args = []) {

        global $wp, $SecuritySafe;

        // Create Logs Table
        $this->create_table_logs();

        // Create Stats Table
        $this->create_table_stats();

        $args['type'] = '404s';
        $args['threats'] = 0;
        $args['status'] = 'test';
        $args['details'] = ( isset( $args['details'] ) ) ? $args['details'] : sprintf( __( '%s plugin enabled.', SECSAFE_SLUG ), SECSAFE_NAME );;
        
        // Log Test 404
        Self::add_entry( $args );

        // Log Actual Activity
        Self::log_activity( $args );
        
    } // enable_plugin()


    /**
     * Removes the settings from the database on plugin deactivation
     * @since  0.3.5
     */
    public function disable_plugin() {

        // Remove Cron
        wp_clear_scheduled_hook( 'secsafe_cleanup_tables_daily' );

        $settings = get_option( SECSAFE_OPTIONS );

        // Check to see if the user wants us to cleanup the data
        if( isset( $settings['general']['cleanup'] ) && $settings['general']['cleanup'] == '1' ) {

            // Delete Settings
            delete_option( SECSAFE_OPTIONS );

            // Delete Tables
            $this->drop_table( SECSAFE_DB_FIREWALL );
            $this->drop_table( SECSAFE_DB_STATS );

        } else {

            // Log Activity
            $args = [];
            $args['details'] = sprintf( __( '%s plugin disabled.', SECSAFE_SLUG ), SECSAFE_NAME );
            Self::log_activity( $args );

        } // isset()

    } // disable_plugin()


    public static function log_activity( $args = [] ) {

        $user = wp_get_current_user();

        // Log Actual Activity
        $args['type'] = 'activity';
        $args['threats'] = '0';
        $args['user_agent'] = Yoda::get_user_agent();
        $args['username'] = ( isset( $user->user_login ) ) ? $user->user_login : 'unknown';
        $args['ip'] = Yoda::get_ip();
        $args['status'] = ( defined( 'DOING_CRON' ) ) ? 'automatic' : 'unknown';
        $args['status'] = ( $args['status'] == 'unknown' && isset( $user->user_login ) ) ? 'manual' : $args['status'];
        
        Self::add_entry( $args );

    } // log_activity()


    /**
     * TODO:Finish this functionality and create a cron to run it daily.
     * This cleans up the database when the daily cron runs
     * @since 2.0.0
     */ 
    public function cleanup_tables() {

        Self::cleanup_type( '404s' );
        Self::cleanup_type( 'logins' );
        Self::cleanup_type( 'activity' );
        Self::expire_type( 'allow_deny' );

    } // cleanup_tables()


    /**
     * Deletes all rows in excess of limit for a specific type
     * @since 2.0.0
     */ 
    static private function cleanup_type( $type ) {

        global $wpdb;

        $types = Yoda::get_types();

        // Require Valid Type
        if ( isset( $types[ $type ] ) ) {

            $args = [];

            $limit = Yoda::get_display_limits( $type, true );

            $table_main = Yoda::get_table_main();

            $query = "SELECT COUNT(type) FROM $table_main WHERE type = '$type'";

            // Count how many exist
            $exists = (int) $wpdb->get_var( $query );

            $args['details'] = '[';

            // If more than limit
            if ( $exists > $limit ) {

                // Calculate amount to delete
                $delete = intval( $exists - $limit );

                $query = "DELETE FROM $table_main WHERE type = '$type' ORDER BY date ASC LIMIT $delete";

                $result = $wpdb->query( $query );

                $args['details'] .= (int)$result . '-' . $exists . '-' . $limit;

            } else {

                $args['details'] = '0-' . $exists . '-' . $limit;

            }

            $args['details'] .= '] ' . $type . ' ' . __( 'database maintenance', SECSAFE_SLUG ) . '.';

            // Log Activity
            Self::log_activity( $args );

        }

    } // cleanup_type()


    /**
     * Deletes all rows for a specific type that have an expire date that is older than now.
     * @since 2.0.0
     */ 
    private function expire_type( $type ) {

        global $wpdb;

        $args = [];
            
        // Cleanup Valid Types
        $types = Yoda::get_types();

        if ( isset( $types[ $type ] ) ) {

            $table_main = Yoda::get_table_main();

            $now = date('Y-m-d H:i:s');

            $query = "DELETE FROM $table_main WHERE type = '$type' AND status = 'deny' AND date_expire < '$now' AND date_expire != '0000-00-00 00:00:00'";

            $result = $wpdb->query( $query );

            $args['details'] = '[' . (int)$result . '] ' . $type . ' ' . __( 'database maintenance', SECSAFE_SLUG ) . '.';

        } else {

            $args['details'] = sprintf( __( 'Error: %s is not a valid type.', SECSAFE_SLUG ), $type );

        }

        // Log Activity
        Self::log_activity( $args );

    } // expire_type()


    /**
     * Creates Firewall Table 
     * @since  2.0.0
     */
    private function create_table_logs() {

        global $wpdb;

        $table_main = Yoda::get_table_main();

        // Must have two spaces after PRIMARY KEY, UNIQUE and INDEX
        $wpdb->query( "CREATE TABLE IF NOT EXISTS $table_main (
            ID BIGINT NOT NULL AUTO_INCREMENT,
            type VARCHAR(10) NOT NULL default '',
            date DATETIME NOT NULL,
            date_expire DATETIME NOT NULL,
            ip VARCHAR(50) NOT NULL default '',
            username VARCHAR(50) NOT NULL default '',
            uri VARCHAR(512) NOT NULL,
            referer VARCHAR(512) NOT NULL default '',
            user_agent VARCHAR(512) NOT NULL default '',
            threats TINYINT(1) NOT NULL default '0',
            status VARCHAR(10) NOT NULL default '',
            details VARCHAR(512) NOT NULL default '',
            PRIMARY KEY  (ID),
            UNIQUE  (ID),
            INDEX  (type, status)
        ) DEFAULT CHARSET=utf8;" ); 

    } // create_table_logs()


    /**
     * Creates Stats Table
     * @since  2.0.0
     */
    private function create_table_stats() {

        global $wpdb;

        $table_stats = Yoda::get_table_stats();

        // Must have two spaces after PRIMARY KEY and UNIQUE
        $wpdb->query( "CREATE TABLE IF NOT EXISTS $table_stats (
            date DATETIME NOT NULL,
            404s BIGINT NOT NULL default '0',
            404s_threats BIGINT NOT NULL default '0',
            blocked BIGINT NOT NULL default '0',
            threats BIGINT NOT NULL default '0',
            logins BIGINT NOT NULL default '0',
            logins_failed BIGINT NOT NULL default '0',
            logins_success BIGINT NOT NULL default '0',
            logins_threats BIGINT NOT NULL default '0',
            logins_blocked BIGINT NOT NULL default '0',
            PRIMARY KEY  (date),
            UNIQUE  (date)
        ) DEFAULT CHARSET=utf8;" ); 

    } // create_table_stats()


    /**
     * Drop table in the database
     * @since  2.0.0
     */
    private function drop_table( $table ) {

        global $wpdb;

        // Only Our Tables
        $tables = [
            SECSAFE_DB_FIREWALL, 
            SECSAFE_DB_STATS ];

        if ( in_array( $table, $tables ) ) {

            $table = $wpdb->prefix . $table;

            return $wpdb->query( "DROP TABLE IF EXISTS {$table}" );

        }

        return false;

    } // drop_table()


    /**
     * Add entry to database
     * @since  2.0.0
     */ 
    static public function add_entry( $args ) {

        global $wpdb;

        $args = ( isset( $args['type'] ) ) ? $args : [];
        $type = ( isset( $args['type'] ) ) ? $args['type'] : false;
        $types = Yoda::get_types();
        $result = false; // Default

        // Require Valid Type
        if ( isset( $types[ $type ] ) ) {

            /**
             * Statically set for now
             * @todo  log all wp cron activity not just Security Safe's
             */
            $args['date'] = current_time('mysql');
            
            if ( 
                $args['type'] != 'activity' && 
                $args['type'] != 'allow_deny' 
            ) {

                $args['uri'] = ( isset( $_SERVER['REQUEST_URI'] ) ) ? filter_var( $_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL ) : '';
                $args['referer'] = ( isset( $_SERVER['HTTP_REFERER'] ) ) ? filter_var( $_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL ) : '';
                $args['user_agent'] = Yoda::get_user_agent();
                $args['ip'] = Yoda::get_ip();

                $args['threats'] = ( isset( $args['threats'] ) && $args['threats'] ) ? 1 : 0;

                // Record Stats
                Self::add_stats( $args );

            }

            // Trim Data
            $targs = [];

            foreach( $args as $key => $value ) {

                // Limit to 512 Characters
                $targs[ $key ] = substr( $value, 0, 512 );

            }

            // Add data to DB and insert() is sanitized by WP
            $result = $wpdb->insert( Yoda::get_table_main(), $targs );

            if ( ! $result ) {

                // Create Logs Table
                Self::create_table_logs();

                // Try Again now that a table exist
                $result = $wpdb->insert( Yoda::get_table_main(), $targs );
                
            }
            
        }

        return $result;

    } // add_entry()


    /**
     * Add stats into the db
     * @since  2.0.0
     */
    static function add_stats( $args ) {

        global $wpdb;

        $date = date('Y-m-d 00:00:00', strtotime( $args['date'] ) );
        $hour = date('Y-m-d H:00:00', strtotime( $args['date'] ) );

        $stats['blocked'] = $blocked = ( isset( $args['status'] ) && $args['status'] == 'blocked' ) ? 1 : 0;
        $stats['threats'] = $threats = ( isset( $args['threats'] ) && $args['threats'] ) ? 1 : 0;

        $stats['404s'] = $e404s = ( $args['type'] == '404s' ) ? 1 : 0;
        $stats['404s_threats'] = $e404s_threats = ( $threats && $args['type'] == '404s' ) ? 1 : 0;

        $stats['logins'] = $logins = ( isset( $args['type'] ) && $args['type'] == 'logins' ) ? 1 : 0;
        $stats['logins_failed'] = $logins_failed = ( $logins && isset( $args['status'] ) && $args['status'] == 'failed' ) ? 1 : 0;
        $stats['logins_success'] = $logins_success = ( $logins && isset( $args['status'] ) && $args['status'] == 'success' ) ? 1 : 0;
        $stats['logins_threats'] = $logins_threats = ( $logins && $threats ) ? 1 : 0;
        $stats['logins_blocked'] = $logins_blocked = ( $logins && $blocked ) ? 1 : 0;

        // Get the current day's stats
        $exist = '';
        $table = Yoda::get_table_stats();

        // Update 
        $query = "
            UPDATE $table 
            SET 404s = 404s + $e404s,
            404s_threats = 404s_threats + $e404s_threats,
            blocked = blocked + $blocked,
            threats = threats + $threats,
            logins = logins + $logins,
            logins_failed = logins_failed + $logins_failed,
            logins_success = logins_success + $logins_success,
            logins_threats = logins_threats + $logins_threats,
            logins_blocked = logins_blocked + $logins_blocked
        ";

        $query_date = $query . " WHERE date like '$date'";

        $exist = $wpdb->query( $query_date );

        if ( ! $exist ) {

            $stats['date'] = $date;

            // Add
            $result = $wpdb->insert( Yoda::get_table_stats(), $stats );

            if ( ! $result ) {

                // Create Stats Table
                Self::create_table_stats();

                // Try Again now that a table exist
                $result = $wpdb->insert( Yoda::get_table_stats(), $stats );

            }

        }

    } // add_stats()


    /**
     * Prevent plugins like WP Super Cache and W3TC from caching any data on this page.
     * @since  2.2.3
     */ 
    static function prevent_caching() {

        if ( ! defined( 'DONOTCACHEOBJECT' ) ) {

            define( 'DONOTCACHEOBJECT', true );

        }

        if ( ! defined( 'DONOTCACHEDB' ) ) {

            define( 'DONOTCACHEDB', true );
            
        }

        if ( ! defined( 'DONOTCACHEPAGE' ) ) {

            define( 'DONOTCACHEPAGE', true );
            
        }

    } // prevent_caching()


    /**
     * Writes to debug.log for troubleshooting
     * @param string $message Message entered into the log
     * @param string $file Location of the file where the error occured
     * @param string $line Line number of where the error occured
     * @return void
     * @since 0.1.0
     */
     public static function log( $message = false, $file = false, $line = false ) {

        if ( ! SECSAFE_DEBUG ) { return; }

        $message = ( $message ) ? $message : 'Error: Log Message not defined!';
        $message .= ( $file && $line ) ? ' - ' . 'Occurred on line ' . $line . ' in file ' . $file : '';

        error_log( date( 'Y-M-j h:m:s' ) . " - " . $message . "\n", 3, SECSAFE_DIR . '/debug.log' );

    } // log()


} // Janitor()
