<?php
namespace SCER\Core;

class Expiry {

    public static function is_expired( $post_id ) {
        $expiry = get_post_meta( $post_id, '_scer_expiry', true );
        if ( empty( $expiry ) ) {
            return false;
        }

        $now = current_time( 'Y-m-d H:i' );
        return ( $expiry <= $now );
    }
}
