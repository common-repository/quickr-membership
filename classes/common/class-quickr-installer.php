<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Installer_Admin
 *
 * @author nur
 */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class Quickr_Installer {

    /**
     * 
     */
    public static function activate() {
        wp_schedule_event(time(), 'daily', 'quickr_account_status_event');
        wp_schedule_event(time(), 'daily', 'quickr_delete_pending_account_event');
        self::run_installer();
        self::init_settings();
    }

    /**
     * 
     */
    public static function run_installer() {
        //Do this if multi-site setup
        if (function_exists('is_multisite') && is_multisite()) {
            self::run_multisite_installer();
        } else {
            self::run_singlesite_installer();
        }
    }

    public static function init_settings() {
        require_once QUICKR_PATH . 'classes/admin/class-quickr-configurator.php';
        $config = new Quickr_Configurator();
        $config->setup();
    }

    /**
     * 
     * @global type $wpdb
     * @return type
     */
    private static function run_multisite_installer() {
        $networkwide = filter_input(INPUT_GET, 'networkwide');
        if ($networkwide == 1) {
            return;
        }
        global $wpdb;
        // Get all blog ids
        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blogids as $blog_id) {
            switch_to_blog($blog_id);
            self::run_singlesite_installer();
            restore_current_blog();
        }
    }

    /**
     * 
     */
    private static function run_singlesite_installer() {
        //self::setup_membership_schema();
        self::setup_member_membership_rel_schema();
        self::setup_transation_schema();
        self::setup_default_settings();
        self::setup_membership_post_rel_schema();
    }

    /**
     * 
     */
    private static function setup_membership_schema() {
        global $wpdb;
        $charset_collate = self::get_charset_settings();
        $sql = "CREATE TABLE " . $wpdb->prefix . Quickr_Constants::membership . " (
                ID INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(127) NOT NULL,
                role VARCHAR(255) NOT NULL DEFAULT 'subscriber',
                permissions TINYINT(4) UNSIGNED NOT NULL DEFAULT '0',
                duration VARCHAR(11) NOT NULL DEFAULT 'none',
                duration_type enum('unlimited','onetime','recurring','fixed_date') DEFAULT 'onetime',
                duration_unit enum('none',days','months','years') DEFAULT 'days',
                login_redirect_page  VARCHAR(500) NULL,
                protect_older_posts  TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
                campaign_name VARCHAR(255) NOT NULL DEFAULT '',
                PRIMARY KEY (ID)
          ) $charset_collate  AUTO_INCREMENT=2 ;";
        dbDelta($sql);
    }

    /**
     * 
     */
    private static function setup_member_membership_rel_schema() {
        global $wpdb;
        $charset_collate = self::get_charset_settings();
        $sql = "CREATE TABLE " . $wpdb->prefix . Quickr_Constants::membership_rel . " (
                ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                user_ID BIGINT(20) UNSIGNED NOT NULL,
                membership_ID INT(11) NOT NULL, 
                activation_date DATE NOT NULL DEFAULT '0000-00-00',
                expiration_date DATE NOT NULL DEFAULT '0000-00-00',
                is_default_membership TINYINT(2) NOT NULL DEFAULT 0,
                is_current TINYINT(2) NOT NULL DEFAULT 0,
                PRIMARY KEY  (ID),
                UNIQUE KEY user_membership (user_ID,membership_ID),
                UNIQUE KEY user_default (user_ID,is_default_membership),
                UNIQUE KEY user_current (user_ID,is_current)
          ) $charset_collate AUTO_INCREMENT=1 ;";
        dbDelta($sql);
    }

    /**
     * 
     */
    private static function setup_membership_post_rel_schema() {
        global $wpdb;
        $charset_collate = self::get_charset_settings();
        $sql = "CREATE TABLE " . $wpdb->prefix . Quickr_Constants::membership_post_rel . " (
                ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                post_ID BIGINT(20) UNSIGNED NOT NULL,
                membership_ID INT(11) NOT NULL, 
                add_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (ID),
                UNIQUE KEY user_membership (post_ID,membership_ID)
          ) $charset_collate AUTO_INCREMENT=1 ;";
        dbDelta($sql);
    }

    /**
     * 
     * @global type $wpdb
     */
    private static function setup_transation_schema() {
        global $wpdb;
        $charset_collate = self::get_charset_settings();
        $sql = "CREATE TABLE " . $wpdb->prefix . Quickr_Constants::transaction . " (
                ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                user_ID BIGINT(20) UNSIGNED NOT NULL,
                first_name varchar(255) DEFAULT NULL,
                last_name varchar(255) DEFAULT NULL,
                email varchar(255) DEFAULT NULL,
                membership_ID INT(11) NOT NULL DEFAULT 0, 
                txn_date DATE NOT NULL DEFAULT '0000-00-00',
                txn_id VARCHAR(255) NOT NULL DEFAULT '',
                txn_amount DECIMAL(13,4) NOT NULL DEFAULT 0,
                txn_currency VARCHAR(3) NOT NULL DEFAULT 'USD',                        
                txn_subscr_id VARCHAR(255) NOT NULL DEFAULT '',
                txn_reference VARCHAR(255) NOT NULL DEFAULT '',
                txn_response_data  TEXT DEFAULT NULL,
                txn_processor varchar(32) DEFAULT '',
                txn_ip varchar(128) default '',
                txn_status varchar(16) DEFAULT '',
                PRIMARY KEY  (ID)
          ) $charset_collate  AUTO_INCREMENT=1 ;";
        dbDelta($sql);
    }

    /**
     * 
     */
    private static function setup_default_settings() {
        
    }

    /**
     * 
     * @global type $wpdb
     * @return type
     */
    private static function get_charset_settings() {
        global $wpdb;
        $charset = empty($wpdb->charset) ? 'CHARSET=utf8' : $wpdb->charset;
        $charset_collate = 'DEFAULT CHARACTER SET ' . $charset;
        $charset_collate.= empty($wpdb->collate) ? '' : ' COLLATE ' . $wpdb->collate;
        return $charset_collate;
    }

    public static function save_activation_error() {
        $upload_dir = wp_upload_dir();
        $dir = $upload_dir['basedir'] . '/quickrmember/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($dir . 'activation_errors.html', ob_get_contents());
    }

}
