<?php

/**
 * Quickr_Email_Tab
 *
 * @author nur858
 */
require_once QUICKR_PATH . 'classes/common/class-quickr-tag-maker.php';

class Quickr_Email_Settings_Tab { 

    public function __construct() {
   
    }

    /**
     *
     */
    public function init() {
        //Register settings sections and fileds for the general settings tab.  
        register_setting('quickr_settings_tab_settings_email', 'quickr-settings', array(&$this, 'sanitize'));
        $tag_maker = new Quickr_Tag_Maker();
        $settings = Quickr_Settings::get_instance();
        add_settings_section('email-misc-settings', Quickr_I18n::_('Email Misc. Settings'), array(&$this, 'email_misc_settings_callback'), 'quickr_settings');
        add_settings_field('email-misc-from', Quickr_I18n::_('From Email Address'), array($tag_maker, 'textfield_callback'), 'quickr_settings', 'email-misc-settings', array(
            'item' => 'email-misc-from',
            'message' => 'This value will be used as the sender\'s address for the emails. Example value: Your Name &lt;sales@your-domain.com&gt;',
            'value' => $settings->get_value('email-misc-from')));

        //Prompt to complete registration email settings
        add_settings_section('reg-prompt-email-settings', Quickr_I18n::_('Email Settings (Prompt to Complete Registration )'), array(&$this, 'reg_prompt_email_settings_callback'), 'quickr_settings');
        add_settings_field('reg-prompt-complete-mail-subject', Quickr_I18n::_('Email Subject'), array($tag_maker, 'textfield_callback'), 'quickr_settings', 'reg-prompt-email-settings', array(
            'item' => 'reg-prompt-complete-mail-subject',
            'message' => '',
            'value' => $settings->get_value('reg-prompt-complete-mail-subject')
        ));
        add_settings_field('reg-prompt-complete-mail-body', Quickr_I18n::_('Email Body'), array($tag_maker, 'textarea_callback'), 'quickr_settings', 'reg-prompt-email-settings', array(
            'item' => 'reg-prompt-complete-mail-body',
            'message' => '',
            'value' => $settings->get_value('reg-prompt-complete-mail-body')));

        //Registration complete email settings
        $msg_for_admin_notify_email_field = Quickr_I18n::_('Enter the email address where you want the admin notification email to be sent to.');
        $msg_for_admin_notify_email_field .= Quickr_I18n::_(' You can put multiple email addresses separated by comma (,) in the above field to send the notification to multiple email addresses.');
        add_settings_section('reg-email-settings', Quickr_I18n::_('Email Settings (Registration Complete)'), array(&$this, 'reg_email_settings_callback'), 'quickr_settings');
        add_settings_field('reg-complete-mail-subject', Quickr_I18n::_('Email Subject'), array($tag_maker, 'textfield_callback'), 'quickr_settings', 'reg-email-settings', array(
            'item' => 'reg-complete-mail-subject',
            'message' => '',
            'value' => $settings->get_value('reg-complete-mail-subject')));
        add_settings_field('reg-complete-mail-body', Quickr_I18n::_('Email Body'), array($tag_maker, 'textarea_callback'), 'quickr_settings', 'reg-email-settings', array(
            'item' => 'reg-complete-mail-body',
            'message' => '',
            'value' => $settings->get_value('reg-complete-mail-body')));
        add_settings_field('enable-admin-notification-after-reg', Quickr_I18n::_('Send Notification to Admin'), array($tag_maker, 'checkbox_callback'), 'quickr_settings', 'reg-email-settings', array(
            'item' => 'enable-admin-notification-after-reg',
            'message' => Quickr_I18n::_('Enable this option if you want the admin to receive a notification when a member registers.'),
            'value' => $settings->get_value('enable-admin-notification-after-reg')));
        add_settings_field('admin-notification-email', Quickr_I18n::_('Admin Email Address'), array($tag_maker, 'textfield_callback'), 'quickr_settings', 'reg-email-settings', array(
            'item' => 'admin-notification-email',
            'message' => $msg_for_admin_notify_email_field,
            'value' => $settings->get_value('admin-notification-email')));
        add_settings_field('enable-notification-after-manual-user-add', Quickr_I18n::_('Send Email to Member When Added via Admin Dashboard'), array($tag_maker, 'checkbox_callback'), 'quickr_settings', 'reg-email-settings', array(
            'item' => 'enable-notification-after-manual-user-add',
            'message' => '',
            'value' => $settings->get_value('enable-notification-after-manual-user-add')));

        //Password reset email settings
        /*add_settings_section('reset-password-settings', Quickr_I18n::_('Email Settings (Password Reset)'), array(&$this, 'reset_password_settings_callback'), 'quickr_settings');
        add_settings_field('reset-mail-subject', Quickr_I18n::_('Email Subject'), array($tag_maker, 'textfield_callback'), 'quickr_settings', 'reset-password-settings', array(
            'item' => 'reset-mail-subject',
            'message' => '',
            'value' => $settings->get_value('reset-mail-subject')));
        add_settings_field('reset-mail-body', Quickr_I18n::_('Email Body'), array($tag_maker, 'textarea_callback'), 'quickr_settings', 'reset-password-settings', array(
            'item' => 'reset-mail-body',
            'message' => '',
            'value' => $settings->get_value('reset-mail-body')));*/

        //Account upgrade email settings
        add_settings_section('upgrade-email-settings', Quickr_I18n::_(' Email Settings (Account Upgrade Notification)'), array(&$this, 'upgrade_email_settings_callback'), 'quickr_settings');
        add_settings_field('upgrade-complete-mail-subject', Quickr_I18n::_('Email Subject'), array($tag_maker, 'textfield_callback'), 'quickr_settings', 'upgrade-email-settings', array(
            'item' => 'upgrade-complete-mail-subject',
            'message' => '',
            'value' => $settings->get_value('upgrade-complete-mail-subject')));
        add_settings_field('upgrade-complete-mail-body', Quickr_I18n::_('Email Body'), array($tag_maker, 'textarea_callback'), 'quickr_settings', 'upgrade-email-settings', array(
            'item' => 'upgrade-complete-mail-body',
            'message' => '',
            'value' => $settings->get_value('upgrade-complete-mail-body')));

        //Bulk account activate and notify email settings.
        add_settings_section('bulk-activate-email-settings', Quickr_I18n::_(' Email Settings (Bulk Account Activate Notification)'), array(&$this, 'bulk_activate_email_settings_callback'), 'quickr_settings');
        add_settings_field('bulk-activate-notify-mail-subject', Quickr_I18n::_('Email Subject'), array($tag_maker, 'textfield_callback'), 'quickr_settings', 'bulk-activate-email-settings', array(
            'item' => 'bulk-activate-notify-mail-subject',
            'message' => '',
            'value' => $settings->get_value('bulk-activate-notify-mail-subject')));
        add_settings_field('bulk-activate-notify-mail-body', Quickr_I18n::_('Email Body'), array($tag_maker, 'textarea_callback'), 'quickr_settings', 'bulk-activate-email-settings', array(
            'item' => 'bulk-activate-notify-mail-body',
            'message' => '',
            'value' => $settings->get_value('bulk-activate-notify-mail-body')));
    }

