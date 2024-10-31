<?php

/**
 * quickr-admin-member-stats
 *
 * @author nur85
 */
class Quickr_Admin_Member_Stats {

    public function __construct() {
        add_action('quickr_admin_dashboard_pagelet_show', array($this, 'show'));
    }

    public function show() {
        $membership_count = wp_count_posts('quickr_member_level')->publish;
        global $wpdb;
        $user_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users INNER JOIN $wpdb->usermeta on user_id = ID WHERE meta_key= %s", Quickr_Constants::billing_membership_level_metakey));
        $payment_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . Quickr_Constants::transaction  );
        include QUICKR_PATH . 'views/admin/dashboard/pagelet/at_a_glance.php';
    }

}
