<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Members_Menu
 *
 * @author nur858
 */
require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-abstract-menu.php' ;
class Quickr_Memberships_Menu extends Quickr_Abstract_Menu{
    /**
     * 
     */
    public function __construct() {
        //Register the draw tab action hook. It will be triggered using do_action("swpm-draw-settings-nav-tabs")
        add_action('quickr-draw-memberships-nav-tabs', array(&$this, 'render_tab'));
        parent::__construct();
    }
    public function default_tab() {
        return 'membership_list';
    }
    /**
     * 
     */
    public function render(){
        parent::render();
        // finally trigger settings actions
        include QUICKR_PATH . 'views/admin/memberships/template.php';
    }
    /**
     * 
     */
    public function render_tab(){
        //Setup the available settings tabs array.
        $tabs = array(
            'membership_list' => Quickr_I18n::_('Membership Levels'),
            'membership_add' => Quickr_I18n::_('Add/Edit Membership Level')
        );  
        $tabs = apply_filters('quickr_memberships_tabs', $tabs);
        include QUICKR_PATH . 'views/admin/memberships/tab.php';
    }
}