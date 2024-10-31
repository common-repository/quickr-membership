<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Description of class-quckr-payment-button-grid
 *
 * @author nur
 */
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class Quickr_Payment_Button_Grid extends WP_List_Table {

    private $items_per_page;

    /**
     * 
     */
    function __construct() {
        parent::__construct(array(
            'singular' => Quickr_I18n::_('Payment'),
            'plural' => Quickr_I18n::_('Payments'),
            'ajax' => false
        ));
        $this->items_per_page = apply_filters('quickr_membership_items_per_page', 50);
    }

    /**
     * 
     * @return type
     */
    function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />'
            , 'ID' => Quickr_I18n::_('ID')
            , 'title' => Quickr_I18n::_('Title')
            , 'button_type' => Quickr_I18n::_('Button type')
            , 'billing_processor' => Quickr_I18n::_('Payment Processor')
            , 'billing_amount' => Quickr_I18n::_('Billing Amount')
            , 'button_code' => Quickr_I18n::_('Button Code')
        );
    }

    /**
     * 
     * @return type
     */
    function get_sortable_columns() {
        return array(
            'ID' => array('ID', true), //True means already sorted
            'title' => array('title', false),
            'button_type' => array('button_type', false)
        );
    }

    /**
     * 
     * @return type
     */
    function get_bulk_actions() {
        $actions = array(
            'bulk_delete' => Quickr_I18n::_('Delete'),
        );
        return $actions;
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="button[]" value="%s" />', $item['ID']);
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

    public function column_billing_amount($item) {
      return  $item['billing_amount'] . ' ' . $item['billing_currency']; 
    }

    function column_button_code($item) {
        $level = get_post_meta($item['ID'], Quickr_Constants::billing_membership_level_metakey, true);;
        if (Quickr_Utils::is_valid_membership_level($level)){
        return sprintf("<input type='text' readonly='readonly' value='[quickr_button id=%s]' />", $item['ID']);
        }
        
        return '<span color=\'red\'>' . Quickr_I18n::_("Error! Membership level associated with this button has been deleted.") . '</span>';
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    function column_ID($item) {
        $page = sanitize_text_field(filter_input(INPUT_GET, 'page'));
        $button_type = $item['button_type'];
        $processor = $item['billing_processor'];
        $edit = admin_url(sprintf('admin.php?tab=payments_%s&step=1&page=%s&button_type=%s&button_id=%s', $processor, $page, $button_type, $item['ID']));
        $delete = admin_url(sprintf('admin.php?page=%s&tab=payments_buttons&action=delete&button_id=%s', $page, $item['ID']));
        $actions = array(
            'edit' => '<a href="' . $edit . '">Edit</a>',
            'delete' => '<a href="' . $delete . '"
                            onclick="return confirm(\'Are you sure you want to delete this entry?\')">Delete</a>',
        );
        return $item['ID'] . $this->row_actions($actions);
    }

    /**
     * 
     * @global type $wpdb
     */
    function prepare_items() {
        $totalitems = $this->get_total_items();
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => ceil($totalitems / $this->items_per_page),
            "per_page" => $this->items_per_page,
        ));
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->get_data();
    }

    private function get_data() {
        $paged = max(1, absint(filter_input(INPUT_GET, 'paged')));
        $data = array();
        $cpt_args = array(
            'post_type' => 'quickr_pay_button',
            'post_status' => 'publish',
            'posts_per_page' => $this->items_per_page,
            'paged' => $paged
        );
        $items = get_posts($cpt_args);

        if (empty($items)) {
            return $data;
        }
        foreach ($items as $item) {
            $record = self::get_meta($item->ID);
            $record['ID'] = $item->ID;
            $record['title'] = $item->post_title;
            $record['button_type'] = $item->post_content;
            $data[] = $record;
        }
        return $data;
    }

    private static function get_meta($id) {
        $data = array();
        $data['billing_amount'] = get_post_meta($id, Quickr_Constants::billing_amount_metakey, true);
        $data['billing_currency'] = get_post_meta($id, Quickr_Constants::billing_currency_metakey, true);
        $data['billing_processor'] = get_post_meta($id, Quickr_Constants::billing_payment_processer_metakey, true);
        return $data;
    }

    function get_total_items() {
        $counts = wp_count_posts('quickr_pay_button');
        return $counts->publish;
        /* foreach ($counts as $count){
          $total += $count;
          }
          return $total; */
    }

    /**
     * 
     */
    function no_items() {
        _e('No button found.');
    }

}
