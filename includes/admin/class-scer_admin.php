<?php
namespace SCER\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

class SCER_Admin {

    public function __construct() {
        // Enqueue JS & CSS for Admin
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);

        // Initialize MetaBox
        new MetaBox();
    }

    public function enqueue_assets($hook) {
        // Only on post editor screens
        if ( $hook === 'post.php' || $hook === 'post-new.php' ) {

            wp_enqueue_script(
                'scer-metabox-js',
                SCER_PLUGIN_URL . 'assets/js/scer-metabox.js',
                ['jquery'],
                SCER_VERSION,
                true
            );

            wp_enqueue_style(
                'scer-metabox-css',
                SCER_PLUGIN_URL . 'assets/css/scer-metabox.css',
                [],
                SCER_VERSION
            );
        }
    }
}
