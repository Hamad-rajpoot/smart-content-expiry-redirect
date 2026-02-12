<?php
namespace SCER\Core;

defined( 'ABSPATH' ) || exit;

class Actions {

    /**
     * Handle post expiry actions
     *
     * @param int $post_id
     */
    public static function run( $post_id ) {

        $action = get_post_meta( $post_id, '_scer_action', true );
        if ( ! $action ) {
            return;
        }

        /*
         * Prevent repeat execution if already completed,
         * except for status-change actions (draft/private)
         */
        $completed = get_post_meta( $post_id, '_scer_completed', true );
        if ( $completed && ! in_array( $action, [ 'draft', 'status_change', 'private' ], true ) ) {
            return;
        }

        switch ( $action ) {

            case 'draft':
            case 'status_change':
                self::change_status( $post_id, 'draft' );
                break;

            case 'private':
                self::change_status( $post_id, 'private' );
                break;

            case 'trash':
                self::trash_post( $post_id );
                break;

            case 'redirect':
                self::redirect_post( $post_id );
                break;

            default:
                // Unknown action, do nothing
                break;
        }

        // Mark completed for non-status actions
        if ( ! in_array( $action, [ 'draft', 'status_change', 'private' ], true ) ) {
            update_post_meta( $post_id, '_scer_completed', 1 );
        }
    }

    /**
     * Change post status safely
     *
     * @param int    $post_id
     * @param string $status
     */
    private static function change_status( $post_id, $status ) {
        wp_update_post( [
            'ID'          => $post_id,
            'post_status' => $status,
        ] );
    }

    /**
     * Move post to trash
     *
     * @param int $post_id
     */
    private static function trash_post( $post_id ) {
        wp_trash_post( $post_id );
    }

    /**
     * Enable redirect action for frontend
     *
     * @param int $post_id
     */
    private static function redirect_post( $post_id ) {
        update_post_meta( $post_id, '_scer_redirect_active', 1 );
    }
}
