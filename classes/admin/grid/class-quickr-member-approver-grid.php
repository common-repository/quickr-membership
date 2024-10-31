<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Member_Importer_Grid
 *
 * @author nur858
 */
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class Quickr_Member_Approver_Grid extends WP_List_Table {

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
            , 'account_state' => Quickr_I18n::_('Account State')
        );
    }

    /**
     * 
     * @return type
     */
    function get_sortable_columns() {
        return array(
            'ID' => array('ID', true), //True means already sorted
            'user_login' => array('user_login', false),
            'user_email' => array('user_email', false)
        );
    }

    /**
     * 
     * @return type
     */
    function get_bulk_actions() {
        $actions = array(
            'active' => Quickr_I18n::_('Set Status to Active'),
            'active_notify' => Quickr_I18n::_('Set Status to Active and Notify'),
            'inactive' => Quickr_I18n::_('Set Status to Inactive'),
            'pending' => Quickr_I18n::_('Set Status to Pending'),
            'expired' => Quickr_I18n::_('Set Status to Expired'),
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
        $membership = get_post($item['membership_ID']);
        return $membership->post_title;
    }

    function column_account_state($item) {
        $account_status = get_user_meta($item['ID'], Quickr_Constants::member_account_status_metakey, true);
        return Quickr_Admin_Member_Form_Data::get_account_status_name($account_status);
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    function column_ID($item) {
        $actions = array(
            'edit' => sprintf('<a href="%s">Edit</a>', Quickr_Utils::get_edit_user_link($item['ID'])),
            'detail' =>sprintf('<a href="">Preview</a>')
        );
        return $item['ID'] . $this->row_actions($actions);
    }

    function column_default($item, $col_name) {
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
        $query = "SELECT U.ID as ID, user_login, user_email, membership_ID FROM $wpdb->users U"
                . " INNER JOIN " . $wpdb->prefix . Quickr_Constants::membership_rel . " MR ON (MR.user_ID = U.ID)"
                . " INNER JOIN $wpdb->usermeta UM ON ( U.ID = UM.user_id) "
                . " WHERE meta_key = '" . Quickr_constants::member_account_status_metakey
                . "' AND meta_value = '" . Quickr_Constants::member_status_pending . "'";
        $search = sanitize_text_field(filter_input(INPUT_POST, 'quickr_member_search'));

        if(!empty($search)){
            $search = '%' . $wpdb->esc_like($search) . '%';
            $query .= $wpdb->prepare(" AND (user_email LIKE %s OR user_login LIKE %s)", $search, $search); 
        }
        //Execute the query
        $totalitems = $wpdb->query($query); //return the total number of affected rows
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
        $this->items = $wpdb->get_results($query, ARRAY_A);
    }

    /**
     * 
     */
    function no_items() {
        _e('Nothing to approve.');
    }

}
