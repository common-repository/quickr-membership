<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Member
 *
 * @author nur858
 */
require_once QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php';
class Quickr_Admin_profile {
    private $errors;
    private $member_data;
    public function __construct() {
        $this->errors = array();
        $this->member_data = new Quickr_Admin_Member_Form_Data();        
    }
    public function register_user($user_id){   
        $this->member_data->ID = $user_id;
        $this->member_data->save();
    }
    /**
     * 
     * @param type $type
     * @return type
     */
    public function add_new_user($type){
        if ($type != 'add-new-user') {return;}
        include QUICKR_VIEWS . 'admin/members/member_add_form.php';
    }
    /**
     * 
     * @param type $user
     */
    public function show_user_profile($user){ 
        if ( !current_user_can( 'edit_user') ){
            return false;        
        }
        if ($this->member_data->is_submitted()){
            $this->member_data->extract();
            $this->member_data->validate();
        }
        else{
            $this->member_data->load($user->ID);
        }       
        include QUICKR_VIEWS . 'admin/members/member_edit_form.php';
    }
    /**
     * 
     * @param type $user_id
     * @return boolean
     */
    public function save_user_profile($user_id){
        if ( !current_user_can( 'edit_user') ){
            return false;        
        }
        $this->member_data->extract();
        $this->member_data->validate();                
        $this->member_data->ID = $user_id; 
        $this->member_data->save();
    }
    /**
     * 
     * @param type $errors
     * @param type $update
     * @param type $user
     * @return type
     */
    public function validate_fields($errors, $update, $user) {        
        if (!empty($errors->errors)){return;}
        if (empty($update)){
            $this->member_data->extract();  
            $this->member_data->validate();
        }
        if ($this->member_data->has_error()){
            foreach($this->member_data->errors as $key=>$value){
                $errors->add('quickr-error-' . $key, $value);
            }
        }
    }
}
