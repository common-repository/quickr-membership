<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Member_Import_Tab
 *
 * @author nur858
 */
class Quickr_Member_Approve_Tab {

    /**
     * 
     */
    public function render_content() {
        require_once QUICKR_CLASSES . 'admin/grid/class-quickr-member-approver-grid.php';
        $grid = new Quickr_Member_Approver_Grid();
        $quickr_member_search = sanitize_text_field(filter_input(INPUT_POST, 'quickr_member_search'));
        include_once QUICKR_PATH . 'views/admin/members/member_approve.php';
    }

    public function init() {
        $tab = sanitize_text_field(filter_input(INPUT_GET, 'tab'));

        if ($tab != 'members_approve') {
            return;
        }
        $action = Quickr_Utils::get_query_param('action');
        if (!empty($action)) {
            $this->change_account_status($action);
        }
    }

    public function notices() {
        $tab = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if ($tab != 'members_approve') {
            return;
        }
        $deleted = sanitize_text_field(filter_input(INPUT_GET, 'deleted'));
        if (!empty($deleted)) {
            Quickr_Utils::update_message('Deleted successfully');
        }
    }

    private function change_account_status($action) {
        $members = filter_input(INPUT_POST, 'members', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if (empty($members)) {
            return;
        }
        foreach ($members as $value) {
            $member = absint($value);
            if (empty($member)) {
                continue;
            }            
            switch ($action) {
                case 'active':
                    update_user_meta($member, Quickr_Constants::member_account_status_metakey, Quickr_Constants::member_status_active);
                    break;
                case 'inactive':
                    update_user_meta($member, Quickr_Constants::member_account_status_metakey, Quickr_Constants::member_status_inactive);
                    break;
                case 'active_notify':
                    update_user_meta($member, Quickr_Constants::member_account_status_metakey, Quickr_Constants::member_status_active);
                    Quickr_Emailer::send_active_account_notification($member);
                    break;
                case 'pending':
                    update_user_meta($member, Quickr_Constants::member_account_status_metakey, Quickr_Constants::member_status_pending);
                    break;
                case 'expired':
                    update_user_meta($member, Quickr_Constants::member_account_status_metakey, Quickr_Constants::member_status_expired);
                    break;
            }
        }
    }

}
