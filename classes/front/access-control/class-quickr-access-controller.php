<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Access_Controller
 *
 * @author nur858
 */
class Quickr_Access_Controller {

    public function __construct() {
        ;
    }

    /**
     * 
     * @global type $wpdb
     * @param type $post_id
     * @return type
     */
    public static function is_protected($post_id) {
        global $wpdb;
        $sql = $wpdb->prepare(
                "SELECT COUNT(membership_ID) AS c FROM "
                . $wpdb->prefix . Quickr_Constants::membership_post_rel
                . " WHERE post_ID=%d", $post_id
        );
        $count = $wpdb->get_var($sql);
        return $count > 0;
    }

    /**
     * 
     * @global type $wpdb
     * @param type $post_id
     * @param type $membership_level
     * @return type
     */
    public static function is_membership_level_permitted($post_id, $membership_level) {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT membership_ID FROM  "
                . $wpdb->prefix . Quickr_Constants::membership_post_rel
                . " WHERE post_ID = %d AND membership_ID=%d", $post_id, $membership_level);
        $membership_id = $wpdb->get_var($sql);
        return $membership_id == $membership_level;
    }

    /**
     * 
     * @param type $post_id
     * @return boolean
     */
    public static function is_accessible($post_id) {
        if (!self::is_protected($post_id)) {
            return true;
        }
        if (!is_user_logged_in()) {
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("You need to log in to view more."));
        }
        require_once QUICKR_CLASSES . 'data/class-quickr-member-data.php';
        $user = new Quickr_Member_Data();
        $user->load(get_current_user_id());
        if (!self::is_membership_level_permitted($post_id, $user->membership_ID)) {
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("Your membership level doesn't have access to this content."));
        }
        if ($user->account_status == 'active') {
            return true;
        }
        if ($user->account_status == 'expired') {
            $settings = Quickr_Settings::get_instance();
            $allow_expired_account = $settings->get_value('allow-expired-account');
            if (empty($allow_expired_account)) {
                return new WP_Error(
                        Quickr_I18n::_("Access Denied")
                        , Quickr_I18n::_("Your subscription has expired."));
            }
            return true;
        }
        if ($user->account_status == 'incomplete') {
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("Your registration is incomplete.Please complete to get full access."));
        }
        if ($user->account_status == 'pending') {
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("Your account in under review."));
        }
        return new WP_Error(
                Quickr_I18n::_("Access Denied")
                , Quickr_I18n::_("You don't have access to this content."));
        ;
    }

}
