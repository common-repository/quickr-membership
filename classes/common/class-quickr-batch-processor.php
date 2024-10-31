<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Batch_Processor
 *
 * @author nur
 */
class Quickr_Batch_Processor {
    /**
     * 
     */
    public function __construct() {

    }
    /**
     * 
     * @param Quickr_Initializer $initializer
     */
    public function register_hooks(){
        add_action('quickr_account_status_event', array($this, 'update_account_status'));
        add_action('quickr_delete_pending_account_event',array($this, 'delete_pending_account'));                
    }
    /**
     * 
     */
    public function update_account_status(){
        
    }
    /**
     * 
     */
    public function delete_pending_account(){
        
    }
}
