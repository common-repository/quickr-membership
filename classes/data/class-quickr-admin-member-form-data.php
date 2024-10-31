<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Admin_Member_Form_Data
 *
 * @author nur858
 */
require_once QUICKR_PATH. 'classes/common/class-quickr-data.php';
class Quickr_Admin_Member_Form_Data extends Quickr_Data {
    public $ID;
    public $account_status;
    public $referrer;
    public $reg_code;
    public $notes;
    public $membership_ID;
    //public $membership_name;
    public $activation_date;
    public $expiration_date;
    public $is_default_membership;
    public $is_current;
    
    public function __construct() {
        parent::__construct();
        $this->is_current = 1;
        $this->is_default_membership = 1;      
        $this->account_status = 'pending';
    }
    public function extract() {
        $this->account_status = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'quickr_account_status'), 'sanitize_text_field', 'esc_html');
        $this->referrer = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'quickr_referrer'), 'sanitize_text_field', 'esc_url');
        $this->reg_code = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'quickr_reg_code'),'sanitize_text_field', 'esc_html');
        $this->notes = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'quickr_notes'),'sanitize_text_field', 'esc_html');
        $this->membership_ID = absint(filter_input(INPUT_POST, 'quickr_membership'));
        $this->activation_date = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'quickr_activation_date'),'sanitize_text_field', 'esc_html');   
        $this->expiration_date = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'quickr_expiration_date'),'sanitize_text_field', 'esc_html');
    }

    public function is_submitted() {
        new Exception("is_submitted is not implemented for Quickr_Member_Data");
    }
    
    public function load($id) {
        global $wpdb;
        $this->ID = $id;

        $this->account_status = get_user_meta($id, Quickr_Constants::member_account_status_metakey, true);
        $this->referrer = get_user_meta($id, Quickr_Constants::member_referrer_metakey, true);
        $this->reg_code = get_user_meta($id, Quickr_Constants::member_reg_code_metakey, true);   
        $this->notes = get_user_meta($id, Quickr_Constants::member_notes_metakey, true);        
        $sql = $wpdb->prepare("SELECT membership_ID, activation_date, expiration_date FROM " 
                . $wpdb->prefix . Quickr_Constants::membership_rel .
                "  WHERE user_ID=%d", $id);
        $result = $wpdb->get_row($sql, ARRAY_A);
        if (empty($result) ) {return;}
        foreach($result as $key=>$value){
            $this->$key = $value;
        }        

    }
    public function insert() {}
    public function update($user_id) {
        update_user_meta($user_id, Quickr_Constants::member_account_status_metakey, $this->account_status);
        update_user_meta($user_id, Quickr_Constants::member_referrer_metakey, $this->referrer);
        update_user_meta($user_id, Quickr_Constants::member_reg_code_metakey, $this->reg_code);
        update_user_meta($user_id, Quickr_Constants::member_notes_metakey, $this->notes);        
        $this->update_membership_info($user_id);        
    }
    public function save() {
        update_user_meta($this->ID, Quickr_Constants::member_account_status_metakey, $this->account_status);
        update_user_meta($this->ID, Quickr_Constants::member_referrer_metakey, $this->referrer);
        update_user_meta($this->ID, Quickr_Constants::member_reg_code_metakey, $this->reg_code);
        update_user_meta($this->ID, Quickr_Constants::member_notes_metakey, $this->notes);        
        $this->update_membership_info($this->ID);
    }
    private function update_membership_info($user_id){
        $this->expiration_date = Quickr_Utils::calculate_expiration_date($this->activation_date, $this->expiration_date, $this->membership_ID);
        global $wpdb;
        $sql = $wpdb->prepare("SELECT user_ID FROM " . 
                $wpdb->prefix . Quickr_Constants::membership_rel 
                . " WHERE user_ID = %d ", $user_id);
        $status = $wpdb->get_var($sql);
        if (empty($status)){
            $sql = $wpdb->prepare(" INSERT INTO " . $wpdb->prefix . Quickr_Constants::membership_rel . 
                "(user_ID, membership_ID, activation_date,expiration_date, is_default_membership, is_current)"
                ." VALUES(%d, %d, %s, %s, %d, %d)",
                    $user_id, 
                    $this->membership_ID,
                    $this->activation_date,
                    $this->expiration_date,
                    $this->is_default_membership, 
                    $this->is_current
                    );
        }
        else{
           $sql = $wpdb->prepare(" UPDATE " . $wpdb->prefix . Quickr_Constants::membership_rel 
                   . " SET membership_ID=%d, "
                   . " activation_date=%s, "
                   . " expiration_date=%s, "
                   . " is_default_membership=%d, "
                   . " is_current=%d WHERE user_ID=%d"
                   , $this->membership_ID,
                     $this->activation_date,
                     $this->expiration_date,
                     $this->is_default_membership, 
                     $this->is_current,
                     $user_id
                   ); 
        }
        
        $wpdb->query($sql);        
    }
    /**
     * 
     * @return type
     */
    public function validate() {
        if ($this->membership_ID <=0){
            $this->errors['membership ID'] = Quickr_I18n::_( 'Invalid membership level ID');
        }
        if (empty($this->activation_date)){
            $this->errors['activation_date'] = Quickr_I18n::_( 'Activation date cannot be empty');
        }
        return count($this->errors)<=0;
    }
    /**
     * 
     * @param type $selected
     * @return type
     */
    public static function account_status_dropdown($selected = 'pending'){
        /**
         * 1. incomplete => user needs to complete missing pieces in registration process
         * 2. pending => user registration is pending admin approval
         * 3. active => user account is currently active
         * 4. inactive => user account is banned/inactive. 
         * 5. expired => subscription expired.
         */
        return 
            '<option '
                . (($selected == Quickr_Constants::member_status_incomplete)? 'selected="selected"': "")
                .' value= "' . Quickr_Constants::member_status_incomplete . '">' . Quickr_I18n::_('Incomplete') . '</option>'              
        .  '<option '
                . (($selected ==Quickr_Constants::member_status_pending)? 'selected="selected"': "")
                .' value= "' . Quickr_Constants::member_status_pending . '">' . Quickr_I18n::_('Pending') . '</option>'
        . '<option '
                . (($selected ==Quickr_Constants::member_status_active)? 'selected="selected"': "")
                .' value= "' . Quickr_Constants::member_status_active . '">' .Quickr_I18n::_('Active') . '</option>'
        . '<option '
                . (($selected ==Quickr_Constants::member_status_inactive)? 'selected="selected"': "")
                .' value= "' . Quickr_Constants::member_status_inactive .'">' .Quickr_I18n::_('Inactive') . '</option>'
       . '<option '
                . (($selected ==Quickr_Constants::member_status_expired)? 'selected="selected"': "")
                .' value= "' . Quickr_Constants::member_status_expired .'">' .Quickr_I18n::_('Expired') . '</option>';
        
    }    
    public static function get_account_status_name($key){
        $statuses = array(
            'active' => Quickr_I18n::_('Active'),
            'inactive' => Quickr_I18n::_('Inactive'),
            'expired' => Quickr_I18n::_('Expired'),
            'incomplete' => Quickr_I18n::_('Incomplete'),
            'pending' => Quickr_I18n::_('Pending')
        ); 
        return isset($statuses[$key])? $statuses[$key]:"";
    }
}
