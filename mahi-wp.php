<?php

// Plugin Name: Mahi-WP

register_activation_hook(__FILE__, 'child_plugin_activate');
function child_plugin_activate()
{
    // Require parent plugin
    if (!is_plugin_active('mahi-wp/mahi-wp.php') and current_user_can('activate_plugins')) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires the Mahi-WP Plugin to be installed and active. <br><a href="'.admin_url('plugins.php').'">&laquo; Return to Plugins</a>');
    }
}

require_once 'functions.php';
require_once 'debug.php';
