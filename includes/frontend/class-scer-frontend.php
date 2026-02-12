<?php
namespace SCER\Frontend;

use SCER\Core\Expiry;
use SCER\Core\Actions;

class Frontend {

    public function __construct() {
        add_action( 'template_redirect', [ $this, 'handle_expiry' ] );
    }

    public function handle_expiry() {

        if ( ! is_singular() ) {
            return;
        }

        $post_id = get_the_ID();

        // Check expiry
        if ( Expiry::is_expired( $post_id ) ) {

            // Run selected action
            Actions::run( $post_id );

            // Redirect if selected
            $redirect_url = get_post_meta( $post_id, '_scer_redirect', true );
            if ( $redirect_url ) {
                wp_redirect( esc_url( $redirect_url ), 301 );
                exit;
            }
        }
    }
}
