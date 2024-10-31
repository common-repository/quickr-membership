<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Member_Importer_Grid
 *
 * @author nur858
 */
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class Quickr_Membership_Grid extends WP_List_Table {

    private $items_per_page;

    /**
     * 
     */
    function __construct() {
        parent::__construct(array(
            'singular' => Quickr_I18n::_('Membership Level'),
            'plural' => Quickr_I18n::_('Membership Levels'),
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
            , 'name' => Quickr_I18n::_('Name')
            , 'duration_type' => Quickr_I18n::_('Duration Type')
            , 'duration' => Quickr_I18n::_('Validity')
            , 'protect_older_posts' => Quickr_I18n::_('Protect Older Posts')
            , 'campaign_name' => Quickr_I18n::_('Campaign Name')
        );
    }

    /**
     * 
     * @return type
     */
    function get_sortable_columns() {
        return array(
            'ID' => array('ID', true), //True means already sorted
            'name' => array('name', false)
        );
    }

    /**
     * 
     * @return type
     */
    function get_bulk_actions() {
        $actions = array(
            'delete' => Quickr_I18n::_('Delete')
        );
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

    public function column_duration($item) {
        if ($item['duration_type'] == 'fixed') {
            return Quickr_I18n::_('Until') . ' ' . $item['duration'];
        }
        if ($item['duration'] == 0){
            return Quickr_I18n::_('Never Expires');
        }
        return $item['duration'] . ' ' . Quickr_I18n::_('Day(s)');
    }

    public function column_duration_type($item) {
        return Quickr_Membership_Form_Data::get_duration_type_name($item['duration_type']);
    }

    public function column_protect_older_posts($item) {
        return empty($item['protect_older_posts']) ? Quickr_I18n::_("No") : Quickr_I18n::_("Yes");
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    function column_ID($item) {
        $page = sanitize_text_field(filter_input(INPUT_GET, 'page'));
        $duration_type = $item['duration_type'];
        $edit = admin_url(sprintf('admin.php?tab=membership_add&step=1&page=%s&duration_type=%s&level_id=%s', $page,$duration_type, $item['ID']));
        $delete = admin_url(sprintf('admin.php?page=%s&tab=membership_list&action=delete&level_id=%s', $page, $item['ID']));
        $actions = array(
            'edit' => '<a href="'.$edit.'">Edit</a>',
            'delete' => '<a href="'. $delete.'"
                            onclick="return confirm(\'Are you sure you want to delete this entry?\')">Delete</a>',
        );
        return $item['ID'] . $this->row_actions($actions);
    }

    /**
     * 
     * @param type $item
     * @return type
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="membership[]" value="%s" />', $item['ID']
        );
    }

    /**
     * 
     * @global type $wpdb
     */
    function prepare_items() {
        $totalitems = $this->get_total_items();
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => ceil($totalitems/$this->items_per_page),
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
            'post_type' => 'quickr_member_level',
            'post_status' => 'publish',
            'posts_per_page' => $this->items_per_page,
            'paged' => $paged
        );
        $items = get_posts($cpt_args);
        if (empty($items)) {
            return $data;
        }
        foreach ($items as $item) {
            $duration_type = get_post_meta($item->ID, Quickr_Constants::membership_duration_type_metakey, true);
            $protect_older_posts = get_post_meta($item->ID, Quickr_Constants::membership_protect_older_posts_metakey, true);
            $campaign_name = get_post_meta($item->ID, Quickr_Constants::membership_campaign_name_metakey, true);
            $data[] = array(
                'ID' => $item->ID,
                'name' => $item->post_title,
                'duration_type' => $duration_type,
                'duration' => $item->post_content,
                'protect_older_posts' => $protect_older_posts,
                'campaign_name' => $campaign_name
            );
        }
        return $data;
    }
    function get_total_items() {
        $counts = wp_count_posts('quickr_member_level');
        return $counts->publish;
        /*$total = 0;
        foreach ($counts as $count){
            $total += $count;
        }
        return $total;*/
    }
    /**
     * 
     */
    function no_items() {
        _e('No membershiop level found. Please click <a href="admin.php?page=quickr_membership_level&tab=membership_add">here</a> to define membership level.');
    }

}
