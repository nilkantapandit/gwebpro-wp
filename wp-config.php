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
define( 'DB_NAME', 'u464260987_gwebpro_new' );

/** Database username */
define( 'DB_USER', 'u464260987_gwebpro_new' );

/** Database password */
define( 'DB_PASSWORD', 'Gwebpro_new@123#$' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define( 'AUTH_KEY',         'FPB*M>:5!a~of)T]T4+q[--ut G}8t0F=F;`imC97EX<% ~OGyh8lrYyOWJ-OXY@' );
define( 'SECURE_AUTH_KEY',  '=o_P{+P2VB8ke{{ I7cPIA*%pXokc<N:UMXt&lm7)jO%udS| JRnsAN!5u[ maj4' );
define( 'LOGGED_IN_KEY',    '.B^gel;E(Q~8M9H#1KieKA10[AoH!n3#X($NH(6ofNs$EPS6=9<4On$Fi@<<#z6|' );
define( 'NONCE_KEY',        '(`J3?tOe8i:cRXxC&#Vp4EJxqWZ:diVSThK`riaVQ4ZYsAz}=|~jLv&yGNrzs0FP' );
define( 'AUTH_SALT',        'WjnDTK}Crt6wT,TFx<x&@tV_DxgG=c0j3PxiEn,-Oo?[$Z<VgqWPn4mKWZ]Tdzrs' );
define( 'SECURE_AUTH_SALT', 'ACp^FZ/Ak&X|=Ql0Figj+byyn-.T]x|Xop%.`b:$~&ll_>,Pw|ij`rC9a`Ek}qv*' );
define( 'LOGGED_IN_SALT',   'eiRvd?V[ ZG6wVoVAOjBK_FK@_wlP%4?6vvRr^?x6i!<{E+4A8fL1)SEnn^7W~4X' );
define( 'NONCE_SALT',       'g;fDqd~+z&026>5v`W/bN8R+^5H-rM7X@gL9Ji}1A`)9#q!z6piwF7XNIr,Xs-xp' );

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
$table_prefix = 'gp_';

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
