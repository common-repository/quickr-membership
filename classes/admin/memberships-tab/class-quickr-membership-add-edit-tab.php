<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Membership_List_Tab
 *
 * @author nur858
 */
require_once QUICKR_CLASSES . 'data/class-quickr-membership-form-data.php';
class Quickr_Membership_Add_Edit_Tab {

    public $membership_data;

    public function __construct() {
        $this->membership_data = new Quickr_Membership_Form_Data();
    }

    public function init() {
        if (!$this->membership_data->is_submitted()) {
            return;
        }
        $membership_id = absint(filter_input(INPUT_GET, 'level_id'));
        $this->membership_data->extract();
        $this->membership_data->validate();
        if (!$this->membership_data->has_error()){
            empty($membership_id)?$this->membership_data->insert(): $this->membership_data->update($membership_id);
            //todo: redirect
        }
    }
    public function notices(){
        if (!$this->membership_data->is_submitted()) {
            return;
        }
        if (!$this->membership_data->has_error()){
            echo Quickr_Utils::update_message('Updated Successfully');
        }
    }
    /**
     * 
     */
    public function render_content() {
        $step = absint(filter_input(INPUT_GET, 'step'));
        if ($step != 1) {
            include_once QUICKR_VIEWS . 'admin/memberships/add_membership_level_step1.php';
            return;
        }
        
        $membership_id = absint(filter_input(INPUT_GET, 'level_id'));
        empty($membership_id)? $this->add(): $this->edit($membership_id);
    }
    private function edit($membership_id){
        $this->membership_data->is_submitted()?$this->membership_data->extract():$this->membership_data->load($membership_id);
        $membership = $this->membership_data;
        if ($this->membership_data->duration_type == 'fixed') {
            include_once QUICKR_VIEWS . 'admin/memberships/add_membership_level_fixed.php';
            return;
        }
        if ($this->membership_data->duration_type == 'variable'){
            include_once QUICKR_VIEWS . 'admin/memberships/add_membership_level_variable.php';
            return;
        }        
        wp_die('Invaild opeation');
    }
    private function add(){
        $duration_type = sanitize_text_field(filter_input(INPUT_GET, 'duration_type'));
        if($this->membership_data->is_submitted()){$this->membership_data->extract();}
        $membership = $this->membership_data;
        if ($duration_type == 'fixed') {
            $this->membership_data->duration = date('Y-m-d', strtotime('+30 days', time()));
            include_once QUICKR_VIEWS . 'admin/memberships/add_membership_level_fixed.php';
            return;
        }
        if ($duration_type == 'variable'){
            $this->membership_data->duration = 0;
            include_once QUICKR_VIEWS . 'admin/memberships/add_membership_level_variable.php';        
            return;
        }
        wp_die('Invaild operation');
    }
}
