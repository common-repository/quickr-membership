<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Member_Data
 *
 * @author nur858
 */
require_once (QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php');
class Quickr_Member_Data  extends Quickr_Admin_Member_Form_Data {
    public $ID;
    public $account_status;
    public $referrer;
    public $reg_code;
    public $notes;
    public $membership_ID;
    public $membership_name;
    public $activation_date;
    public $expiration_date;
    public $login_redirect_page;
    public $is_default_membership;
    public $is_current;
    public $duration;
    public $role;
    public $duration_type;
    public $protect_older_posts;
    public $campaign_name;
    public function load($id) {
        global $wpdb;
        $this->ID = $id;
        $sql = $wpdb->prepare("SELECT membership_ID, activation_date, expiration_date FROM " 
                . $wpdb->prefix . Quickr_Constants::membership_rel .
                "  WHERE user_ID=%d", $id);
        $result = $wpdb->get_row($sql, ARRAY_A);
        if (empty($result) ) {return;}
        foreach($result as $key=>$value){
            $this->$key = $value;
        }        
        $this->load_membership_info();
        if ($this->is_expired() && $this->account_status != Quickr_Constants::member_status_active){
            $this->account_status = Quickr_Constants::member_status_expired;
            update_user_meta($this->ID, Quickr_Constants::member_account_status_metakey, $this->account_status);
        }
        $this->account_status = get_user_meta($id, Quickr_Constants::member_account_status_metakey, true);
        $this->referrer = get_user_meta($id, Quickr_Constants::member_referrer_metakey, true);
        $this->reg_code = get_user_meta($id, Quickr_Constants::member_reg_code_metakey, true);
        $this->notes = get_user_meta($id, Quickr_Constants::member_notes_metakey, true);
    }

    public function is_expired() {
        return strtotime($this->expiration_date) < time();
    }
    private function load_membership_info(){
        $post = get_post($this->membership_ID);
        if ($post != null){
            $this->membership_name = $post->post_title;
            $this->duration = $post->post_content;
            $this->role = get_post_meta($this->membership_ID, Quickr_Constants::membership_role_metakey, true);
            $this->duration_type = get_post_meta($this->membership_ID, Quickr_Constants::membership_duration_type_metakey, true);
            $this->protect_older_posts = get_post_meta($this->membership_ID, Quickr_Constants::membership_protect_older_posts_metakey, true);
            $this->login_redirect_page = get_post_meta($this->membership_ID, Quickr_Constants::membership_login_redirect_page_metakey, true);
            $this->campaign_name = get_post_meta($this->membership_ID, Quickr_Constants::membership_campaign_name_metakey, true);
        }        
    }
}
