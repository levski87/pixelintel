<?php
/*
  Plugin Name: OptiMonk
  Plugin URI: http://wordpress.org/extend/plugins/optimonk/
  Description: OptiMonk is an exit-intent popup technology
  Author: OptiMonk
  Version: 1.0.0
  Author URI: http://www.optimonk.com/
  License: GPLv2
*/
if (!session_start()) {
    session_start();
}
require_once(dirname(__FILE__) . "/optimonk-admin.php");
require_once(dirname(__FILE__) . "/optimonk-front.php");

if (class_exists('OptiMonkAdmin') && class_exists('OptiMonkFront')) {
    if (!is_admin()) {
        add_action('wp_print_footer_scripts', array('OptiMonkFront', 'init'));
    }
    register_activation_hook(__FILE__, array('OptiMonkAdmin', 'activate'));
    add_action('admin_init', array('OptiMonkAdmin', 'redirectToSettingPage'));

    $optiMonkAdmin = new OptiMonkAdmin(__FILE__);
    $optiMonkFront = new OptiMonkFront();
}
