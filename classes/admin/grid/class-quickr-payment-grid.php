<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Payment_Grid
 *
 * @author nur858
 */
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
class Quickr_Payment_Grid extends WP_List_Table {
    /**
     * 
     */
    function __construct() {
        parent::__construct(array(
            'singular' => Quickr_I18n::_('Payment'),
            'plural' => Quickr_I18n::_('Payments'),
            'ajax' => false
        ));
    }
    /**
     * 
     * @return type
     */
    function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />'
            , 'txn_id' => Quickr_I18n::_('Transaction ID')
            , 'email' => Quickr_I18n::_('Payer Email')
            , 'txn_date' => Quickr_I18n::_('Transaction Date')
            , 'txn_amount' => Quickr_I18n::_('Transaction Amount')
            , 'txn_currency' => Quickr_I18n::_('Transaction Currency')
            , 'txn_processor' => Quickr_I18n::_('Payment Processor')
            , 'txn_status' => Quickr_I18n::_('Payment Status')
        );
    }
    /**
     * 
     * @return type
     */
    function get_sortable_columns() {
        return array(
            'txn_id' => array('txn_id', true),//True means already sorted
            'email' => array('email', false),
            'txn_date' => array('txn_date', false),
            'txn_processor' => array('txn_processor', false)
        );
    }
    /**
     * 
     * @return type
     */
    function get_bulk_actions() {
        $actions = array();
        return $actions;
    }
    /**
     * 
     * @param type $item
     * @param type $column_name
     * @return type
     */
    function column_default($item, $column_name) {
        return $item[$column_name];
    }
    /**
     * 
     * @param type $item
     * @return type
     */
    function column_txn_id($item) {
        $url = sprintf("admin.php?page=quickr_payments&tab=payments_history&action=view&txn_id=%s", $item['txn_id']);
        $actions = array(
            'detail' => sprintf('<a target="_blank" href="'. $url.'">Detail</a>'),
        );
        return $item['txn_id'] . $this->row_actions($actions);
    }
    
    /**
     * 
     * @global type $wpdb
     */
    function prepare_items() {
        global $wpdb;

        $query = "SELECT * FROM " . $wpdb->prefix . Quickr_Constants::transaction . " ";
        //Execute the query
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        $quickr_txn_search = sanitize_text_field(filter_input(INPUT_GET, 'quickr_txn_search'));
        if (!empty($quickr_txn_search)){
            $query .= ' WHERE email LIKE \'%'. esc_sql($quickr_txn_search) .'%\'';
            $query .= ' OR first_name LIKE \'%'. esc_sql($quickr_txn_search) .'%\'';
            $query .= ' OR last_name LIKE \'%'. esc_sql($quickr_txn_search) .'%\'';
        }
        $orderby = sanitize_text_field(filter_input(INPUT_GET, 'orderby'));
        $order = sanitize_text_field(filter_input(INPUT_GET, 'order'));
        $orderby = empty($orderby) ? ' ID' : $orderby  ;
        $order = empty($order) ? ' DESC' : $order;

        if (!empty($orderby) && !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }
        
        //Pagination setup
        $perpage = apply_filters('quickr_transaction_items_per_page', 50);
        $paged = absint(filter_input(INPUT_GET, 'paged'));
        if (empty($paged)) {
            $paged = 1;
        }
        $totalpages = ceil($totalitems / $perpage);
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $wpdb->get_results($query, ARRAY_A);
    }
    /**
     * 
     */
    function no_items() {
        _e('No payment made yet.');
    }
}
