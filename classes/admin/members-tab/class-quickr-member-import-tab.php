<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Member_Import_Tab
 *
 * @author nur858
 */
class Quickr_Member_Import_Tab {
    /**
     * 
     */
    public function render_content(){
        require_once QUICKR_PATH. 'classes/admin/grid/class-quickr-member-importer-grid.php';
        $grid = new Quickr_Member_Importer_Grid();
        $quickr_member_search = sanitize_text_field(filter_input(INPUT_POST, 'quickr_member_search'));
        include_once QUICKR_PATH . 'views/admin/members/member_import.php';
    }
}
