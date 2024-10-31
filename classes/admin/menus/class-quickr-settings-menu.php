<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Settings_Menu
 *
 * @author nur85
 */
require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-abstract-menu.php';

class Quickr_Settings_Menu extends Quickr_Abstract_Menu {

    public function __construct() {
        //Register the draw tab action hook. It will be triggered using do_action("swpm-draw-settings-nav-tabs")
        add_action('quickr-draw-settings-nav-tabs', array(&$this, 'render_tab'));
        parent::__construct();
    }
    public function default_tab() {
        return 'settings_general';
    }
    /**
     * 
     */
    public function render() {
        parent::render();
        // finally trigger settings actions
        include QUICKR_PATH . 'views/admin/settings/template.php';
    }

    /**
     * 
     */
    public function render_tab() {
        //Setup the available settings tabs array.
        $tabs = array(
            'settings_general' => Quickr_I18n::_('General Settings'),
            'settings_email' => Quickr_I18n::_('Email Settings'),
            'settings_payment' => Quickr_I18n::_('Payment Settings'),
            'settings_advanced' => Quickr_I18n::_('Advanced Settings'),
            'settings_extension' => Quickr_I18n::_('Extensions Settings')
        );
        $tabs = apply_filters('quickr_settings_tabs', $tabs);
        include QUICKR_PATH . 'views/admin/settings/tab.php';
    }
}
