<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Payment_List_Tab
 *
 * @author nur858
 */
class Quickr_Button_List_Tab {
    /**
     * contructor that initializes payment history page.
     */
    public function render_content(){ 
        require_once QUICKR_PATH. 'classes/admin/grid/class-quickr-payment-button-grid.php';
        $grid = new Quickr_Payment_Button_Grid();
        $quickr_button_search = filter_input(INPUT_POST, 'quickr_button_search');
        include_once QUICKR_PATH . 'views/admin/payments/button_grid.php';
    }
    public function init(){
        $tab = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if ($tab != 'payments_buttons') {return;}
        $action = Quickr_Utils::get_query_param('action');
        if ($action == 'delete') {$this->delete();}
        
    }
    public function notices(){
          $tab = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if ($tab != 'payments_buttons') {return;}
        $deleted = sanitize_text_field(filter_input(INPUT_GET, 'deleted'));
        if (!empty($deleted)) {Quickr_Utils::update_message('Deleted successfully');}                    
    }    
    private function delete(){ 
        $button_id = absint(filter_input(INPUT_GET, 'button_id'));
        if ($button_id > 0){
            $this->delete_one($button_id);
            return;
        }
        $membershps = filter_input(INPUT_POST, 'button', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
 
        if (count($membershps)){
            foreach($membershps as $membership){$this->delete_one (absint($membership));}
        }
        wp_redirect(add_query_arg('deleted', '1'));
        exit();
    }
    
    private function delete_one($button_id){
        wp_delete_post($button_id, true);
    }
}
