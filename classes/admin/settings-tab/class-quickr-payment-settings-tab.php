<?php

/**
 * Quickr_Payment_Settings_Tab
 *
 * @author nur858
 */
require_once QUICKR_CLASSES . 'common/class-quickr-tag-maker.php';

class Quickr_Payment_Settings_Tab {

    public function __construct() {
        
    }

    public function init() {
        register_setting('quickr_settings_tab_settings_payment', 'quickr-settings', array(&$this, 'sanitize'));
        $tag_maker = new Quickr_Tag_Maker();
        $settings = Quickr_Settings::get_instance();
        add_settings_section('paypal-settings', Quickr_I18n::_('Paypal Settings'), array(&$this, 'paypal_settings_callback'), 'quickr_settings');
        add_settings_field('enable-paypal-sanbox-mode', Quickr_I18n::_('Enable Sanbox Mode'), array($tag_maker, 'checkbox_callback'), 'quickr_settings', 'paypal-settings', array(
            'item' => 'enable-paypal-sanbox-mode',
            'message' => Quickr_I18n::_ ('Enable this setting if you want to verify payment processing through test payments.'),
            'value' => $settings->get_value('enable-paypal-sanbox-mode')));        
        add_settings_field('paypal-ipn-url', Quickr_I18n::_('Paypal IPN listener URL'), array($tag_maker, 'textfield_long_readonly_callback'), 'quickr_settings', 'paypal-settings', array(
            'item' => 'paypal-ipn-url',
            'message' => Quickr_I18n::_ ('URL that paypal uses to send back payment information when a payment is made. This is helpful when you want to use hosted button. '
                    . 'Note: This is a readonly field'),
            'value' => add_query_arg('quickr_ipn_call', 1, get_home_url())));                
    }

    public function notices() {
        $updated = sanitize_text_field(filter_input(INPUT_GET, 'settings-updated'));
        $general = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if (!empty($updated) && ($general == 'settings_payment')) {
            Quickr_Utils::update_message('Setting Updated');
        }
    }

    public function sanitize($input) {
       $output = (array) get_option('quickr-settings');
        $output['enable-paypal-sanbox-mode'] = isset($input['enable-paypal-sanbox-mode']) ? esc_attr($input['enable-paypal-sanbox-mode']) : "";
        return $output;        
    }
 public function paypal_settings_callback() {
        
    }
}
