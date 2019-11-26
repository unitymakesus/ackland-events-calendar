<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('DATABASE_NAME') );
/** MySQL database username */
define( 'DB_USER', getenv('DATABASE_USER') );
/** MySQL database password */
define( 'DB_PASSWORD', getenv('DATABASE_PASSWORD') );
/** MySQL hostname */
define( 'DB_HOST',  getenv(strtoupper(str_replace('-', '_', getenv('DATABASE_SERVICE_NAME'))).'_SERVICE_HOST') );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
	$_SERVER['HTTPS'] = 'on';
	$_SERVER['SERVER_PORT'] = 443;
}

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'csIXdwnMsoLmdUM8VCvp+7NIf3q89gKiJAgWton58Sz8emYZ/iJil+HdOlBZyM8MXT0wZFvB+toJ5IByubxPzw==');
define('SECURE_AUTH_KEY',  'YTdjpl4yPwat4cNQzfi5/RL3tEDpmDD4q2LAcKmJgvc2cAwXPz51qTNn2WbMC4hrW0SkH8ueA/kbOZe0C0Jpqg==');
define('LOGGED_IN_KEY',    '+xTm6+D6IeIQMVdzOYmoHXGW8XpVNDCEwReTkD7Pjzh8UU407qXkbnnNcBiHHWsY8v5xeuFYXHNRIrGpsYLE0w==');
define('NONCE_KEY',        'nOo44xJZhBXEv7MJYLk6tVuMroK5xpApWL2BmP/lQyZC8pdkDhGZDdbUeaaHPCTEH2veGFmLpch5RDFeRP6Uqg==');
define('AUTH_SALT',        'Ec5Gguy6EZdlP7Xn/Oa/0YAiubEVKykbMdKoPbss68BL4ejkXJyFAyZv2D218ns3hbS7U+PHVSAIRZnYlcl48g==');
define('SECURE_AUTH_SALT', 'M+nIIchX38ZcmF3yq0d/fdUBWL3u3PFMktyOQoKhrV5BhQ0TtfP+XBQyWe6hgW+iYLQFjBsF37IQdwsEK5kjJA==');
define('LOGGED_IN_SALT',   '0fc2e3Uyx5/xqUlzXRE21Wvkkx3kvLmHbF3BbeEiMHnSzXhXQWA/lO0ksZxd2ddTcxcDv4owMxzhU3HIYQ9j9w==');
define('NONCE_SALT',       '5ypfPOPmIn+/uM7bdHbBmm8ZAOiBCviHuQDjllOMaVyPl8UdntEyqRLgcI12QppSkT6m2Nb4LyF1qeSG+uTbbw==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

define( 'WP_DEBUG', true );
define( 'WP_POST_REVISIONS', false );
define( 'DISALLOW_FILE_EDIT', true );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
