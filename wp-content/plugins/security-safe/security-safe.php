<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( !defined( 'ABSPATH' ) ) {
    die;
}
// Do not move the following constants to Yoda
define( 'SECSAFE_TIME_START', microtime( true ) );
define( 'SECSAFE_DEBUG', false );
define( 'SECSAFE_FILE', __FILE__ );
define( 'SECSAFE_DIR', __DIR__ );
define( 'SECSAFE_DIR_CORE', SECSAFE_DIR . '/core' );
define( 'SECSAFE_DIR_INCLUDES', SECSAFE_DIR_CORE . '/includes' );
// Load Yoda Before We Translate
require_once SECSAFE_DIR_INCLUDES . '/Yoda.php';
Yoda::set_constants();
define( 'SECSAFE_VERSION', '2.2.3' );
define( 'SECSAFE_DESC', __( 'Firewall, Security Hardening, Auditing & Privacy', SECSAFE_SLUG ) );
/**
 * WP Security Safe Plugin.
 *
 * @package   SovereignStack\SecuritySafe
 * @copyright Copyright (C) 2018-2019, Sovereign Stack, LLC - support@sovstack.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: WP Security Safe
 * Version:     2.2.3
 * Plugin URI: https://sovstack.com/security-safe
 * Description: Firewall, Security Hardening, Auditing & Privacy
 * Author: Sovereign Stack, LLC
 * Author URI: https://sovstack.com
 * Text Domain: security-safe
 * Domain Path: /languages/
 * License:     GPL v3
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !function_exists( 'security_safe' ) ) {
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
                'premium_suffix' => '',
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
}

// Load Janitor
require_once SECSAFE_DIR_INCLUDES . '/Janitor.php';
$Janitor = new Janitor();
// Load Plugin Core
require_once SECSAFE_DIR_CORE . '/Plugin.php';
// Load Security
require_once SECSAFE_DIR_INCLUDES . '/Threats.php';
require_once SECSAFE_DIR_FIREWALL . '/Firewall.php';
require_once SECSAFE_DIR_SECURITY . '/Security.php';
// Init Plugin
add_action( 'plugins_loaded', __NAMESPACE__ . '\\Plugin::init' );
// Clear PHP Cache on Upgrades
add_filter(
    'upgrader_pre_install',
    __NAMESPACE__ . '\\Plugin::clear_php_cache',
    10,
    2
);