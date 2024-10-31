<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Logger
 *
 * @author nur85
 */
class Quickr_Logger {
    public static function log($title = '', $message = '', $type = null, $log_meta = array(),$parent = 0) {
        $log_enabled = Quickr_Settings::get_instance()->get_value('enable-logging');
        if (!$log_enabled) {
            return;
        }
        Quickr_Logging::add($title, $message, $parent, $type, $log_meta);
    }
    public  static function info($title = '', $message = '', $log_meta = array(),$parent = 0){
        self::log($title, $message, Quickr_Constants::log_level_info, $log_meta, $parent);
    }
    public  static function error($title = '', $message = '', $log_meta = array(),$parent = 0){
        self::log($title, $message, Quickr_Constants::log_level_error, $log_meta, $parent);
    }
    public  static function event($title = '', $message = '', $log_meta = array(),$parent = 0){
        self::log($title, $message, Quickr_Constants::log_level_event, $log_meta, $parent);
    }
    public  static function debug($title = '', $message = '',$log_meta = array(),$parent = 0){
        self::log($title, $message, Quickr_Constants::log_level_debug, $log_meta, $parent);
    }
    public  static function fatal($title = '', $message = '',$log_meta = array(),$parent = 0 ){
        self::log($title, $message, Quickr_Constants::log_level_fatal, $log_meta, $parent);
    }
}
