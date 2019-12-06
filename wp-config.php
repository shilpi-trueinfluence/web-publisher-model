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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'K2-7?4MpBZwg)bE$DMZ^E|Mib-C 0uIRzZN5Z=:PAS#w j&`=5`VP(ob<21CByie' );
define( 'SECURE_AUTH_KEY',  'X2ghd=MX,$!&>{#iS?mM_d:KE){$rp)6##hbG9I~ekq!}sayt<&(97k1W],DkAGL' );
define( 'LOGGED_IN_KEY',    '-zBG@xsT#M+sNY]<66%%R}Ji;G7E2ocuU2Yijl$)F(PTYVJi6KC640Y)AAUfGm>2' );
define( 'NONCE_KEY',        'fw_&-aZ3nf|Qi%)BZF.5Hf!v97??a(][-5L/ 4eDv6Q4js:o!6LHfkh;p3&9UIRP' );
define( 'AUTH_SALT',        'y`GU7Pj>@yD$>KI^pZ,,}.vd+:{0AQji~$GDe`hUD0;?^evf4V4&U~}5B;L({#I@' );
define( 'SECURE_AUTH_SALT', 'hej5sYGx<<.ViR(!z76lpJ:v_,eAKH@:svVT3y<X<}Uk=iBeakNqddp8o9b5_@6T' );
define( 'LOGGED_IN_SALT',   '}v>w5Pg~*v4qe|`_`{0&ZJwM6P.Yh)}rXla?4zWQ3-;c;6!eha D;n_*jmxFTd5&' );
define( 'NONCE_SALT',       '_~h6sPNN;6>-3/KskUW3rH}:jG^0a LLdrS5Ukwy>e[yma&$Y~pe!=X{`=Bbq:.D' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
