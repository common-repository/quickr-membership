<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Quickr_Menu_Builder
 *
 * @author nur
 */
class Quickr_Menu_Builder {

    /**
     *
     * @var type 
     */
    private $menu_slug;

    /**
     * 
     */
    public function __construct() {
        $this->menu_slug = "quickr_membership";
    }

    /**
     * 
     */
    public function build() {
        add_menu_page(Quickr_I18n::_("Quickr Member"), Quickr_I18n::_("Quickr Member"), 'manage_options', $this->menu_slug, array(&$this, "dashboard_menu"), 'dashicons-id');

        add_submenu_page($this->menu_slug, Quickr_I18n::_("Member"), Quickr_I18n::_('Member'), 'manage_options', 'quickr_member', array(&$this, "members_menu"));

        add_submenu_page($this->menu_slug, Quickr_I18n::_("Membership Level"), Quickr_I18n::_("Membership Level"), 'manage_options', 'quickr_membership_level', array(&$this, "memberships_menu"));

        add_submenu_page($this->menu_slug, Quickr_I18n::_("Payments"), Quickr_I18n::_("Payments"), 'manage_options', 'quickr_payments', array(&$this, "payments_menu"));

        add_submenu_page($this->menu_slug, Quickr_I18n::_("Settings"), Quickr_I18n::_("Settings"), 'manage_options', 'quickr_settings', array(&$this, "settings_menu"));

        add_submenu_page($this->menu_slug, Quickr_I18n::_("Extensions"), Quickr_I18n::_("Extensions"), 'manage_options', 'quickr_extensions', array(&$this, "extensions_menu"));

        //todo:do_action

        $this->meta_box();
    }

    public function dashboard_menu() {
        require_once QUICKR_CLASSES . 'admin/menus/class-quickr-dashboard-menu.php';
        $menu = new Quickr_Dashboard_Menu();
        $menu->render();
        /*require_once QUICKR_CLASSES . 'admin/widget/class-quickr-admin-member-stats.php';
        $d = new Quickr_Admin_Member_Stats();
        global $wp_widget_factory;
        Quickr_Widget_System::register_widget( 'Quickr_Admin_Member_Stats');
    the_widget('Quickr_Admin_Member_Stats', array('title'=>'TITLE', ), array('widget_id'=>'arbitrary-instance-xxx',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
        ?>
        <?php*/
    }

    /**
     * 
     */
    public function members_menu() {
        require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-members-menu.php';
        $menu = new Quickr_Members_Menu();
        $menu->render();
    }

    /**
     * 
     */
    public function memberships_menu() {
        require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-memberships-menu.php';
        $menu = new Quickr_Memberships_Menu();
        $menu->render();
    }

    /**
     * 
     */
    public function settings_menu() {
        require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-settings-menu.php';
        $menu = new Quickr_Settings_Menu();
        $menu->render();
    }

    /**
     * 
     */
    public function payments_menu() {
        require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-payments-menu.php';
        $menu = new Quickr_Payments_Menu();
        $menu->render();
    }

    /**
     * 
     */
    public function extensions_menu() {
        require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-extensions-menu.php';
        $menu = new Quickr_Extensions_Menu();
        $menu->render();
    }

    /**
     * 
     */
    private function meta_box() {
        
    }

}
