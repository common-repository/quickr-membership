<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Settings
 * 
 * Singleton helper class to access quickr settings conveniently.
 *
 * @author nur858
 */
class Quickr_Settings {
    private static $_this;
    private $settings;
    private function __construct() {
        $this->settings = get_option('quickr-settings');
    }
    /**
     * returns singleton instance of Quickr_Settings
     * 
     * @return Quickr_Settings
     */
    public static function get_instance() {
        self::$_this = empty(self::$_this) ? new Quickr_Settings() : self::$_this;
        return self::$_this;
    } 
    /**
     *  returns value for given key if exists otherwise returns default.
     * 
     * @param mixed $key   key to retrieve 
     * @param mixed $default value to return if key doesn't exist.
     * 
     * @return mixed 
     */
    public function get_value($key, $default = "") {
        if (isset($this->settings[$key])) {
            return $this->settings[$key];
        }
        return $default;
    }
    /**
     * stores given value against given key in the options.
     * 
     * @param string $key  key to store
     * @param mixed $value value for the key
     * @return \Quickr_Settings [for chaining convenience]
     */
    public function set_value($key, $value) {
        $this->settings[$key] = $value;
        return $this;
    }
    /**
     * saves settings to db
     */
    public function save() {
        update_option('quickr-settings', $this->settings);
    }    
}
