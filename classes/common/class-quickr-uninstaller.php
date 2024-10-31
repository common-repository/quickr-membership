<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Uninstaller
 *
 * @author nur
 */
class Quickr_Uninstaller {
    /**
     * 
     */
    public static function deactivate(){
        //todo: delete tables if settings says so
        wp_clear_scheduled_hook('quickr_account_status_event');
        wp_clear_scheduled_hook('quickr_delete_pending_account_event');        
    }
}
