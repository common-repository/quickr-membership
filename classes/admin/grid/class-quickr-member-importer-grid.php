<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Member_Importer_Grid
 *
 * @author nur858
 */
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
class Quickr_Member_Importer_Grid extends WP_List_Table {
    /**
     * 
     */
    function __construct() {
        parent::__construct(array(
            'singular' => Quickr_I18n::_('Member'),
            'plural' => Quickr_I18n::_('Members'),
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
            , 'ID' => Quickr_I18n::_('ID')
            , 'user_login' => Quickr_I18n::_('Username')
            , 'user_email' => Quickr_I18n::_('Email')
            , 'membership_level' => Quickr_I18n::_('Membership Level')
            , 'account_status' => Quickr_I18n::_('Account Status')
        );
    }
    /**
     * 
     * @return type
     */
    function get_sortable_columns() {
        return array(
            'ID' => array('ID', true),//True means already sorted
            'user_login' => array('user_login', false),
            'user_email' => array('_user_email', false)
        );
    }
    /**
     * 
     * @return type
     */
    function get_bulk_actions() {
        $actions = array(
            'bulk_delete' => Quickr_I18n::_('Delete'),
            'bulk_active' => Quickr_I18n::_('Set Status to Active'),
            'bulk_active_notify' => Quickr_I18n::_('Set Status to Active and Notify'),
            'bulk_inactive' => Quickr_I18n::_('Set Status to Inactive'),
            'bulk_pending' => Quickr_I18n::_('Set Status to Pending'),
            'bulk_expired' => Quickr_I18n::_('Set Status to Expired'),            
        );
        return $actions;
    }
    /**
     * 
     * @param type $item
     * @param type $column_name
     * @return type
     */
    function column_membership_level($item) {
        $membership_dropdown = Quickr_Membership_Form_Data::membership_dropdown();
        return ' <select >'
        . $membership_dropdown
        . '</select>';
    }
    function column_account_status($item) {
        $account_status_dropdown = Quickr_Admin_Member_Form_Data::account_status_dropdown();
        return ' <select >'
        . $account_status_dropdown
        . '</select>';
    }    
    /**
     * 
     * @param type $item
     * @return type
     */
    function column_ID($item) {
        $actions = array(
            'edit' => sprintf('<a href="admin.php?page=%s&member_action=edit&member_id=%s">Edit</a>', $_REQUEST['page'], $item['ID']),
            'delete' => sprintf('<a href="?page=%s&member_action=delete&member_id=%s"
                                    onclick="return confirm(\'Are you sure you want to delete this entry?\')">Delete</a>', $_REQUEST['page'], $item['ID']),
        );
        return $item['ID'] . $this->row_actions($actions);
    }
    function column_default($item, $col_name){
        return $item[$col_name];
    }
    /**
     * 
     * @param type $item
     * @return type
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="members[]" value="%s" />', $item['ID']);
    }
    /**
     * 
     * @global type $wpdb
     */
    function prepare_items() {
        global $wpdb;

        $query = "SELECT * FROM $wpdb->users where ID NOT IN (SELECT ID FROM {$wpdb->prefix}quickr_members)";
        //Execute the query
        $totalitems = 0;//$wpdb->query($query); //return the total number of affected rows
        
        //Pagination setup
        $perpage = apply_filters('quickr_members_import_items_per_page', 50);
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
        $this->items = array();//$wpdb->get_results($query, ARRAY_A);
    }
    /**
     * 
     */
    function no_items() {
        _e('Nothing to import.');
    }
}
