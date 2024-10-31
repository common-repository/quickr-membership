<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Attachment_Handler
 *
 * @author nur858
 */
class Quickr_Attachment_Handler {

    /**
     * 
     * @param type $content
     * @param type $post_id
     */
    public function filter_attachment_url($content, $post_id) {
        $post = get_post($post_id);
        require_once QUICKR_CLASSES . 'front/access-control/class-quickr-access-controller.php';
        if (is_admin() ||has_post_thumbnail($post_id)) {//No need to filter on the admin side
            return $content;
        }

        $acl = new Quickr_Access_Controller();
        $result = $acl->is_accessible($post->ID);
        if (is_wp_error($result)) {
            return QUICKR_URL . '/images/restricted-icon.png';
        }
        return $content;
    }

    /**
     * 
     * @param type $content
     * @param type $post_id
     */
    public function filter_attachment($content, $post_id) {
        if (is_admin() ||has_post_thumbnail($post_id)) {//No need to filter on the admin side
            return $content;
        }
        
        $post = get_post($post_id);
        $acl = new Quickr_Access_Controller();
        $result = $acl->is_accessible($post->ID);
        if (!is_wp_error($result)) {
            return $content;
        }

        if (isset($content['file'])) {
            $content['file'] = 'restricted-icon.png';
            $content['width'] = '400';
            $content['height'] = '400';
        }

        if (isset($content['sizes'])) {
            if ($content['sizes']['thumbnail']) {
                $content['sizes']['thumbnail']['file'] = 'restricted-icon.png';
                $content['sizes']['thumbnail']['mime-type'] = 'image/png';
            }
            if ($content['sizes']['medium']) {
                $content['sizes']['medium']['file'] = 'restricted-icon.png';
                $content['sizes']['medium']['mime-type'] = 'image/png';
            }
            if (isset($content['sizes']['post-thumbnail'])) {
                $content['sizes']['post-thumbnail']['file'] = 'restricted-icon.png';
                $content['sizes']['post-thumbnail']['mime-type'] = 'image/png';
            }
        }
        return $content;
    }

    /**
     * 
     * @param type $post
     * @param type $attachment
     * @return type
     */
    public function save_attachment_extra($post, $attachment) {
        $post_id  = $post['ID'];
         // Add nonce for security and authentication.
        $nonce_name   = filter_input (INPUT_POST, 'quickr_post_editor_nonce');
        $nonce_action = 'quickr_post_editor_action';
 
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        require_once QUICKR_CLASSES . 'data/class-quickr-admin-metabox-data.php';
        $data = new Quickr_Admin_Metabox_Data();
        $data->extract();
        $data->update($post_id);              
        return $post;
    }

}
