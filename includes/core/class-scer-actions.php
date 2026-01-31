<?php
namespace SCER\Core;

class Actions {

    public function __construct() {
        add_action( 'scer_do_expiry_action', [ $this, 'process' ] );
    }

    public function process( $post_id ) {

        $action   = get_post_meta( $post_id, '_scer_action', true );

        switch ( $action ) {

            case 'redirect':
                $url = get_post_meta( $post_id, '_scer_redirect', true );
                if ( $url ) {
                    update_post_meta( $post_id, '_scer_redirect_active', $url );
                }
                break;

            case 'replace':
                $content = get_post_meta( $post_id, '_scer_replace', true );
                if ( $content ) {
                    wp_update_post([
                        'ID'           => $post_id,
                        'post_content' => $content
                    ]);
                }
                break;

            case 'status_change':
                $status = get_post_meta( $post_id, '_scer_status', true );
                if ( $status ) {
                    wp_update_post([
                        'ID'     => $post_id,
                        'post_status' => $status
                    ]);
                }
                break;
        }

        update_post_meta( $post_id, '_scer_completed', 1 );
    }
}
