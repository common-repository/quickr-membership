<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Dependencies
 *
 * @author nur
 */
class Quickr_Dependencies {
    
    public static function load_common($class){
        $root = plugin_dir_path(dirname(__FILE__));
        $file = 'class-' . str_replace("_", "-", strtolower($class)) . ".php";
        require_once $root. "common/" .$file;        
    }
    public static function load_admin($class){
        $root = plugin_dir_path(dirname(__FILE__));
        $file = 'class-' . str_replace("_", "-", strtolower($class)) . ".php";
        require_once $root. "admin/" .$file;        
    }
    public static function load_front($class){
        $root = plugin_dir_path(dirname(__FILE__));
        $file = 'class-' . str_replace("_", "-", strtolower($class)) . ".php";
        require_once $root. "front/" .$file;        
    }
}
