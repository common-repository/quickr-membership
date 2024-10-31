<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Extensions_Menu
 *
 * @author nur85
 */
require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-abstract-menu.php';
class Quickr_Extensions_Menu extends Quickr_Abstract_Menu {
    public function __construct() {
        //Register the draw tab action hook. It will be triggered using do_action("swpm-draw-settings-nav-tabs")
        add_action('quickr-draw-extensions-nav-tabs', array(&$this, 'render_tab'));
        parent::__construct();
    }
    public function default_tab() {
        return 'extensions_general';
    }
    /**
     * 
     */
    public function render() {
        parent::render();
        // finally trigger settings actions
        include QUICKR_PATH . 'views/admin/extensions/template.php';
    }

    /**
     * 
     */
    public function render_tab() {
        //Setup the available settings tabs array.
        $tabs = array(
            'extensions_general' => Quickr_I18n::_('General')
        );
        $tabs = apply_filters('quickr_extensions_tabs', $tabs);
        include QUICKR_PATH . 'views/admin/extensions/tab.php';
    }
}
