<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Payment_List_Tab
 *
 * @author nur858
 */
class Quickr_Payment_List_Tab {
    /**
     * contructor that initializes payment history page.
     */
    public function render_content(){ 
        require_once QUICKR_PATH. 'classes/admin/grid/class-quickr-payment-grid.php';
        $action = sanitize_text_field(filter_input(INPUT_GET, 'action'));
        if ($action == 'view'){
            $this->display_transaction_details();
            return; 
        }
        $grid = new Quickr_Payment_Grid();
        $quickr_txn_search = sanitize_text_field(filter_input(INPUT_POST, 'quickr_txn_search'));
        include_once QUICKR_PATH . 'views/admin/payments/payment_grid.php';
    }
    private function display_transaction_details(){
            global $wpdb;
            $txn_id = absint(filter_input(INPUT_GET, 'txn_id'));
            $response_data = json_decode($wpdb->get_var($wpdb->prepare('SELECT txn_response_data FROM ' . 
                    $wpdb->prefix . Quickr_Constants::transaction . ' WHERE txn_id=%d', $txn_id)), true);
            include_once QUICKR_PATH . 'views/admin/payments/payment_details.php';
        
    }
}
