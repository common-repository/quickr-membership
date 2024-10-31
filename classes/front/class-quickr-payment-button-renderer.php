<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Payment_Button_Renderer
 *
 * @author nur85
 */
class Quickr_Payment_Button_Renderer {
    public function __construct() {
        add_filter('quickr_pay_render_paypal_buy_now', array($this, 'render_paypal_buy_now_button'),10,3);
        add_filter('quickr_pay_render_paypal_subscribe', array($this, 'render_paypal_subscription_button'),10,3);        
    }
    
    public function render_paypal_subscription_button($output, $id, $args){
        require_once (QUICKR_CLASSES . 'data/class-quickr-paypal-form-data.php');
        $payment_data = new Quickr_Paypal_Form_Data();
        $payment_data->load($id);
        $sandbox_mode = Quickr_Settings::get_instance()->get_value('enable-paypal-sanbox-mode');
        $server = $sandbox_mode ? Quickr_Constants::SANDBOX_SERVER : Quickr_Constants::REAL_SERVER;
        $button_text = (isset($args['button_text'])) ? $args['button_text'] : Quickr_I18n::_('Subscribe Now');
        $business= $payment_data->billing_email;
        $amount = $payment_data->billing_amount;
        $currency_code = $payment_data->billing_currency;
        $item_number = $id;
        $item_name = htmlspecialchars($payment_data->title);
        $notify_url = home_url() . '/?quickr_ipn_call=1';                
        $return = $payment_data->return_url;
        $cancel_return = $payment_data->cancel_return_url;
        $window_target = isset($args['new_window']) ? 'target="_blank"' : '';
        $button_image_url = $payment_data->button_image_url;
        $a1 = $payment_data->billing_trial_amount;
        $p1 = $payment_data->billing_trial_cycle;
        $t1 = $payment_data->billing_trial_cycle_term;
        
        $a3 = $payment_data->billing_amount;
        $p3 = $payment_data->billing_cycle;
        $t3 = $payment_data->billing_cycle_term;
        
        $srt = $payment_data->billing_cycle_count;
        $sra = $payment_data->billing_retry;
        //Custom field data
        $custom_data = array();
        $custom_data['user_level']= $payment_data->membership_level;
        $custom_data['user_ip'] = Quickr_Utils::get_user_ip_address();
        if (is_user_logged_in()) {
            $custom_data ['user_id'] = get_current_user_id();
        }        
        
        $custom = apply_filters('quickr_paypal_custom_vars', http_build_query($custom_data));
        $custom .= '&digest=' . md5($custom . SECURE_AUTH_SALT);
        ob_start();
        require (QUICKR_VIEWS . 'front/paypal_subscription_button.php');
        $output = ob_get_clean();
        return $output;
        
    }
    
    public function render_paypal_buy_now_button($output, $id, $args){
        require_once (QUICKR_CLASSES . 'data/class-quickr-paypal-form-data.php');
        $payment_data = new Quickr_Paypal_Form_Data();
        $payment_data->load($id);
        $sandbox_mode = Quickr_Settings::get_instance()->get_value('enable-paypal-sanbox-mode');
        $server = $sandbox_mode ? Quickr_Constants::SANDBOX_SERVER : Quickr_Constants::REAL_SERVER;
        $button_text = (isset($args['button_text'])) ? $args['button_text'] : Quickr_I18n::_('Buy Now');
        $business= $payment_data->billing_email;
        $amount = $payment_data->billing_amount;
        $currency_code = $payment_data->billing_currency;
        $item_number = $id;
        $item_name = htmlspecialchars($payment_data->title);
        $notify_url = home_url() . '/?quickr_ipn_call=1';                
        $return = $payment_data->return_url;
        $cancel_return = $payment_data->cancel_return_url;
        $window_target = isset($args['new_window']) ? 'target="_blank"' : '';
        $button_image_url = $payment_data->button_image_url;
        //Custom field data
        $custom_data = array();
        $custom_data['user_level']= $payment_data->membership_level;
        $custom_data['user_ip'] = Quickr_Utils::get_user_ip_address();
        if (is_user_logged_in()) {
            $custom_data ['user_id'] = get_current_user_id();
        }        
        
        $custom = apply_filters('quickr_paypal_custom_vars', http_build_query($custom_data));
        $custom .= '&digest=' . md5($custom . SECURE_AUTH_SALT);
        ob_start();
        require (QUICKR_VIEWS . 'front/paypal_pay_now_button.php');
        $output = ob_get_clean();
        return $output;
    }
}
