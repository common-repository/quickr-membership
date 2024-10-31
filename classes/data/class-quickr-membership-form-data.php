<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Membership_Form_Data
 *
 * @author nur858
 */
require_once QUICKR_PATH . 'classes/common/class-quickr-data.php';

class Quickr_Membership_Form_Data extends Quickr_Data {
    public $ID;
    public $name;
    public $role;
    public $permissions;
    public $duration;
    public $duration_type;
    public $login_redirect_page;
    public $protect_older_posts;
    public $campaign_name;

    public function __construct() {
        parent::__construct();
        $this->duration = 30;
        $this->role = 'subscriber';
        $this->duration_type = 'variable';
        $this->protect_older_posts = false;
    }

    public function get_ID() {
        return $this->ID;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_role() {
        return $this->role;
    }

    public function get_permissions() {
        return $this->permissions;
    }

    public function get_duration() {
        return $this->duration;
    }

    public function get_duration_type() {
        return $this->duration_type;
    }

    public function get_login_redirect_page() {
        return $this->login_redirect_page;
    }

    public function get_protect_older_posts() {
        return $this->protect_older_posts;
    }

    public function get_campaign_name() {
        return $this->campaign_name;
    }

    /**
     * 
     */
    public function extract() {
        $this->ID = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'ID'), 'sanitize_text_field', 'esc_html');
        $this->name = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'name'), 'sanitize_text_field', 'esc_html');
        $this->role = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'role'), 'sanitize_text_field', 'esc_html');
        $this->permissions = 31;
        $this->duration = absint(filter_input(INPUT_POST, 'duration',FILTER_SANITIZE_NUMBER_INT));
        $this->duration_type = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'duration_type'), 'sanitize_text_field', 'esc_html');
        $this->login_redirect_page = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'login_redirect_page'), 'sanitize_text_field', 'esc_url');
        $this->protect_older_posts = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'protect_older_posts'), 'sanitize_text_field', 'esc_html');
        $this->campaign_name = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'campaign_name'), 'sanitize_text_field', 'esc_html');
    }

    /**
     * 
     * @return type
     */
    public function is_submitted() {
        $submit = filter_input(INPUT_POST, 'quickr-membership-submit');
        return !empty($submit);
    }

    /**
     * 
     * @global type $wpdb
     * @param type $id
     * @return type
     */
    public function load($id) {
        $post = get_post($id);
        if ($post != null){
            $this->ID = $id;
            $this->name = $post->post_title;
            $this->duration = $post->post_content;
            $this->role = get_post_meta($id, Quickr_Constants::membership_role_metakey, true);
            $this->duration_type = get_post_meta($id, Quickr_Constants::membership_duration_type_metakey, true);
            $this->protect_older_posts = get_post_meta($id, Quickr_Constants::membership_protect_older_posts_metakey, true);
            $this->login_redirect_page = get_post_meta($id, Quickr_Constants::membership_login_redirect_page_metakey, true);
            $this->campaign_name = get_post_meta($id, Quickr_Constants::membership_campaign_name_metakey, true);
        }
    }

    /**
     * 
     * @return boolean
     */
    public function save() {
        $nonce = filter_input(INPUT_POST, 'quickr_membership_level_nonce');
        if (!wp_verify_nonce($nonce, 'quickr_membership_level_nonce_submit')) {
            $this->errors['error'] = Quickr_I18n::_('Sorry! security verification failed.');
            return false;
        }

        parent::save();
    }

    /**
     * 
     */
    public function validate() {
        if (empty($this->name)) {$this->errors['name'] = Quickr_I18n::_("Title cannot be empty.");}
        if (empty($this->role)) {$this->errors['role'] = Quickr_I18n::_("Role cannot be empty.");}

        if ($this->duration_type == 'fixed' && !strtotime($this->duration)){
            $this->errors['duration'] = Quickr_I18n::_("Not a valid Date.");
        }
        else if ($this->duration_type == 'variable' && (!is_numeric($this->duration) || $this->duration < 0)){
            $this->errors['duration'] = Quickr_I18n::_("Not a valid number.");
        }
    }
    /**
     * 
     * @global type $wpdb
     */
    public function insert() {
         $membership_id = wp_insert_post(
                array(
                    'post_title' => sanitize_text_field($this->name),
                    'post_type' => 'quickr_member_level',
                    'post_content' => $this->duration,
                    'post_status' => 'publish'
                ),true
        );
         if (is_wp_error($membership_id)) {
             $this->errors['error'] = $membership_id->get_error_message();
             return;             
         }
         if (!add_post_meta($membership_id, Quickr_Constants::membership_role_metakey, $this->role, true)) {
            update_post_meta($membership_id, Quickr_Constants::membership_role_metakey, $this->role);
        }
         if (!add_post_meta($membership_id, Quickr_Constants::membership_duration_type_metakey, $this->duration_type, true)) {
            update_post_meta($membership_id, Quickr_Constants::membership_duration_type_metakey, $this->duration_type);
        }
        if (!add_post_meta($membership_id, Quickr_Constants::membership_login_redirect_page_metakey, $this->login_redirect_page, true)) {
            update_post_meta($membership_id, Quickr_Constants::membership_login_redirect_page_metakey, $this->login_redirect_page);
        }
        if (!add_post_meta($membership_id, Quickr_Constants::membership_protect_older_posts_metakey, $this->protect_older_posts, true)) {
            update_post_meta($membership_id, Quickr_Constants::membership_protect_older_posts_metakey, $this->protect_older_posts);
        }
        if (!add_post_meta($membership_id, Quickr_Constants::membership_campaign_name_metakey, $this->campaign_name, true)) {
            update_post_meta($membership_id, Quickr_Constants::membership_campaign_name_metakey, $this->campaign_name);
        }
        return true;
    }

    /**
     * 
     * @global type $wpdb
     * @param type $id
     */
    public function update($id) {
        wp_update_post(
                array(
                    'ID' => $id,
                    'post_title' => $this->name,
                    'post_type' => 'quickr_member_level',
                    'post_content' => $this->duration,
                    'post_status' => 'publish'
                )
        );
        update_post_meta($id, Quickr_Constants::membership_role_metakey, $this->role);
        update_post_meta($id, Quickr_Constants::membership_duration_type_metakey, $this->duration_type);
        update_post_meta($id, Quickr_Constants::membership_login_redirect_page_metakey, $this->login_redirect_page);
        update_post_meta($id, Quickr_Constants::membership_protect_older_posts_metakey, $this->protect_older_posts);
        update_post_meta($id, Quickr_Constants::membership_campaign_name_metakey, $this->campaign_name);

        return true;
    }
    
    public static function get_duration_type_name($key) {
        $duration_types = array(
            "fixed" => Quickr_I18n::_("Fixed"),
            "variable" => Quickr_I18n::_("Variable")
        );
        return isset($duration_types[$key]) ? $duration_types[$key] : "";
    }

    /**
     * 
     * @param type $selected
     * @return type
     */
    public static function duration_type($selected = 'variable') {
        return
                '<option '
                . (($selected == 'fixed') ? 'selected="selected"' : "")
                . ' value= "fixed">' . Quickr_I18n::_('fixed') . '</option>'
                . '<option '
                . (($selected == 'variable') ? 'selected="selected"' : "")
                . ' value= "variable">' . Quickr_I18n::_('Variable') . '</option>';
    }

    public static function membership_dropdown($selected = 2) {
         $args = array( 
            'post_type' => 'quickr_member_level', 
            'posts_per_page' => -1, 
            'post_status' => 'publish', 
            'post_parent' => null );
        $results = get_posts($args);
        
        if (empty($results)) {
            return '';
        }
        $output = '';

        foreach ($results as $result) {
            $output .= '<option '
                    . (($selected == $result->ID) ? 'selected="selected"' : "")
                    . ' value= "' . $result->ID . '">' 
                    . Quickr_I18n::_($result->post_title) 
                    . ' (' . Quickr_I18n::_($result->ID) . ')' . '</option>';
        }

        return $output;
    }
}
