<?php
define('WP_CACHE', true);
/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Super cray cray takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
| You can find your hostname by running `hostname` in terminal.
|
*/
$environments = array(
    'local' => array(
        'kirill',
        'Denis-PC',
        'kirillMac.local',
        'denis-Inspiron-N5110',
        'denis-Lenovo-Yoga-2-Pro',
    ),
);

foreach ($environments as $environment => $hosts)
{
    // To determine the current environment, we'll simply iterate through the possible
    // environments and look for the host that matches the host for this request we
    // are currently processing here, then return back these environment's names.
    foreach ((array) $hosts as $host)
    {
        if ($host === gethostname()) {
            require_once ".env.{$environment}.php";
        }
    }
}


/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $_ENV['DB_NAME']);

/** MySQL database username */
define('DB_USER', $_ENV['DB_USER']);

/** MySQL database password */
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

/** MySQL hostname */
define('DB_HOST', $_ENV['DB_HOST']);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define( 'AWS_ACCESS_KEY_ID', $_ENV['AWS_ACCESS_KEY_ID'] );
define( 'AWS_SECRET_ACCESS_KEY', $_ENV['AWS_SECRET_KEY']);


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'K.0n6$Ghu{(-V=8-Np}z|(7K;x86^mU38Ye~2FL+X<pS?oI.G9*81dIx_`kb[P+#');
define('SECURE_AUTH_KEY',  'gN[U-FImUGMEK}Ed.-I31iI%^I2T*B|!q1t~VI+f-C*^ Ba-ENMct[+{}m9L.Vah');
define('LOGGED_IN_KEY',    '#FI0ho||DQVg$*^Ra8klo;&W5@6.}R0jQ{N2hXXx44P>ax(F+x<VH%b;w5gH<oPB');
define('NONCE_KEY',        '=c5JfH6=7N]5xoa)FEdwmmeF}G0`?QJq;*p1}-C F3/SqL0Y;FQ:H|W:LzaH|%pp');
define('AUTH_SALT',        'NkkVF||B(dSjS-eMic{x_;moYA<Y z`LvjPE_E_8xep?}|z-L9zH63d3F)G+T@h#');
define('SECURE_AUTH_SALT', '*<;s[45H7*ef TG+K?|%<9O;&c/@Z.j>{}RT9R~P6o1>k*SZ-;A1*SBT!q2htj@4');
define('LOGGED_IN_SALT',   'Q_qqzkJE/5ofn{SQ%9AFMxMfCFKw$%3+d7oL8>!l+o-QTIYi~ q4vGC}bhlf%1*}');
define('NONCE_SALT',       'P0Yr|bI8XgtXZ1W1~h]hw^:#80-, ?eDb52LWr>5|#VJ26:-od~&:Q5Tq^]t7#~b');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/**
 * Disable all automatic updates
 */
define('AUTOMATIC_UPDATER_DISABLED', true);

/**
 * This will block users being able to use the plugin and theme installation/update
 * functionality from the WordPress admin area. Setting this constant also disables
 * the Plugin and Theme editor (i.e. you don't need to set DISALLOW_FILE_MODS and
 * DISALLOW_FILE_EDIT, as on its own DISALLOW_FILE_MODS will have the same effect).
 */
define('DISALLOW_FILE_MODS', true);

/**
 * Disable all core updates:
 */
define('WP_AUTO_UPDATE_CORE', false);

$memcached_servers = array(
    'default' => array(
       $_ENV['MEMCACHED_NODE_ONE'],
    )
);

require_once(ABSPATH . 'Mobile_Detect.php');
$mobileDetect = new Mobile_Detect();

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
