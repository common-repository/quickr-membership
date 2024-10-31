<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Abstract_Menu
 * 
 * @author nur85
 */
abstract class Quickr_Abstract_Menu {
    protected $current_tab;
    public function __construct() {
       //Read the value of tab query arg.
        $tab = Quickr_Utils::get_query_param( 'tab');
        $tab = empty($tab) ? filter_input(INPUT_POST, 'tab') : $tab;
        $this->current_tab = empty($tab) ? $this->default_tab() : $tab;
    }
    public static function get_query_param($param){
        $value = sanitize_text_field(filter_input(INPUT_GET, $param));
        return empty($value) ? sanitize_text_field(filter_input(INPUT_POST, $param)) : $value;
        
    }
    /**
     * 
     */
    public function render(){ 
      //Register the various settings fields for the current tab.
        $method = 'quickr_tab_' . $this->current_tab;
        do_action($method);
 
    }
    /**
     * 
     */
    public abstract function render_tab();
    public abstract function default_tab();
}
