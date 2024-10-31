<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Post_Handler
 *
 * @author nur858
 */
class Quickr_Post_Handler {
    /**
     * 
     * @param type $content
     * @return string
     */
    public function filter_content($content){
        global $post;
        require_once QUICKR_CLASSES . 'front/access-control/class-quickr-access-controller.php';
        $acl = new Quickr_Access_Controller();
        $result = $acl->is_accessible($post->ID);
        if (is_wp_error($result)){
            //return 'restricted';
            return '<div class="error">'. $result->get_error_message() . '</div>';
        }
        return $content;
    }    
}
