<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Initializer
 *
 * @author nur
 */
class Quickr_Initializer {
    private $filters;
    private $actions;
    private $shortcodes;
    public function __construct() {
        $this->filters = array();
        $this->actions = array();
        $this->shortcodes = array();
    }
    public function add_shortcode($code, $context, $callback){
        $this->shortcodes[] = $this->build_hook($code, $context, $callback, 10, 1);
    }
    /**
     * 
     * @param type $hook
     * @param type $context
     * @param type $callback
     * @param type $priority
     * @param type $accepted_args
     */
    public function add_filter($hook, $context, $callback, $priority= 10, $accepted_args = 1){
        $this->filters[] = $this->build_hook($hook, $context, $callback, $priority, $accepted_args);
    }
    /**
     * 
     * @param type $hook
     * @param type $context
     * @param type $callback
     * @param type $priority
     * @param type $accepted_args
     */
    public function add_action($hook, $context, $callback, $priority = 10, $accepted_args = 1){
        $this->actions[] = $this->build_hook($hook, $context, $callback, $priority, $accepted_args);
    }
    /**
     * 
     * @param type $hook
     * @param type $context
     * @param type $callback
     * @param type $priority
     * @param type $accepted_args
     * @return \stdClass
     */
    private function build_hook($hook, $context, $callback, $priority, $accepted_args){
        $std = new stdClass();
        $std->hook = $hook;
        $std->context = $context;
        $std->callback = $callback;
        $std->priority = $priority;
        $std->accepted_args = $accepted_args;
        return $std;
    }
    /**
     * 
     * @param type $class
     */
    public function load_admin($class){
        $this->load($class, 'admin');
    }
    /**
     * 
     * @param type $class
     */
    public function load_front($class){
        $this->load($class, 'front');
    }
    /**
     * 
     * @param type $class
     */
    public function load_common($class){
        $this->load($class, 'common');
    }
    private function load($class, $dir){
        $root = plugin_dir_path(dirname(__FILE__));
        $file = 'class-' . str_replace("_", "-", strtolower($class)) . ".php";
        require_once $root. $dir . "/" .$file;        
    }
    /**
     * 
     */
    public function run(){
        foreach($this->filters as $filter){
            add_filter($filter->hook, array($filter->context, $filter->callback), $filter->priority, $filter->accepted_args);
        }
        foreach($this->actions as $action){
            add_action($action->hook, array($action->context, $action->callback), $action->priority, $action->accepted_args);
        }        
        foreach ($this->shortcodes as $shortcode){
            add_shortcode($shortcode->hook, array($shortcode->context, $shortcode->callback));
        }
    }
}
