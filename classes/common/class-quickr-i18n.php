<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_I18n
 *
 * @author nur
 */
class Quickr_I18n {

    /**
     * Constructor for i18n class that takes text domain name as parameter.
     * @param string $text_domain
     */
    public function __construct() {
    }

    /**
     * registers localization hook 
     * 
     */
    public function register_hooks() {
        add_action('plugins_loaded', array(&$this, 'load_plugin_textdomain'));
    }

    /**
     * loads localization file
     */
    public function load_plugin_textdomain() {        
        $locale = apply_filters('plugin_locale', get_locale(), Quickr_Constants::name);
        load_textdomain(Quickr_Constants::name, QUICKR_PATH . "languages/quickr-membership-$locale.mo");
        load_plugin_textdomain(
                Quickr_Constants::name, false, dirname(dirname(plugin_basename(__FILE__))) . '/languages');
    }

    /**
     * Given a string, returns translated version of the string into
     * currently active locale.
     * 
     * @param string $text
     * @return string
     */
    public static function _($text) {
        return __($text, Quickr_Constants::name);
    }

    /**
     * Given a string, prints translated version of the string into
     * currently active locale.
     * 
     * @param type $text
     */
    public static function e($text) {
        echo self::_($text);
    }

}
