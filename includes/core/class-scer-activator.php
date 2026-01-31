<?php
namespace SCER\Core;

class Activator {
    public static function activate() {
        // Example: setup default options, cron jobs
        if ( ! wp_next_scheduled( 'scer_cron_event' ) ) {
            wp_schedule_event( time(), 'hourly', 'scer_cron_event' );
        }
    }
}
