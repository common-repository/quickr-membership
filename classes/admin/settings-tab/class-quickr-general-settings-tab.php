<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-quickr-general-tab
 *
 * @author nur85
 */
require_once QUICKR_PATH . 'classes/common/class-quickr-tag-maker.php';
require_once QUICKR_CLASSES . 'data/class-quickr-membership-form-data.php';
class Quickr_General_Settings_Tab {
    
    public function __construct() {
    ;
    }
    
    
    /**
     *
     */
    public function init() { 
        //Register settings sections and fileds for the general settings tab.
        register_setting('quickr_settings_tab_settings_general', 'quickr-settings', array(&$this, 'sanitize'));
        $tag_maker = new Quickr_Tag_Maker();
        $settings = Quickr_Settings::get_instance();
        //This settings section has no heading
        //add_settings_section('swpm-general-post-submission-check', '', array(&$this, 'swpm_general_post_submit_check_callback'), 'simple_wp_membership_settings');
        //add_settings_section('swpm-documentation', SwpmUtils::_('Plugin Documentation'), array(&$this, 'swpm_documentation_callback'), 'simple_wp_membership_settings');
        add_settings_section('general-settings', Quickr_I18n::_('General Settings'), array(&$this, 'general_settings_callback'), 'quickr_settings');
        add_settings_field('enable-free-membership', Quickr_I18n::_('Enable Free Membership'), array($tag_maker, 'checkbox_callback'), 'quickr_settings', 'general-settings', array(
            'item' => 'enable-free-membership',
            'value' => $settings->get_value('enable-free-membership'),
            'message' => Quickr_I18n::_('Enable/disable registration for free membership level. When you enable this option, '
                    . 'make sure to specify a free membership level ID in the field below.')));

        add_settings_field('free-membership-id', Quickr_I18n::_('Free Membership Level ID'), array($tag_maker, 'selectbox_callback'), 'quickr_settings', 'general-settings', array(
            'item' => 'free-membership-id',
            'options' => Quickr_Membership_Form_Data::membership_dropdown($settings->get_value('free-membership-id', '2')),
            'selected' => $settings->get_value('free-membership-id', '2'),
            'message' => Quickr_I18n::_('Free membership ID needs to be defined if free membership level enabled. these two settings work together')));

        add_settings_field('default-account-status', Quickr_I18n::_('Default Account Status'), array($tag_maker, 'selectbox_callback'), 'quickr_settings', 'general-settings', array('item' => 'default-account-status',
            'options' => Quickr_Admin_Member_Form_Data::account_status_dropdown($settings->get_value('default-account-status', 'active')),
            'selected' => $settings->get_value('default-account-status', 'active'),
            'message' => Quickr_I18n::_('Select the default account status for newly registered users. If you want to manually approve the members then you can set the status to "Pending".')));

        add_settings_field('enable-moretag', Quickr_I18n::_('Enable More Tag Protection'), array($tag_maker, 'checkbox_callback'), 'quickr_settings', 'general-settings', array(
            'item' => 'enable-moretag',
            'value' => $settings->get_value('enable-moregtag'),
            'message' => Quickr_I18n::_('Enables or disables "more" tag protection in the posts and pages. Anything after the More tag is protected. Anything before the more tag is teaser content.')));
        add_settings_field('hide-adminbar', Quickr_I18n::_('Hide Adminbar'), array($tag_maker, 'selectbox_callback'), 'quickr_settings', 'general-settings', array(
            'item' => 'hide-adminbar',       
            'options' => array(''=>  Quickr_I18n::_('None')
                              ,'front' => Quickr_I18n::_('Frontend Only')
                              ,'admin' => Quickr_I18n::_('Admin Only')
                              ,'both'  => Quickr_I18n::_('Both')
                ),
            'selected' => $settings->get_value('hide-adminbar', ''),            
            'message' => Quickr_I18n::_('WordPress shows an admin toolbar to the logged in users of the site. Check this if you want to hide that admin toolbar in the frontend of your site.')));
        
        add_settings_section('pages-settings', Quickr_I18n::_('Pages Settings'), array(&$this, 'pages_settings_callback'), 'quickr_settings');
        add_settings_field('login-page-url', Quickr_I18n::_('Login Page URL'), array($tag_maker, 'textfield_long_callback'), 'quickr_settings', 'pages-settings', array('item' => 'login-page-url',
            'message' => '',
            'value' => $settings->get_value('login-page-url')));
        add_settings_field('registration-page-url', Quickr_I18n::_('Registration Page URL'), array($tag_maker, 'textfield_long_callback'), 'quickr_settings', 'pages-settings', array('item' => 'registration-page-url',
            'message' => '',
            'value' => $settings->get_value('registration-page-url')));
        add_settings_field('signup-page-url', Quickr_I18n::_('Signup Page URL'), array($tag_maker, 'textfield_long_callback'), 'quickr_settings', 'pages-settings', array('item' => 'signup-page-url',
            'message' => '',
            'value' => $settings->get_value('signup-page-url')));
        add_settings_field('profile-page-url', Quickr_I18n::_('Edit Profile Page URL'), array($tag_maker, 'textfield_long_callback'), 'quickr_settings', 'pages-settings', array('item' => 'profile-page-url',
            'message' => '',
            'value' => $settings->get_value('profile-page-url')));
        add_settings_field('password-reset-page-url', Quickr_I18n::_('Password Reset Page URL'), array($tag_maker, 'textfield_long_callback'), 'quickr_settings', 'pages-settings', array('item' => 'reset-page-url',
            'message' => '',
            'value' => $settings->get_value('password-reset-page-url')));
    }
    
    public function notices(){
        $updated = sanitize_text_field(filter_input(INPUT_GET, 'settings-updated'));
        $general = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if (!empty($updated) && (empty($general) || ($general == 'settings_general'))) {
            Quickr_Utils::update_message('Setting Updated');
        }
    }
    
    public function render_content(){
        echo 'render';
    }
    /**
     *
     */
    public function general_settings_callback() {
        Quickr_I18n::e('General Plugin Settings.');
    }

    public function pages_settings_callback() {
        Quickr_I18n::_("Page Setup and URL Related settings");
    }
   /**
     *
     * @param type $input
     * @return type
     */
    public function sanitize($input) {
        $output = (array) get_option('quickr-settings');
        $output['enable-free-membership'] = isset($input['enable-free-membership']) ? esc_attr($input['enable-free-membership']) : "";
        $output['enable-moretag'] = isset($input['enable-moretag']) ? esc_attr($input['enable-moretag']) : "";
        $output['hide-adminbar'] = esc_attr($input['hide-adminbar']);
        $output['free-membership-id'] = ($input['free-membership-id'] != 1) ? absint($input['free-membership-id']) : '';
        $output['login-page-url'] = esc_url($input['login-page-url']);
        $output['registration-page-url'] = esc_url($input['registration-page-url']);
        $output['profile-page-url'] = esc_url($input['profile-page-url']);
        $output['password-reset-page-url'] = esc_url($input['reset-page-url']);
        $output['signup-page-url'] = esc_url($input['signup-page-url']);
        $output['default-account-status'] = esc_attr($input['default-account-status']);
        $output['members-login-to-comment'] = isset($input['members-login-to-comment']) ? esc_attr($input['members-login-to-comment']) : "";
        
        return $output;
    }
}
