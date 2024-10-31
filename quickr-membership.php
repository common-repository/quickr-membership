<?php
/**
 * Plugin Name: Quickr Membership
 * Version: 1.0.0
 * Plugin URI: https://quickr-membership.com/
 * Author: Quickr
 * Author URI: https://quickr-membership.com/
 * Description: An Extensible wordpress membership plugin
 * Text Domain: quickr-membership
*/

//Direct access to this file is not permitted
if ( ! defined( 'ABSPATH' ) ) {exit;}
define('QUICKR_PATH', plugin_dir_path(__FILE__));
define('QUICKR_URL', plugins_url('', __FILE__));
define('QUICKR_ROOT', plugin_basename(__FILE__));
define('QUICKR_CLASSES', QUICKR_PATH . 'classes/');
define('QUICKR_VIEWS', QUICKR_PATH . 'views/');

include_once(QUICKR_CLASSES . 'common/class-quickr-constants.php' );
include_once(QUICKR_CLASSES . 'common/class-quickr-initializer.php' );
require_once QUICKR_CLASSES . 'common/class-quickr-settings.php';
require_once QUICKR_CLASSES . 'common/class-quickr-i18n.php';
/**
 * plugin activation callback function.
 */
function quickr_activate_membership(){
    include_once (QUICKR_CLASSES . 'common/class-quickr-installer.php');
    Quickr_Installer::activate();
}
/**
 * 
 */
add_action( 'activated_plugin', 'quickr_save_activation_error' );
function quickr_save_activation_error(){
    include_once (QUICKR_CLASSES . 'common/class-quickr-installer.php');
    Quickr_Installer::save_activation_error();
}
/**
 * plugin activation callback function.
 */
function quickr_deativate_membership(){
    include_once (QUICKR_CLASSES . 'common/class-quickr-uninstaller.php');
    Quickr_Uninstaller::deactivate();
}
// register activation/deativation hook
register_activation_hook(__FILE__, 'quickr_activate_membership');
register_deactivation_hook(__FILE__, 'quickr_deativate_membership');

include_once (QUICKR_CLASSES . 'class-quickr-membership.php');
/**
 * initialize the plugin.
 */
function quickr_run_membership(){
    $quickr = new Quickr_Membership();
    $quickr->load();    
}
// execute the plugin.
quickr_run_membership();


