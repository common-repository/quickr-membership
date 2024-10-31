<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-quickr-user-validator
 *
 * @author nur85
 */
require_once QUICKR_CLASSES . 'data/class-quickr-member-data.php';
class Quickr_Member_Validator {

    private $error_code;

    public function __construct() {
        ;
    }

    public function validate($user, $username, $password) {
        if (is_wp_error($user) || user_can($user,'administrator')) {
            return $user;
        }
        $member = new Quickr_Member_Data();
        $member->load($user->ID);
        if ($member->account_status == '') {
            return $user;
        }

        if ($member->account_status == Quickr_Constants::member_status_active) {
            return $user;
        }
        
        if ($member->account_status == Quickr_Constants::member_status_expired) {            
            $allow_expired_login = Quickr_Settings::get_instance()->get_value('allow-expired-account');
            if (!empty($allow_expired_login)) {return $user;}
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("Sorry! Your account has expired.")); 
        }
        if ($member->account_status == Quickr_Constants::member_status_incomplete) {
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("Your registration is incomplete. Please follow the link to complete registration. <a href=''>here</a>"));
        }
        if ($member->account_status == Quickr_Constants::member_status_pending) {
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("Your account is pending admin approval."));
        }
        if ($member->account_status == Quickr_Constants::member_status_inactive) {
            return new WP_Error(
                    Quickr_I18n::_("Access Denied")
                    , Quickr_I18n::_("Your account is currently inactive."));
        }
        return new WP_Error(
                Quickr_I18n::_("Access Denied")
                , Quickr_I18n::_("Something went wrong."));
    }

    public function login_failed($username) {

        // Getting URL of the login page
        $referrer = filter_input(INPUT_SERVER, 'HTTP_REFERER');
        // if there's a valid referrer, and it's not the default log-in screen
        if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
            $login_page = Quickr_Settings::get_instance()->get_value('login-page-url');
            wp_redirect($login_page . "?login=failed");
            exit;
        }
    }

    public function login_errors($error) {
        return $error;
    }

    public function login_form() {
        $reg_link = Quickr_Settings::get_instance()->get_value('signup-page-url');
        echo  '<p>' . Quickr_I18n::_('Not a member?'). '<a href="'. $reg_link . '">' . Quickr_I18n::_('register') .'</a></p>';
    }

}
