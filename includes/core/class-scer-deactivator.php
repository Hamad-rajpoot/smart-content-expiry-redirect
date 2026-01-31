<?php
namespace SCER\Core;

class Deactivator {
    public static function deactivate() {
        // Clear cron jobs
        $timestamp = wp_next_scheduled( 'scer_cron_event' );
        if ( $timestamp ) {
            wp_unschedule_event( $timestamp, 'scer_cron_event' );
        }
    }
}
