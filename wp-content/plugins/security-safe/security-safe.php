<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * @package SecuritySafe
 * @version 1.2.1
 */
/*
 * Plugin Name: Security Safe
 * Plugin URI: https://sovstack.com/security-safe
 * Description: Security Safe - Security, Hardening, Auditing & Privacy
 * Author: Sovereign Stack, LLC
 * Author URI: https://sovstack.com
 * Version: 1.2.1
 * Text Domain: security-safe
 * Domain Path:  /languages
 * License: GPLv3 or later
 */
/*
Copyright (C) 2018  Sovereign Stack, LLC (email : support@sovstack.com)
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
// Create a helper function for easy SDK access.
function security_safe()
{
    global  $security_safe ;
    
    if ( !isset( $security_safe ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $security_safe = fs_dynamic_init( array(
            'id'             => '2439',
            'slug'           => 'security-safe',
            'type'           => 'plugin',
            'public_key'     => 'pk_d47b8181312a2a8b3191a732c0996',
            'is_premium'     => false,
            'has_addons'     => false,
            'has_paid_plans' => true,
            'menu'           => array(
            'slug'    => 'security-safe',
            'contact' => false,
        ),
            'is_live'        => true,
        ) );
    }
    
    return $security_safe;
}

// Init Freemius.
security_safe();
// Signal that SDK was initiated.
do_action( 'security_safe_loaded' );

if ( !function_exists( 'security_safe' ) ) {
    // Base Plugin
    $SecuritySafe = array(
        'name'              => 'Security Safe',
        'version'           => '1.2.1',
        'slug'              => 'security-safe',
        'options'           => 'securitysafe_options',
        'file'              => __FILE__,
        'dir'               => __DIR__,
        'dir_admin'         => __DIR__ . '/admin',
        'dir_common'        => __DIR__ . '/common',
        'dir_policies'      => __DIR__ . '/common/policies',
        'dir_lang'          => __DIR__ . '/languages',
        'url'               => plugin_dir_url( __FILE__ ),
        'url_author'        => 'https://sovstack.com/',
        'url_more_info'     => 'https://sovstack.com/security-safe/',
        'url_more_info_pro' => admin_url( 'admin.php?page=security-safe-pricing' ),
    );
    // Base Plugin
    require_once __DIR__ . '/common/Plugin.php';
    // Init Plugin
    add_action( 'init', __NAMESPACE__ . '\\Plugin::init' );
    // Clear PHP Cache on Upgrades
    add_filter(
        'upgrader_pre_install',
        __NAMESPACE__ . '\\Plugin::clear_php_cache',
        10,
        2
    );
    // Cleanup Plugin
    add_action( 'shutdown', __NAMESPACE__ . '\\Plugin::shutdown' );
}

// function_exists()