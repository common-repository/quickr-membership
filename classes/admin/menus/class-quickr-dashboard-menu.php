<?php
/**
 * class-quickr-dashboard-menu
 *
 * @author nur85
 */
class Quickr_Dashboard_Menu {
    public function render(){
        require_once QUICKR_CLASSES . 'admin/pagelet/class-quickr-admin-member-stats.php';
        require_once QUICKR_CLASSES . 'admin/pagelet/class-quickr-admin-member-documentation.php';
        new Quickr_Admin_Member_Stats();
        new Quickr_Admin_Member_Documentation();
        include QUICKR_PATH . 'views/admin/dashboard/container.php';
    }
}
