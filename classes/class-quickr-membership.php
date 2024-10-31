<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * class-quickr-membership
 *
 * @author Quickr
 */
class Quickr_Membership {
    public function __construct() {
    }
    /**
     * loads dependencies and registers various hooks
     */
    public function load(){
        $this->load_common();
        is_admin()? $this->load_admin():$this->load_front();
    } 
    /**
     * loads dependencies and registers various hooks that are 
     * common to fronend and admin.
     */
    private function load_common(){        
        require_once QUICKR_CLASSES . 'common/class-quickr-utils.php';
        require_once QUICKR_CLASSES . 'common/class-quickr-common-dispatcher.php';
        require_once QUICKR_CLASSES . 'common/class-quickr-batch-processor.php';
        require_once QUICKR_CLASSES . 'common/class-quickr-emailer.php';
        require_once QUICKR_CLASSES . 'common/class-quickr-paypal-listener.php';
        require_once QUICKR_CLASSES . 'common/class-quickr-logger.php';
        require_once QUICKR_CLASSES . '3rdparty/Quickr_Logging.php';
        require_once QUICKR_CLASSES . 'common/class-quickr-widget-system.php';
        
        $this->register_common_hooks();
        $this->register_locale();
        $this->register_batch_listener();
        $this->register_payment_listener();
        add_filter('widget_text', 'do_shortcode');
    }
    /**
     * loads dependencies and registers various hooks for admin
     */
    private function load_admin(){
        require_once QUICKR_CLASSES . 'admin/class-quickr-admin-dispatcher.php';
        require_once QUICKR_CLASSES . 'admin/class-quickr-menu-builder.php';
        require_once QUICKR_CLASSES . 'admin/class-quickr-configurator.php';
        require_once QUICKR_CLASSES . 'admin/class-quickr-log-exporter.php';
        $this->register_admin_hooks();
    }
    /**
     * loads dependencies and registers various hooks for frontend
     */
    private function load_front(){
        require_once QUICKR_CLASSES . 'front/class-quickr-front-dispatcher.php';
        require_once QUICKR_CLASSES . 'front/class-quickr-shortcode-processor.php';
        $this->register_front_hooks();
        $this->register_shortcodes();
    }
    /**
     * initialize localization
     */
    private function register_locale(){
        $i18n = new Quickr_I18n();
        $i18n->register_hooks();
    }
    /**
     * set cron job processor/listener.
     */
    private function register_batch_listener(){
        $processor = new Quickr_Batch_Processor();
        $processor->register_hooks();
    }
    private function register_payment_listener(){
        $paypal = new Quickr_Paypal_Listener();
        $paypal->register_hooks();
    }
            
    /**
     * loads dependencies and registers various hooks common to frontend and admin
     */
    private function register_common_hooks(){
        $dispatcher = new Quickr_Common_Dispatcher();
        $dispatcher->register_hooks();
    }
    /**
     * loads dependencies and registers various hooks for fronend
     */
    private function register_front_hooks(){
        $dispatcher = new Quickr_front_Dispatcher();
        $dispatcher->register_hooks();
    }
    private function register_shortcodes(){
        $processor = new Quickr_Shortcode_Processor();
        $processor->register_shortcode();
        
    }
    /**
     * loads dependencies and registers various hooks for admin
     */
    private function register_admin_hooks(){
        $dispatcher = new Quickr_Admin_Dispatcher();
        $dispatcher->register_hooks();
    }
}