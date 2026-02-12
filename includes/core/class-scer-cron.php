<?php
namespace SCER\Core;

defined( 'ABSPATH' ) || exit;

class Cron {

    /**
     * Constructor
     * Initialize cron schedule and hooks
     */
    public function __construct() {

        // Register custom cron interval
        add_filter( 'cron_schedules', [ $this, 'add_interval' ] );

        // Hook for checking expired content
        add_action( 'scer_run_expiry_check', [ $this, 'check_expired_content' ] );

        // Schedule the cron event if not already scheduled
        if ( ! wp_next_scheduled( 'scer_run_expiry_check' ) ) {
            wp_schedule_event( time(), 'scer_minute', 'scer_run_expiry_check' );
        }
    }

    /**
     * Add a custom cron interval of 1 minute
     *
     * @param array $schedules Existing cron schedules
     * @return array Modified schedules
     */
    public function add_interval( $schedules ) {

        if ( ! isset( $schedules['scer_minute'] ) ) {
            $schedules['scer_minute'] = [
                'interval' => 60,
                'display'  => __( 'Every Minute (SCER)', 'scer' ),
            ];
        }

        return $schedules;
    }

    /**
     * Check posts and pages for expiry and trigger actions
     */
    public function check_expired_content() {

        $query = new \WP_Query( [
            'post_type'      => [ 'post', 'page' ],
            'post_status'    => [ 'publish', 'private' ],
            'meta_query'     => [
                [
                    'key'     => '_scer_expiry',
                    'compare' => 'EXISTS',
                ],
            ],
            'posts_per_page' => -1,
        ] );

        if ( $query->have_posts() ) {

            $now_ts = current_time( 'timestamp' );

            while ( $query->have_posts() ) {
                $query->the_post();

                $post_id = get_the_ID();
                $expiry  = get_post_meta( $post_id, '_scer_expiry', true );

                if ( empty( $expiry ) ) {
                    continue;
                }

                // Convert expiry to timestamp
                $expiry_ts = strtotime( $expiry );

                if ( ! $expiry_ts ) {
                    continue;
                }

                // Run action if post is expired
                if ( $expiry_ts <= $now_ts ) {
                    do_action( 'scer_do_expiry_action', $post_id );
                }
            }

            wp_reset_postdata();
        }
    }
}
