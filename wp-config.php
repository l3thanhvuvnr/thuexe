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
define( 'DB_NAME', 'thuexe' );

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
define( 'AUTH_KEY',         '^wv<Xvzaxh8X_g!Eq[BVF4_d:B.b{?6IkObW @PNh%Yk0LSf$$GD8bG-7DFl)f?C' );
define( 'SECURE_AUTH_KEY',  '!PIha@T~;~Ca$y*,ZuH=:;<!kFZ8M?Va_g3MiL}^QZdjRFMM~0d(I6+pNQb!siy(' );
define( 'LOGGED_IN_KEY',    'y!n)tdAuTnHbuaV7kW9`2HIiQSvU^--kzLqBq6ykKjI{qA3/]I$P&:mC_oX1}Ywd' );
define( 'NONCE_KEY',        'y$75@fowjJ7w_@i}445rfce2/nJ!qCmO@1I1EI~4C= w.i$y<|-N<etuQ&UR(m/E' );
define( 'AUTH_SALT',        '{?+!{AmQ]Y-rW RtL&MIOx~&,#Z[64[p()xRV998SH+IF:Qq3>V9vC3X,}#PY3nw' );
define( 'SECURE_AUTH_SALT', 'Lc4bpT<S)g}ULSFEEZtp0E|j8]=72)p5 ZgGajyJBFq|J+~(:}Nr}E^:e%,2Kl25' );
define( 'LOGGED_IN_SALT',   '7I<yaA^/`0Z4oN&B7yGb:zi8!rA4wVoaq)c8)w&VgaCF-| }ywHB#;Bh*xQs/2A8' );
define( 'NONCE_SALT',       ';JEyAHmbf/Qu^d3.j~jdPVghod*/mhi9+#Wv<X?_-6R2.zpsH&$r</r&jEc~sRWJ' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'tx_';

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

// define( 'WP_HOME', 'https://ea9c-118-69-116-173.ngrok-free.app' );
// define( 'WP_SITEURL', 'https://ea9c-118-69-116-173.ngrok-free.app' );
// if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
//     $_SERVER['HTTPS'] = 'on';
// }

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
