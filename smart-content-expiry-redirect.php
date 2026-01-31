<?php
/**
 * Plugin Name: Smart Content Expiry & Redirect
 * Plugin URI:  https://wordpress.org/plugins/smart-content-expiry-redirect/
 * Description: Automatically expire posts/pages and perform actions like redirect, replace, badge, and WooCommerce deal handling.
 * Version:     1.0.0
 * Author:      Hamad
 * Text Domain: scer
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Constants
define( 'SCER_VERSION', '1.0.0' );
define( 'SCER_PLUGIN_FILE', __FILE__ );
define( 'SCER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SCER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Autoloader
function scer_autoload( $class ) {
    $prefix = 'SCER\\';

    if ( strpos( $class, $prefix ) !== 0 ) {
        return;
    }

   $relative = strtolower( str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) ) );

// extract dir + class
$parts = explode('/', $relative);
$class = array_pop($parts);
$dir   = implode('/', $parts);

// build patterns
$candidates = [
    SCER_PLUGIN_DIR . "includes/$dir/$class.php",
    SCER_PLUGIN_DIR . "includes/$dir/class-scer-$class.php",
    SCER_PLUGIN_DIR . "includes/$dir/class-$class.php",
];

// check files
foreach ( $candidates as $file ) {
    if ( file_exists( $file ) ) {
        require_once $file;
        return;
    }
}
}

spl_autoload_register( 'scer_autoload' );

// Activation / Deactivation
register_activation_hook( __FILE__, function() {
    SCER\Core\Activator::activate();
});

register_deactivation_hook( __FILE__, function() {
    SCER\Core\Deactivator::deactivate();
});

// Init Plugin
function scer_init_plugin() {
    $loader = new SCER\Core\Loader();
    $loader->init();
}
add_action( 'plugins_loaded', 'scer_init_plugin' );
