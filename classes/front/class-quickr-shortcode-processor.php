<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Shortcode_Processor
 *
 * @author nur858
 */
class Quickr_Shortcode_Processor {

    private static $errors;

    public function register_shortcode() {
        add_shortcode('quickr_login_form', array($this, 'login_form'));
        add_shortcode('quickr_password_reset_form', array($this, 'password_reset_form'));

        add_shortcode('quickr_profile_form', array($this, 'profile_form'));
        add_action('quickr_form_profile_submit', array($this, 'profile'));

        add_shortcode('quickr_registration_form', array($this, 'registration_form'));
        add_action('quickr_form_registration_submit', array($this, 'registration'));
        
        add_shortcode('quickr_button', array($this, 'render_button'));
    }
    public function render_button($atts){
        $id = isset($atts['id']) ? $atts['id'] : '';
        if (empty($id)){
            return '<div class="error">' . Quickr_I18n::_('Error! Shortcode does\'t contain button ID.') . '</div>';
        }
        $post = get_post($id);
        if (empty($post)){
            return '<div class="error">' . Quickr_I18n::_('Error! Button cannot be found. It may have been deleted.') . '</div>';
        }
        if ($post->post_type != 'quickr_pay_button'){
            return '<div class="error">' . Quickr_I18n::_('Error! Given ID is not a valid button type.') . '</div>';
        }
        $output = apply_filters('quickr_pay_render_' . $post->post_content, '', $id, $atts);
        return empty($output)?'<div class="error">' . Quickr_I18n::_('Error! Button generation failed. Given button type is not implemented yet.') . '</div>':$output;
    }
    public function profile() {
        if (!is_user_logged_in()) {
            wp_die(Quickr_I18n::_('This is not allowed'), Quickr_I18n::_('Forbidden!'));
        }
        $password = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'password'), 'sanitize_text_field', 'esc_html');
        $email = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'email'), 'sanitize_email', 'esc_html');
        $website = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'website'), 'sanitize_text_field', 'esc_url');
        $fname = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'fname'), 'sanitize_text_field', 'esc_html');
        $lname = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'lname'), 'sanitize_text_field', 'esc_html');
        $nickname = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'nickname'), 'sanitize_text_field', 'esc_html');

        $user_data = array();
        $user_data['ID'] = get_current_user_id();
        if (!empty($password)) {
            $user_data['user_pass'] = $password;
        }
        if (!empty($email)) {
            $user_data['user_email'] = $email;
        }
        if (!empty($website)) {
            $user_data['user_url'] = $website;
        }
        if (!empty($fname)) {
            $user_data['first_name'] = $fname;
        }
        if (!empty($lname)) {
            $user_data['last_name'] = $lname;
        }
        if (!empty($nickname)) {
            $user_data['nickname'] = $nickname;
        }
        self::$errors = wp_update_user($user_data);
        if (!is_wp_error(self::$errors)) {
            
        }
    }

    public function registration() {
        $reg_code = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_GET, 'quickr_reg_code'), 'sanitize_text_field', 'esc_html');
        if (!empty($reg_code)) {
            $this->validate_and_activate($reg_code);
        }
        
        // at this point everything is free level if there is one.
        $free_level = Quickr_Utils::get_free_membership_level();

        if (empty($free_level)) {
            return;
        }

        $user = Quickr_Utils::sanitize_and_scape( filter_input(INPUT_POST, 'user_login'), 'sanitize_text_field', 'esc_html');
        $email = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'user_email'), 'sanitize_email', 'esc_html');
        self::$errors = register_new_user($user, $email);
        if (!is_wp_error(self::$errors)) {
            wp_update_user(array('ID'=>self::$errors, 
                'role'=>get_post_meta(self::$errors, Quickr_Constants::membership_role_metakey, true)));
            require_once (QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php');
            $member = new Quickr_Admin_Member_Form_Data();
            $member->account_status = Quickr_Constants::member_status_active;
            $member->membership_ID = $free_level;
            $member->activation_date = date('Y-m-d');
            $member->expiration_date = Quickr_Utils::calculate_expiration_date($member->activation_date, '0000-00-00', $member->membership_ID);
            $member->update(self::$errors);
            $redirect_to = Quickr_Settings::get_instance()->get_value('login-page-url');            
            wp_safe_redirect(empty($redirect_to) ? 'wp-login.php?registered=true' : $redirect_to);
            exit();
        }
    }

    public function login_form($atts) {

        if (!is_user_logged_in()) { // Display WordPress login form:
            $args = array(
                'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                'form_id' => 'loginform-custom',
                'label_username' => Quickr_I18n::_('Username'),
                'label_password' => Quickr_I18n::_('Password'),
                'label_remember' => Quickr_I18n::_('Remember Me'),
                'label_log_in' => Quickr_I18n::_('Log In'),
                'remember' => true
            );
            wp_login_form($args);
        } else { // If logged in:
            wp_loginout(home_url()); // Display "Log Out" link.
            echo " | ";
            wp_register('', ''); // Display "Site Admin" link.
        }
    }

    public function password_reset_form($atts) {
        $login_url = Quickr_Settings::get_instance()->get_value('login-page-url');
        include QUICKR_VIEWS . 'front/password_reset.php';
    }

    public function profile_form($atts) {
        if (!is_user_logged_in()) {
            return;
        }
        global $current_user;
        require_once QUICKR_CLASSES . 'data/class-quickr-member-data.php';

        $member = new Quickr_Member_Data();
        $member->load($current_user->ID);
        $uri = filter_input(INPUT_SERVER, ' ');
        $errors = empty(self::$errors) ? new WP_Error(null, null, null) : self::$errors;
        include QUICKR_VIEWS . 'front/profile.php';
    }

    private function validate_and_activate($reg_code) {
        if (empty($reg_code)) {
            return;
        }
        $users = get_users(
                array(
                    'meta_key' => Quickr_Constants::member_reg_code_metakey,
                    'meta_value' => $reg_code,
                    'number' => 1,
                    'count_total' => false
                )
        );
        if (count($users) > 0) {
            $user_id = $users[0]->ID;
            global $wpdb;
            $user_login = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'user_login'), 'sanitize_text_field', 'esc_html');
            require_once (QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php');
            $member = new Quickr_Admin_Member_Form_Data();
            $member->load($user_id);
            $member->account_status = Quickr_Constants::member_status_active;
            $member->activation_date = date('Y-m-d');
            $member->update($user_id);            
            
            $wpdb->update($wpdb->users, array('user_login' => $user_login), array('ID' => $user_id));
            $user_pass = wp_generate_password( 12, false );
            $user_info = array('ID'=>$user_id, 'user_pass'=>$user_pass);
            if (!empty($user_login)){$user_info['user_login'] = $user_login;}
            wp_update_user($user_info);
            update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
            Quickr_Emailer::send_registration_email($user_id);
        }
    }

    public function registration_form($atts) {
        $reg_code = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_GET, 'quickr_reg_code'), 'sanitize_text_field', 'esc_html');
        self::$errors = empty(self::$errors) ? new WP_Error(null, null, null) : self::$errors;
        if (empty($reg_code)) {
            $this->registration_form_free_level();
        } else {
            $this->registration_form_reg_code($reg_code);
        }
    }

    private function registration_form_free_level() {
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $enable_free_level = Quickr_Settings::get_instance()->get_value('enable-free-membership');
        $free_level = Quickr_Settings::get_instance()->get_value('free-membership-id');
        if (empty($enable_free_level)) {
            Quickr_I18n::e('Error! Free membership level is not enabled.');
            return;
        }

        if (empty($free_level)) {
            Quickr_I18n::e('Error! Freem membership level is not defined.');
            return;
        }
        $user_login = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'user_login'), 'sanitize_text_field', 'esc_html');
        $user_email = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'user_email'), 'sanitize_email', 'esc_html');
        $membership_level  = get_the_title($free_level);
        $errors = self::$errors;
        include QUICKR_VIEWS . 'front/registration.php';
    }

    private function registration_form_reg_code($reg_code) {
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $users = get_users(
                array(
                    'meta_key' => Quickr_Constants::member_reg_code_metakey,
                    'meta_value' => $reg_code,
                    'number' => 1,
                    'count_total' => false,
                    'fields' => array('ID', 'user_login', 'user_email')
                )
        );
        if (count($users) < 1) {
            Quickr_I18n::e('Error! Invalid registration code');
            return;
        }
        require_once QUICKR_CLASSES . 'data/class-quickr-member-data.php';

        $member = new Quickr_Member_Data();
        $member->load($users[0]->ID);        
        
        $status = get_user_meta($users[0]->ID, Quickr_Constants::member_account_status_metakey, true);
        if ($member->account_status != Quickr_Constants::member_status_incomplete) {
            Quickr_I18n::e('Error! Registration code has already been used.');
        }
        $user_login = $users[0]->user_login;
        $user_email = $users[0]->user_email;
        $membership_level  = $member->membership_name;
        $errors = self::$errors;
        include QUICKR_VIEWS . 'front/registration.php';
    }

}
