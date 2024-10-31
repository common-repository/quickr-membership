<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Membership_List_Tab
 *
 * @author nur858
 */
class Quickr_Membership_List_Tab {
    public function __construct() {
        
    }
    /**
     * 
     */
    public function render_content(){
        require_once QUICKR_PATH. 'classes/admin/grid/class-quickr-membership-grid.php';
        $grid = new Quickr_Membership_Grid();
        $quickr_membership_search = sanitize_text_field(filter_input(INPUT_POST, 'quickr_membership_search'));
        include_once QUICKR_PATH . 'views/admin/memberships/membership_grid.php';
    }
    public function init(){
        $tab = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if ($tab != 'membership_list') {return;}
        $action = Quickr_Utils::get_query_param('action');
        if (empty($action)) {$action = sanitize_text_field(filter_input(INPUT_POST, 'action'));}  
        if ($action == 'delete') {$this->delete();}
        
    }
    public function notices(){
          $tab = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if ($tab != 'membership_list') {return;}
        $deleted = sanitize_text_field(filter_input(INPUT_GET, 'deleted'));
        if (!empty($deleted)) {Quickr_Utils::update_message('Deleted successfully');}                    
    }
    
    private function delete(){ 
        $level_id = absint(filter_input(INPUT_GET, 'level_id'));
        if ($level_id > 0){
            $this->delete_one($level_id);
            return;
        }
        $membershps = filter_input(INPUT_POST, 'membership', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
 
        if (count($membershps)){
            foreach($membershps as $membership){$this->delete_one (absint($membership));}
        }
        wp_redirect(add_query_arg('deleted', '1'));
        exit();
    }
    
    private function delete_one($level_id){
        wp_delete_post($level_id, true);
    }
}
