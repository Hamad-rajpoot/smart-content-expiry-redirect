<?php
namespace SCER\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

class MetaBox {

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_meta' ] );
    }

    public function add_meta_box() {
        $screens = ['post','page'];

        foreach ($screens as $screen) {
            add_meta_box(
                'scer_expiry_box',
                __( 'Content Expiry & Redirect', 'scer' ),
                [ $this, 'render_meta_box' ],
                $screen,
                'side'
            );
        }
    }

    public function render_meta_box( $post ) {
        $expiry   = get_post_meta( $post->ID, '_scer_expiry', true );
        $action   = get_post_meta( $post->ID, '_scer_action', true );
        $redirect = get_post_meta( $post->ID, '_scer_redirect', true );
        $replace  = get_post_meta( $post->ID, '_scer_replace', true );
        $status   = get_post_meta( $post->ID, '_scer_status', true );

        wp_nonce_field( 'scer_save_meta', 'scer_meta_nonce' );

        ?>
        <div class="scer-field-group">
            <strong><?php _e('Expiry Date & Time:', 'scer'); ?></strong><br/>
            <input type="datetime-local" name="scer_expiry" value="<?php echo esc_attr($expiry); ?>" />
        </div>

        <div class="scer-field-group">
            <strong><?php _e('Action After Expiry:', 'scer'); ?></strong><br/>
            <select id="scer_action_after_expiry" name="scer_action">
                <option value="">-- <?php _e('Select Action','scer'); ?> --</option>
                <option value="redirect" <?php selected($action,'redirect'); ?>><?php _e('Redirect','scer'); ?></option>
                <option value="replace" <?php selected($action,'replace'); ?>><?php _e('Replace Content','scer'); ?></option>
                <option value="status_change" <?php selected($action,'status_change'); ?>><?php _e('Change Status','scer'); ?></option>
            </select>
        </div>

        <div id="scer_redirect_url_group" class="scer-field-group">
            <strong><?php _e('Redirect URL:', 'scer'); ?></strong><br/>
            <input type="url" name="scer_redirect" value="<?php echo esc_attr($redirect); ?>" />
        </div>

        <div id="scer_replace_content_group" class="scer-field-group">
            <strong><?php _e('Replace Content:', 'scer'); ?></strong><br/>
            <textarea name="scer_replace" rows="4"><?php echo esc_textarea($replace); ?></textarea>
        </div>

        <div id="scer_status_change_group" class="scer-field-group">
            <strong><?php _e('Status Change To:', 'scer'); ?></strong><br/>
            <select name="scer_status">
                <option value="">-- <?php _e('Choose','scer'); ?> --</option>
                <option value="draft" <?php selected($status,'draft'); ?>><?php _e('Draft','scer'); ?></option>
                <option value="private" <?php selected($status,'private'); ?>><?php _e('Private','scer'); ?></option>
                <option value="trash" <?php selected($status,'trash'); ?>><?php _e('Trash','scer'); ?></option>
            </select>
        </div>
        <?php
    }

    public function save_meta( $post_id ) {
        if ( ! isset($_POST['scer_meta_nonce']) || ! wp_verify_nonce($_POST['scer_meta_nonce'],'scer_save_meta') ) return;
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
        if ( ! current_user_can('edit_post', $post_id) ) return;

        $fields = [
            '_scer_expiry'   => sanitize_text_field($_POST['scer_expiry'] ?? ''),
            '_scer_action'   => sanitize_text_field($_POST['scer_action'] ?? ''),
            '_scer_redirect' => esc_url_raw($_POST['scer_redirect'] ?? ''),
            '_scer_replace'  => wp_kses_post($_POST['scer_replace'] ?? ''),
            '_scer_status'   => sanitize_text_field($_POST['scer_status'] ?? '')
        ];

        foreach($fields as $key => $value){
            if(empty($value)){
                delete_post_meta($post_id, $key);
            } else {
                update_post_meta($post_id, $key, $value);
            }
        }
    }
}
