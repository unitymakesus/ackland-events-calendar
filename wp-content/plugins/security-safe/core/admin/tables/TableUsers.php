<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

Use \WP_Users_List_Table;

if ( ! class_exists( 'WP_Users_List_Table' ) ) {

    require_once( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' );

}

/**
 * Class TableUsers
 * @package SecuritySafe
 */
final class TableUsers extends WP_Users_List_Table {



} // TableUsers()
