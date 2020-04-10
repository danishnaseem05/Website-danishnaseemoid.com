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
define( 'DB_NAME', 'danihsjd_blogwp8' );

/** MySQL database username */
define( 'DB_USER', 'danihsjd_blogwp8' );

/** MySQL database password */
define( 'DB_PASSWORD', 'CMCA9wUmMTLT' );

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
define( 'AUTH_KEY',         '8dwlrphyxwvdoq1zutptwxxov5ugtvug7gnjlpmbjc65ojqw3mte0rg6stmqazwu' );
define( 'SECURE_AUTH_KEY',  'ybivh7ngsmij9arwfr2hwl5zhclirczy7037j4n8ggqn7oofdtvrqhwkg1ekk3vo' );
define( 'LOGGED_IN_KEY',    'g1mefrfz9j1zd94gwrxte0wrp1osnoutxlomqw1spnf0uakieb1hxe4vogudfa2l' );
define( 'NONCE_KEY',        'jyizkjczv1fbrcrwlkpnatmt0k3279at0q430nwgqzz4wjild1er5mnt0d53hl5n' );
define( 'AUTH_SALT',        'tjlxbeeb4cvod2xikk2tblznxpuba2bh8et88ur4lp99zvshqwxvp97vpcpdy5nn' );
define( 'SECURE_AUTH_SALT', '83rrfu6bp3yokhdxvnpfch8dq8hytqzav9ussitmisfysqc7m3ex1ftjoimmxeba' );
define( 'LOGGED_IN_SALT',   'ufo1k82cprsuykvzkkvejlbaeznqljpjvrrfehke4xcwtksgc3fny1evqqokbrcr' );
define( 'NONCE_SALT',       'qnf9rone1xn06epxpp5npksgvja8165yoxnac67fntntt70dzr46editqm0or63f' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'blogwp8';

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

/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'blog.danishnaseemoid.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
