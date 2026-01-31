<?php
namespace SCER\Frontend;

class Frontend {

    public function __construct() {
        add_action( 'template_redirect', [ $this, 'redirect_expired_content' ] );
    }

    public function redirect_expired_content() {
        if ( is_404() ) {
            $post_id = get_query_var( 'post' );
            if ( get_post_meta( $post_id, '_scer_redirect_active', true ) ) {
                wp_redirect( get_post_meta( $post_id, '_scer_redirect_active', true ) );
                exit;
            }
        }
    }
}
?>