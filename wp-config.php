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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'g^%HN9LC8=u@6%|%n$l&epGw-)xdwYw_^|ST--X~uv3Ii$DhY?] +fa|6O-@1^:-');
define('SECURE_AUTH_KEY',  'nkYVyO8%7Pw>RIcGJ#pB=C(5v?6]Zz}jQ(UeE$&2TaoWDH&t[N}!4,p +zu41#A$');
define('LOGGED_IN_KEY',    '$PU!05pA}?t(`:TixXGSYmq+@beCX|aC.p_T5^VUE4$6:ncsXNq+NQ.A5_ht8qz ');
define('NONCE_KEY',        'B6<t6>k`$T9MKh[F5UL!;izW>Tu$T/ChS|G0OT#:4O]Kv(h9b)H=SPDO2}fmZYme');
define('AUTH_SALT',        '%`Md_HFaggs<FyW4a:8X9#nv5Dp.t7{e=h.U!`hD5-^5hJT/5jjq5Lz$#3 x]lKa');
define('SECURE_AUTH_SALT', '#@:DqlsCFftt$G+/HtEt6 0WDB>O~;p`HhZJTKgDGh8H/r~X:+V{Jp#KS;4s4 jS');
define('LOGGED_IN_SALT',   'RX=EsYuL2+u [Z,m@%9BeK/g~Sz;vU86P+dPSF/bCOg %})s=Bnu@(Wel1;{[.<X');
define('NONCE_SALT',       '?9-{TUBf3ClA1e6t%JL!ridOQ7@S!f#]6wa^F1moSFn6Uc- z!!KmfF.ty}z^rx5');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
