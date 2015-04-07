<?php
/**
 * This file loads in the [Memcached] (http://wordpress.org/extend/plugins/memcached/) dropin file unless disabled
 * by using the $_ENV['DISABLE_MEMCACHE'] value.
 *
 * If Memcached is it will load in the default wordpress file.
 */

if ($_ENV['DISABLE_MEMCACHE']) {
    wp_using_ext_object_cache(null);
    require_once ( ABSPATH . WPINC . '/cache.php' );
} else {
    require_once ( WP_CONTENT_DIR . '/wp-memcached.php' );
}