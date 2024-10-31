<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Front_Dispatcher
 *
 * @author nur
 */
class Quickr_Front_Dispatcher {

    /**
     * 
     * @param Quickr_Initializer $initializer
     */
    public function register_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));


        require_once QUICKR_PATH . 'classes/front/class-quickr-post-handler.php';
        $post_handler = new Quickr_Post_Handler();
        add_filter('the_content', array($post_handler, 'filter_content'));
        require_once QUICKR_PATH . 'classes/front/class-quickr-comment-handler.php';
        $comment_handler = new Quickr_Comment_Handler();
        add_filter('comment_text', array($comment_handler, 'filter_comment'));
        add_filter('comment_form_defaults', array($comment_handler, 'customize_comment_fields'));
        add_action('wp_head', array($comment_handler, 'customize_comment_form'));
        add_action('init', array($comment_handler, 'check_and_restrict_comment_posting_to_members'));
        add_action('init', array($this, 'process_forms'));
        require_once QUICKR_PATH . 'classes/front/class-quickr-attachment-handler.php';
        $attachment_handler = new Quickr_Attachment_Handler();
        add_filter('wp_get_attachment_url', array($attachment_handler, 'filter_attachment_url'), 10, 2);
        add_filter('wp_get_attachment_metadata', array($attachment_handler, 'filter_attachment'), 10, 2);
        add_filter('attachment_fields_to_save', array($attachment_handler, 'save_attachment_extra'), 10, 2);
        add_filter('login_redirect', array($this, 'login_redirect'), 10, 3);
        require_once (QUICKR_CLASSES . 'front/class-quickr-payment-button-renderer.php');
        $button_renderer = new Quickr_Payment_Button_Renderer();
        
    }
    public function process_forms(){
        $submitted = filter_input(INPUT_POST, 'quickr-form-submit');
        $type = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'quickr-form-type'), 'sanitize_text_field', 'esc_html');
        if (!empty($submitted)&& !empty($type)){
            do_action('quickr_form_'. $type. '_submit');
            
        }
    }
    /**
     * 
     */
    public function enqueue_styles() {
        
    }

    /**
     * 
     */
    public function enqueue_scripts() {
        
    }

    public function login_redirect($redirect_to, $request, $user) {
        //is there a user to check?
        if (isset($user->roles) && is_array($user->roles)) {
            //check for admins
            if (in_array('administrator', $user->roles)) {
                // redirect them to the default place
                return $redirect_to;
            }
            if (!empty($user->quickr_login_redirect_page)){
                return $user->quickr_login_redirect_page;
            }
            return home_url();
        }
        return $redirect_to;
    }

}
