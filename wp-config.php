<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
switch ($_SERVER['SERVER_NAME']) {
	/** Production Server */
     case 'thepantryseattle.com':
          define('DB_NAME', 'wordpress');
          define('DB_USER', 'wordpress-user');
          define('DB_PASSWORD', 'Tm7hTMFsdsvs3zEQauAN');
          define('DB_HOST', 'thepantry-jan2020.cxjfm6fxwyci.us-east-1.rds.amazonaws.com:3306');
          define('DB_CHARSET', 'utf8');
          define('DB_COLLATE', '');
          define('WP_DEBUG', false);     
     break;
     /** Development Server */    
     case 'pantrynew':
          define('DB_NAME', 'wp_pantrynew');
          define('DB_USER', 'wp_pantrynew');
          define('DB_PASSWORD', 'wp_pantrynew');
          define('DB_HOST', 'localhost');
          define('DB_CHARSET', 'utf8');
          define('DB_COLLATE', '');
          define('WP_DEBUG', true);  
     break;
     /** Development Server */    
     case 'thepantry-test':
          define('DB_NAME', 'wordpress_staging');
          define('DB_USER', 'wordpress-user');
          define('DB_PASSWORD', 'Tm7hTMFsdsvs3zEQauAN');
          define('DB_HOST', 'thepantry-jan2020.cxjfm6fxwyci.us-east-1.rds.amazonaws.com:3306');
          define('DB_CHARSET', 'utf8');
          define('DB_COLLATE', '');
          define('WP_DEBUG', true);  
     break;
     /** Staging Server */
     case 'pantry.deicreative.com':
			define( 'DB_NAME', 'nbb1645_pantry' );
			define('DB_USER', 'nbb1645_deicreative');
			define('DB_PASSWORD', 'Y%jkta2BFhai');
			define( 'DB_HOST', 'localhost' );
          define('DB_CHARSET', 'utf8');
          define('DB_COLLATE', '');
          define('WP_DEBUG', false);
     break;
} 

define( 'AS3CF_SETTINGS', serialize( array(
    'provider' => 'aws',
    'access-key-id' => 'AKIAIKTNKTAHQAPNPIRA',
    'secret-access-key' => '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',
) ) );
	

if ( $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' )
{
        $_SERVER['HTTPS']       = 'on';
        $_SERVER['SERVER_PORT'] = '443';
        define('FORCE_SSL_ADMIN', true);
}

if ( isset($_SERVER['HTTP_X_FORWARDED_HOST']) ) {
        $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
}
 

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'sEyHI=@~C&8+l$Bgn1Z;*z~yi}01`i~xe6S^TS[qhj49NF?VcZTDm-rwK(!J{;BK' );
define( 'SECURE_AUTH_KEY',  'c*=%dZiq! {NetfdUL#!HA,i`v.k6L3Mgj0D 4>*s}is)D`kB_~og9Saef&eJC,$' );
define( 'LOGGED_IN_KEY',    'Ve!pVF]b3k!_oKd !`)9L4{N1ujl,3tvW.0]4Fjb/()oZmj514%D;>.:Ea%l_X`g' );
define( 'NONCE_KEY',        '5 2,9p@NQ#nel=`^]T1(dK=-VpvP{vCrXKl<jtqkPZX;&$PX4?$[LEwuUgZmQq&P' );
define( 'AUTH_SALT',        '2D]3&cW<U=)2xV3n:}~Ql1TT]$x~:R!Yl.v*3JNL3gPh[[^.~u-x6#Y(ag4ZCk`^' );
define( 'SECURE_AUTH_SALT', '[ux!PxPe?VnU@X%?pN|#njmST 3N<yWf&dqEKf)_z^JYxDuQkrW}A#GWrB/ &3-n' );
define( 'LOGGED_IN_SALT',   '4b|&I<VovT[P(%Ky2Hm[{uDU`5R3xL)[~H}1x[9IwMrN;;hM8A3!l7Py~5o_i`u2' );
define( 'NONCE_SALT',       '`J@{NcPL7x|G{ECZTI0?P}9FzVo~ad%a#V[zyZ~{qq*g3N2??/C[@Q[YV5ZF;2u,' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

define('WP_MEMORY_LIMIT', '256M');


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
