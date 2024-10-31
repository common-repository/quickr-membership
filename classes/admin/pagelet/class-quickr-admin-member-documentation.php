<?php

/**
 * quickr-admin-member-stats
 *
 * @author nur85
 */
class Quickr_Admin_Member_Documentation {

    public function __construct() {
        add_action('quickr_admin_dashboard_pagelet_show', array($this, 'show'));
    }

    public function show() {
        include QUICKR_PATH . 'views/admin/dashboard/pagelet/documentation.php';
    }

}
