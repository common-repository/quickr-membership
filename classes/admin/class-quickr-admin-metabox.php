<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Admin_Metabox
 *
 * @author nur858
 */
class Quickr_Admin_Metabox {
   /**
     * Meta box initialization.
     */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }
 
    /**
     * Adds the meta box.
     */
    public function add_metabox() {
        add_meta_box(
            'quickr-meta-box',
            Quickr_I18n::_( 'Quickr Membership Options'),
            array( $this, 'render_metabox' ),
            'post',
            'advanced',
            'default'
        );
 
    }
    
    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'quickr_post_editor_action', 'quickr_post_editor_nonce' );
        require_once QUICKR_CLASSES . 'data/class-quickr-admin-metabox-data.php';
        $data = new Quickr_Admin_Metabox_Data();
        $data->load($post->ID);        
        include QUICKR_VIEWS . 'admin/metabox/post_metabox.php';
    }
 
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
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
    }
}
