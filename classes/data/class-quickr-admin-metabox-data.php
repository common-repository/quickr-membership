<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Admin_Metabox_Data
 *
 * @author nur
 */
class Quickr_Admin_Metabox_Data extends Quickr_Data {

    public $membership_levels;
    public $selected_levels;

    public function __construct() {
        parent::__construct();
    }

    public function extract() {
        $args = array('quickr_membership' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags' => FILTER_REQUIRE_ARRAY,
        ));
        $quickr_membership = filter_input_array(INPUT_POST, $args);
        $this->selected_levels = $quickr_membership['quickr_membership'];
    }

    public function insert() {
        
    }

    public function is_submitted() {
        
    }

    public function load($id) {
        $cpt_args = array(
            'post_type' => 'quickr_member_level',
            'post_status' => 'publish');
        $this->membership_levels = get_posts($cpt_args);
        global $wpdb;
        $sql = $wpdb->prepare("SELECT membership_ID FROM "
                . $wpdb->prefix . Quickr_Constants::membership_post_rel
                . " WHERE post_ID=%d", $id);
        $selected_levels = $wpdb->get_col($sql);
        $this->selected_levels = empty($selected_levels) ? array() : $selected_levels;        
    }

    public function update($id) {
        global $wpdb;
        foreach ($this->selected_levels as $mid) {
            $sql = $wpdb->prepare("INSERT IGNORE INTO "
                    . $wpdb->prefix . Quickr_Constants::membership_post_rel
                    . "(post_ID, membership_ID) VALUES(%d, %d);", $id, absint($mid));
            $wpdb->query($sql);
        }
    }

    public function validate() {
        
    }

    public function save() {
        //parent::save();
    }

}
