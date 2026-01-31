<?php
namespace SCER\Core;

class Cron {

    public function __construct() {
        add_action( 'scer_run_expiry_check', [ $this, 'check_expired_content' ] );

        // schedule on activation
        if ( ! wp_next_scheduled( 'scer_run_expiry_check' ) ) {
            wp_schedule_event( time(), 'minute', 'scer_run_expiry_check' );
        }

        // add custom interval
        add_filter( 'cron_schedules', [ $this, 'add_interval' ] );
    }

    public function add_interval( $schedules ) {
        $schedules['minute'] = [
            'interval' => 60, // 1 min
            'display'  => 'Every Minute'
        ];
        return $schedules;
    }

    public function check_expired_content() {

        $query = new \WP_Query([
            'post_type'   => ['post','page'],
            'post_status' => ['publish','private'],
            'meta_query'  => [
                [
                    'key'     => '_scer_expiry',
                    'compare' => 'EXISTS'
                ]
            ],
            'posts_per_page' => -1
        ]);

        if ( $query->have_posts() ) {
            $now = current_time( 'Y-m-d H:i' );

            while ( $query->have_posts() ) {
                $query->the_post();

                $post_id = get_the_ID();
                $expiry  = get_post_meta( $post_id, '_scer_expiry', true );

                if ( $expiry && $expiry <= $now ) {
                    $this->run_action( $post_id );
                }

            }
            wp_reset_postdata();
        }
    }

    private function run_action( $post_id ) {
        do_action( 'scer_do_expiry_action', $post_id );
    }
}
