<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Members_Menu
 *
 * @author nur858
 */
require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-abstract-menu.php' ;
class Quickr_Members_Menu extends Quickr_Abstract_Menu{
    /**
     * constructor that registers tab rendering hook and determines current tab.
     * 
     */
    public function __construct() {
        add_action('quickr-draw-members-nav-tabs', array(&$this, 'render_tab'));        
        parent::__construct();
    }
    public function register_hooks(){        
 
    }
    public function default_tab() {
        return 'members_approve';
    }
    /**
     * method to render the page content in the given templae.
     */
    public function render(){
        parent::render();
        // finally trigger settings actions
        include QUICKR_PATH . 'views/admin/members/template.php';
    }
    /**
     * method to render tabs in members page.
     */
    public function render_tab(){
        //Setup the available settings tabs array.
        $tabs = array(
            //'members_import' => Quickr_I18n::_('Import Members'),
            'members_approve' => Quickr_I18n::_('Approve Members')
        );  
        $tabs = apply_filters('quickr_members_tabs', $tabs);
        include QUICKR_PATH . 'views/admin/members/tab.php';
    }
}
