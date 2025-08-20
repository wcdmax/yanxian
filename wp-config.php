<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'yanxian' );

/** Database username */
define( 'DB_USER', 'yanxian' );

/** Database password */
define( 'DB_PASSWORD', 'GBEkAWhsWYWzwyyS' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dV-Do0y%ldyW?PmE0Kp({2YDbRlfbp-t0p+<JOXxX972G[9VxEFKldax $,7v;}1' );
define( 'SECURE_AUTH_KEY',  '{b6@!p-.S=i}_&+spffrVG_ld0m,jof:mIZ&uHV;#t7Pzgy?FA>lojrV@><OM^ap' );
define( 'LOGGED_IN_KEY',    'pYT&KHN,3Q=mIm%/Fc`P$LM8M2XXw(YQQ=n$f1!Cc6/A=jHoxd1KT|>}-7x86):$' );
define( 'NONCE_KEY',        'Di+FS-IR#f$$[ vTEcH/mf;eSoxB+DH&PFf&*#`mOcPLe$t5KWX[VHpm|:Z._e@U' );
define( 'AUTH_SALT',        '!7(WnjP ][n]vM6yi_$IBq!/(e6r;jFbE%M6W=x2Gwg=HvvFkx;5!dzj8v(?1}-~' );
define( 'SECURE_AUTH_SALT', 'D2$(J|2k%00`rhJu+OzO862WoP7:<iBM~}:N+*.G ,`|}LJa2A#3i*>N;nhu7L-=' );
define( 'LOGGED_IN_SALT',   'P>9mvesW73>c,FvVSW.zj}ppgF{08oM?~&mUjFAxn%EJ$xjKKu,{f,NK4LALxXAw' );
define( 'NONCE_SALT',       '.Yu(@]]#`JCVSMuqwWvJ2anWKM_x(7=3C u>E6M}rFqUn;f` YQ[>B$39zQ/w7I*' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

/**
 * Memcached Configuration
 */
define( 'WP_CACHE', true );
define( 'WP_CACHE_KEY_SALT', 'yanxian_' );

// Memcached server configuration
global $memcached_servers;
$memcached_servers = array(
    array(
        '127.0.0.1',
        11211
    )
);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
