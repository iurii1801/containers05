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
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',         '(T`3N}Q{(;2+kqIM86];ffoF&Q35o8@*cZgW}lKpin.tZ2;6kw;Kf<UShtb}$7b1' );
define( 'SECURE_AUTH_KEY',  '0Ya?[yI&|P Jpn0B-@ERsMHW;r {us>9>[E0&zu$Fv<cPND{)BWB4]-Zm&M*`0)c' );
define( 'LOGGED_IN_KEY',    '9I:beFx@jEds)CuQa39wT,$:_M@N0wa+R`nBNrpKyH;3X?w0KE_KgI*#fKMG~XDq' );
define( 'NONCE_KEY',        'MLtX~=G80r/u2-5;k qYU8fDi)ThbJ<f?J^*5/AxHfYwXI2x,<RNy,)5mOLEr/tB' );
define( 'AUTH_SALT',        'fV6uiCnJg4bYi@yw3:Z#KEYAii[Wd1A!X>1ihVxU)_J(o=]d_oRvtmJ~gM8acR^B' );
define( 'SECURE_AUTH_SALT', '~B=C-VwhWHC1H/pDX@G2SkI!|S:o;OBO+b8O,Bvdu#/v$U!_XFI&WRH/vANH4uL>' );
define( 'LOGGED_IN_SALT',   '<pQ(,Jf.mD~|,I=Wy+1w*{h?x.NV38/<#lk,3Zf<(=C2XVF.;}9+}0 lP$p9Y%:y' );
define( 'NONCE_SALT',       ')Q7c&#TIpuiiG)or6n*|c^7[I!GI+N _rj[aIsT1!Zg.nR f@xw=`R*r$]4s#6~R' );

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