    public function notices() {
        $updated = sanitize_text_field(filter_input(INPUT_GET, 'settings-updated'));
        $general = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if (!empty($updated) && ($general == 'settings_email')) {
            Quickr_Utils::update_message('Setting Updated');
        }
    }

    public function render_content() {
        echo 'render_content';
    }

    /**
     *
     * @param type $input
     * @return type
     */
    public function sanitize($input) {
        $output = (array) get_option('quickr-settings');

        $output['reg-complete-mail-subject'] = sanitize_text_field($input['reg-complete-mail-subject']);
        $output['reg-complete-mail-body'] = wp_kses_data(force_balance_tags($input['reg-complete-mail-body']));

        /*$output['reset-mail-subject'] = sanitize_text_field($input['reset-mail-subject']);
        $output['reset-mail-body'] = wp_kses_data(force_balance_tags($input['reset-mail-body']));*/

        $output['upgrade-complete-mail-subject'] = sanitize_text_field($input['upgrade-complete-mail-subject']);
        $output['upgrade-complete-mail-body'] = wp_kses_data(force_balance_tags($input['upgrade-complete-mail-body']));

        $output['bulk-activate-notify-mail-subject'] = sanitize_text_field($input['bulk-activate-notify-mail-subject']);
        $output['bulk-activate-notify-mail-body'] = wp_kses_data(force_balance_tags($input['bulk-activate-notify-mail-body']));

        $output['reg-prompt-complete-mail-subject'] = sanitize_text_field($input['reg-prompt-complete-mail-subject']);
        $output['reg-prompt-complete-mail-body'] = wp_kses_data(force_balance_tags($input['reg-prompt-complete-mail-body']));
        $output['email-misc-from'] = sanitize_email($input['email-misc-from']);
        $output['enable-admin-notification-after-reg'] = isset($input['enable-admin-notification-after-reg']) ? esc_attr($input['enable-admin-notification-after-reg']) : "";
        $output['admin-notification-email'] = sanitize_text_field($input['admin-notification-email']);
        $output['enable-notification-after-manual-user-add'] = isset($input['enable-notification-after-manual-user-add']) ? esc_attr($input['enable-notification-after-manual-user-add']) : "";
        
        return $output;
    }

    public function reg_email_settings_callback() {
        Quickr_I18n::e('This email will be sent to your users when they complete the registration and become a member.');
    }

    public function reset_password_settings_callback() {
        Quickr_I18n::e('This email will be sent to your users when they use the password reset functionality.');
    }

    public function email_misc_settings_callback() {
        Quickr_I18n::e('Settings in this section apply to all emails.');
    }

    public function upgrade_email_settings_callback() {
        Quickr_I18n::e('This email will be sent to your users after account upgrade (when an existing member pays for a new membership level).');
    }

    public function bulk_activate_email_settings_callback() {
        Quickr_I18n::e('This email will be sent to your members when you use the bulk account activate and notify action.');
    }

    public function reg_prompt_email_settings_callback() {
        Quickr_I18n::e('This email will be sent to prompt users to complete registration after the payment.');
    }

}
