<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Data
 *
 * @author nur
 */
abstract class Quickr_Data {
    public $errors;
    protected  function __construct() {
        $this->errors = array();
    }
    /**
     * 
     */
    abstract public function validate();
    /**
     * 
     */
    abstract public function load($id);
    /**
     * 
     */
    abstract public function extract();
    /**
     * 
     */
    abstract public function is_submitted();
    /**
     * 
     */
    abstract public function insert();
    abstract public function update($id);
    public function get_errors_as_string(){
        return implode('<br/>', $this->errors);
    }
    public function save(){
     }
    /**
     * 
     * @return bool
     */
    public function has_error(){
        return count($this->errors) > 0;
    }
}
