<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Payments_Menu
 *
 * @author nur858
 */
require_once QUICKR_PATH . 'classes/admin/menus/class-quickr-abstract-menu.php' ;
class Quickr_Payments_Menu extends Quickr_Abstract_Menu {
    public function __construct() {
        //Register the draw tab action hook. It will be triggered using do_action("swpm-draw-settings-nav-tabs")
        add_action('quickr-draw-payments-nav-tabs', array(&$this, 'render_tab'));
        parent::__construct();
    }
    public function default_tab() {
        return 'payments_history';
    }
    /**
     * 
     */
    public function render(){
        parent::render();
        // finally trigger settings actions
        include QUICKR_PATH . 'views/admin/payments/template.php';
    }
    /**
     * 
     */
    public function render_tab(){
        //Setup the available settings tabs array.
        $tabs = array(
            'payments_history' => Quickr_I18n::_('Payment History'),
            'payments_buttons' => Quickr_I18n::_('Payment Buttons'),
            'payments_paypal' => Quickr_I18n::_("PayPal"),
            /*'payments_stripe' => Quickr_I18n::_("Stripe"),
            'payments_authorize'=> Quickr_I18n::_("Authorize.net"),*/
        );  
        $tabs = apply_filters('quickr_payments_tabs', $tabs);
        include QUICKR_PATH . 'views/admin/payments/tab.php';
    }
}
