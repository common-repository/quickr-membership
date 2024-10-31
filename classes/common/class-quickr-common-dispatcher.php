<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Admin_Dispatcher
 *
 * @author nur
 */
class Quickr_Common_Dispatcher {

    /**
     * 
     * @param Quickr_Initializer $initializer
     */
    public function register_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'register_custom_post_type'));
        add_action('plugins_loaded', array($this, 'plugins_loaded'));
        add_action('wp_loaded', array($this, 'wp_loaded'));
        add_filter('show_admin_bar', array($this, 'show_admin_bar'));
        require_once QUICKR_CLASSES . 'common/class-quickr-member-validator.php';
        $member_validator = new Quickr_Member_Validator();
        add_filter('authenticate', array($member_validator, 'validate'), 30, 3);
        add_action('wp_login_failed', array($member_validator, 'login_failed'));
        add_filter('login_errors', array($member_validator, 'login_errors'));
        add_action('login_form', array($member_validator, 'login_form'));
        add_filter('quickr_log_types', array($this, 'log_types'));
    }
    public function log_types($terms){
        return array(Quickr_Constants::log_level_debug
                , Quickr_Constants::log_level_error
                , Quickr_Constants::log_level_event
                , Quickr_Constants::log_level_fatal
                , Quickr_Constants::log_level_info);
    }
    /**
     * registers shared styelsheets for the plugin
     */
    public function enqueue_styles() {
        wp_enqueue_style(Quickr_Constants::name, QUICKR_URL . '/css/common/common-style.css', array(), "all");
    }

    /**
     * registers shared js files for the plugin
     */
    public function enqueue_scripts() {
        
    }

    public function register_custom_post_type() {
        register_post_type('quickr_member_level', array(
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => false,
            'query_var' => false,
            'rewrite' => false,
            'capability_type' => 'page',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => array('title', 'editor')
        ));
        register_post_type('quickr_pay_button', array(
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => false,
            'query_var' => false,
            'rewrite' => false,
            'capability_type' => 'page',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => array('title', 'editor')
        ));
    }

    public function plugins_loaded() {
        
    }
    public function wp_loaded(){
        //activate_quickr_membership();
    }
    /**
     * 
     * @return boolean
     */
    public function show_admin_bar() {
        //Never show admin toolbar if the user is not even logged in
        if (!is_user_logged_in()) {
            return false;
        }

        //Show admin toolbar to admin only feature is enabled.
        $hide_adminbar = Quickr_Settings::get_instance()->get_value('show-adminbar');
        if ($hide_adminbar == '') {
            return true;
        }
        if ($hide_adminbar == 'both') {
            return false;
        }
        if (is_admin() && current_user_can('administrator')) {
            return $hide_adminbar != 'admin';
        }

        //Hide admin toolbar if the hide adminbar feature is enabled
        return $hide_adminbar != 'front';
    }

}
