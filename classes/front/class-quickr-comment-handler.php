<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Comment_Handler
 *
 * @author nur858
 */
class Quickr_Comment_Handler {
    /**
     * 
     */
    public function filter_comment($content){
        global $comment;
        require_once QUICKR_CLASSES . 'front/access-control/class-quickr-access-controller.php';
        $acl = new Quickr_Access_Controller();
        $result = $acl->is_accessible($comment->comment_post_ID);
        if (is_wp_error($result)){
            return '<div class="error">'.$result->get_error_message() . '</div>';
        }
        return $content;
    }
    /**
     * 
     * @return type
     */
    public function customize_comment_form() {
        $allow_comments = Quickr_Settings::get_instance()->get_value('members-login-to-comment');
        if (empty($allow_comments)){
            return;
        }        
        
        //Apply a filter to the message so it can be customized using the custom message plugin
        $comment_form_msg = apply_filters('swpm_login_to_comment_msg', Quickr_I18n::_("Please login to comment."));
        include QUICKR_PATH . 'views/front/comment_replacement.php';
    }
    /**
     * 
     * @param type $fields
     * @return string
     */
    public function customize_comment_fields($fields){
        
        //Check if login to comment feature is enabled.
        $allow_comments = Quickr_Settings::get_instance()->get_value('members-login-to-comment');
        if (empty($allow_comments)){//Feature is disabled
            return $fields;
        }        
                
        //Member is not logged-in so show the protection message.
        $login_link = Quickr_I18n::_('Please Login to Comment.');
        unset($fields);
        return array(
            'comment_field' => $login_link,
            'title_reply' =>'',
            'cancel_reply_link' => '',
            'comment_notes_before' => '',
            'comment_notes_after' => '',
            'fields' => '',
            'label_submit' => '',
            'title_reply_to' => '',
            'id_submit' => '',
            'id_form'=> ''
        );        
    }
    
    /*
     * This function checks and restricts comment posting (via HTTP POST) to members only (if the feature is enabled)
     */
    public function check_and_restrict_comment_posting_to_members(){    
        $allow_comments = Quickr_Settings::get_instance()->get_value('members-login-to-comment');
        if (empty($allow_comments) || is_admin()){
            return;
        }                      
        
        $comment_id = absint(filter_input(INPUT_POST, 'comment_post_ID'));
        if (empty($comment_id)) {
            return;            
        }
               
        $_POST = array();        
        wp_die(Quickr_I18n::_('Comments not allowed by a non-member.'));
    }
        
}
