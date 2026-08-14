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
define( 'DB_NAME', 'awakening' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'eEh`%G^: l$p}65B[7CB=[1VwfM(X<gef&R+NGZ=I#UGju]Rm5.OM2Ezhx?N!}^z' );
define( 'SECURE_AUTH_KEY',  'pgOXqY*+#Z F9A#6TcXT<|$dsAt!s#Dxa@`o4cK_jwl`p33lC]NfHee`%}:[%X8R' );
define( 'LOGGED_IN_KEY',    'gRw?98DaFr>[tcX1#;>yZaXPTAP,NgZS-P6x{p#7!rfl-.B]JeE62BQ*%~~FFbc,' );
define( 'NONCE_KEY',        'volqY$8FWvAVhr-[bgOss2JZMa5V1pA>G4bYx{0Z{!r^oAKJFMYU-`Bv[OD6}@dp' );
define( 'AUTH_SALT',        '$UBQq%J2-Hg<nT p7RE(rvh8P0m72lq-F+UN%M]_7U9okE!7[GWL{kK8N%6m)0Mg' );
define( 'SECURE_AUTH_SALT', '%{oC8.>_0FODiP/N)n/m4:-,G6M-/-s:<rHg{NR5j|l;[kf<b^B~RSKEs?^}owT}' );
define( 'LOGGED_IN_SALT',   '_`p5Tzp XRgZG-k-)/t*7#]i4_N[g+  BCLY[V5BOUhaCeQd[qfK y_tv|mSIn).' );
define( 'NONCE_SALT',       'aa]lo65V,.u$b4$R.([-zA72M=Yun`~m6A( 2b/u0G`^v?2uU-RNQs6z*|/gv#JU' );

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
