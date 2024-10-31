<?php

/**
 * Quickr_Widget_System
 *
 * @author nur85
 */
class Quickr_Widget_System {
    public function __construct() {
        
    }

    public static  function register_widget($widget_class) {
        global $wp_widget_factory;
        $wp_widget_factory->register($widget_class);
    }

}
