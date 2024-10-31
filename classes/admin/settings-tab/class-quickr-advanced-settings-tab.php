<?php

/**
 * Quickr_Advanced_Tab
 *
 * @author nur858
 */
require_once QUICKR_PATH . 'classes/common/class-quickr-tag-maker.php';

class Quickr_Advanced_Settings_Tab {
    private $status;
    public function __construct() {
        $this->status = '';
    }

    /**
     *
     */
    public function init() {
        //Register settings sections and fileds for the general settings tab.
        register_setting('quickr_settings_tab_settings_advanced', 'quickr-settings', array(&$this, 'sanitize'));
        $tag_maker = new Quickr_Tag_Maker();
        $settings = Quickr_Settings::get_instance();
        add_settings_section('advanced-settings', Quickr_I18n::_('Advanced Misc. Settings'), array(&$this, 'advanced_misc_settings_callback'), 'quickr_settings');
        add_settings_field('allow-expired-account', Quickr_I18n::_('Allow expired Account login'), array($tag_maker, 'checkbox_callback'), 'quickr_settings', 'advanced-settings', array(
            'item' => 'allow-expired-account',
            'message' => '',
            'value' => $settings->get_value('allow-expired-account')));
        add_settings_field('enable-logging', Quickr_I18n::_('Enable Logging'), array($tag_maker, 'checkbox_callback'), 'quickr_settings', 'advanced-settings', array(
            'item' => 'enable-logging',
            'message' => '',
            'value' => $settings->get_value('enable-logging')));        
    }
    public function notices(){
        $updated = sanitize_text_field(filter_input(INPUT_GET,'settings-updated'));
        $general = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        if (!empty($updated) && ($general == 'settings_advanced')) {
            Quickr_Utils::update_message('Setting Updated');
            
        }        
    }
    
    public function render_content(){
        echo 'render_content';
    }
    /**
     *
     * @param type $input
     * @return type
     */
      public function sanitize($input) {
        $output = (array) get_option('quickr-settings');
        $output['allow-expired-account'] = isset($input['allow-expired-account']) ? esc_attr($input['allow-expired-account']) : "";
        $output['enable-logging'] = isset($input['enable-logging']) ? esc_attr($input['enable-logging']) : "";
        return $output;
    }   
   public function advanced_misc_settings_callback() {
        
    }
}
