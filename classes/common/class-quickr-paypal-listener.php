<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Paypal_Listener
 *
 * @author nur858
 */
class Quickr_Paypal_Listener {

    private $timeout;
    private $server;
    private $ssl_verify;
    private $sandbox_mode;

    private $enable_log;
    private $ipn_data;
    private $custom_data;

    public function __construct() {
        $this->timeout = 60;
        $this->ssl_verify = apply_filters('http_local_ssl_verify', false);
        $this->sandbox_mode = Quickr_Settings::get_instance()->get_value('enable-paypal-sanbox-mode');
        $this->server = $this->sandbox_mode ? Quickr_Constants::SANDBOX_SERVER : Quickr_Constants::REAL_SERVER;
        $this->enable_log = Quickr_Settings::get_instance()->get_value('enable-logging');
        $this->ipn_data = array();
    }

    public function register_hooks() {
        add_action('init', array($this, 'process_payment'));
    }

    public function process_payment() {
        if ($this->is_not_ipn_call()) {
            return;
        }
        Quickr_Logger::info('PayPal IPN', '------------------process_payment called----------------');
        if (!$this->validate()) {
            Quickr_Logger::error('PayPal IPN : process_payment', 'ipn data is not valid');
            return;
        }
        $this->extract_custom_data();
        $this->extract_ipn_data();

        $status = strtolower($this->get('payment_status'));
        Quickr_Logger::info('PayPal IPN : process_payment', 'payment status: ' . $status);
        switch ($status) {
            case 'denied':
                break;
            case 'canceled_reversal':
                break;
            case 'completed':
            case 'processed':
            case 'reversed':
            case 'refunded':
                $this->handle_transaction();
                break;
            default:
                // transaction pending
                break;
        }
        $this->save_transaction_data();
    }

    public function handle_transaction() {
        Quickr_Logger::info('PayPal IPN : handle_transaction', 'handle_transaction method called:', array($this->get('mc_gross'), $this->get('reason_code'), $this->get('txn_type')));
        $gross_total = $this->get('mc_gross');
        if ($gross_total < 0) {
            $this->handle_cancel_signup();
            return;
        }
        $reason_code = strtolower($this->get('reason_code'));
        if ($reason_code === 'refund') {
            $this->handle_cancel_signup();
            return;
        }
        $txn_type = strtolower($this->get('txn_type'));
        switch ($txn_type) {
            case 'new_case':
                return;
            case 'subscr_signup':
            case 'web_accept':
                $this->handle_signup();
                return;
            case 'subscr_payment':
                // renewal
                return;
            case 'subscr_cancel':
            case 'subscr_eot':
            case 'subscr_failed':
                $this->handle_cancel_signup();
                return;
        }
    }

    private function validate() {
        Quickr_Logger::info('PayPal IPN', 'Validate: ipn validation started.');
        $ipn_data = array('cmd' => '_notify-validate');
        $ipn_data += wp_unslash($_POST);
        $request_params = array(
            'httpversion' => '1.1',
            'timeout' => $this->timeout,
            'compress' => false,
            'decompress' => false,
            'body' => $ipn_data,
            'user-agent' => 'Quickr Membership Plugin'
        );
        $response = wp_safe_remote_post($this->server, $request_params);
        Quickr_Logger::info('PayPal IPN', 'verification data:', $response);
        Quickr_Logger::info('PayPal IPN', 'verification url : ' . $this->server);
        Quickr_Logger::info('PayPal IPN', 'post data : ', $request_params);
        $body = wp_remote_retrieve_body($response);
        
        if (stristr($body, 'VERIFIED')) {
            Quickr_Logger::info('PayPal IPN', 'Validate: validation succeeded.');
            return true;
        }
        return false;
    }

    private function handle_signup() {
        Quickr_Logger::error('PayPal IPN', '------------------handle_signup called----------------');
        $user_id = $this->get('user_id');
        $membership_level = $this->get('user_level');
        if (!Quickr_Utils::is_valid_membership_level($membership_level)) {
            Quickr_Logger::error('PayPal IPN : process_payment', 'valid membership level not found: ' . $this->get('user_level'), $_POST);
            return;
        }
        $email = $this->get('payer_email');
        if (empty($user_id)) {
            $user = get_user_by('email', $email);
            if (!empty($user)) {
                $user_id = $user->ID;
            }
        }
        $subscr_id = $this->get('subscr_id');
        if (empty($user_id) & !empty($subscr_id)) {
            global $wpdb;
            $query = $wpdb->prepare('SELECT user_id FROM ' . $wpdb->prefix . Quickr_Constants::transaction
                    . ' where txn_subscr_id=%d', $subscr_id);
            $user_id = $wpdb->get_var($query);
        }
        empty($user_id) ? $this->handle_new_account() : $this->handle_account_upgrade($user_id);
        Quickr_Logger::Info('PayPal IPN', 'found user ID :' . $user_id . ', subscr_id:' . $subscr_id );
    }

    private function handle_new_account() {        
        $email = $this->get('payer_email');        
        $uid = uniqid('quickr_');
        Quickr_Logger::error('PayPal IPN', 'handle_new_account: creating new account for:' . $email . ' with temporary user name: ' . $uid);
        $reg_code = hash('sha256', $uid);
        $role = get_post_meta($this->get('user_level'), Quickr_Constants::membership_role_metakey, true);
        $user_id = wp_insert_user(array('user_login' => $uid, 'user_email' => $email, 'role'=>$role));
        Quickr_Logger::info('PayPal IPN', 'handle_new_account :Creating new user with email:' . $email );
        if (!is_wp_error($user_id)) {
            require_once QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php';
            $member = new Quickr_Admin_Member_Form_Data();
            $member->account_status = Quickr_Constants::member_status_incomplete;
            $member->reg_code = $reg_code;
            $member->activation_date = date('Y-m-d');
            $member->membership_ID = $this->get('user_level');    
            $member->expiration_date = Quickr_Utils::calculate_expiration_date($member->activation_date, '0000-00-00', $member->membership_ID);
            $member->update($user_id);
            $this->ipn_data['user_id'] = $user_id;
            Quickr_Emailer::send_registration_complete_prompt_email($user_id);
        }
        else{
            Quickr_Logger::error('PayPal IPN', 'User creation failed for email:'. $email, array('error'=>$user_id->ger_error_message())   );
        }
    }

    private function handle_account_upgrade($user_id) {
        Quickr_Logger::error('PayPal IPN', 'handle_account_upgrade :Updating User :' . $user_id );        
        require_once QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php';
        $member = new Quickr_Admin_Member_Form_Data();
        $member->load($user_id);
        Quickr_Logger::error('PayPal IPN', 'handle_account_upgrade: upgrading account for user ID:' . $user_id );        
        if ($member->account_status == Quickr_Constants::member_status_incomplete){
            Quickr_Logger::error('PayPal IPN', 'Error! Cannot upgrade incomplate account ');
            return;
        }
        $member->account_status = Quickr_Constants::member_status_active;
        $member->activation_date = date('Y-m-d');
        $member->membership_ID = $this->get('user_level');        
        $member->expiration_date = Quickr_Utils::calculate_expiration_date($member->activation_date, '0000-00-00', $member->membership_ID);
        $member->update($user_id);
        $this->ipn_data['user_id'] = $user_id;
        Quickr_Emailer::send_account_upgrade_email($user_id);
    }

    private function handle_cancel_signup() {
        // account should be expired
        $subscr_id = $this->get('parent_txn_id');
        if (empty($subscr_id)) {
            $subscr_id = $this->get('subscr_id');
        }
        if (empty($subscr_id)) {
            return;
        }
        global $wpdb;
        $query = $wpdb->prepare('SELECT user_id FROM ' . $wpdb->prefix . Quickr_Constants::transaction
                . ' where txn_subscr_id=%d', $subscr_id);
        $user_id = $wpdb->get_var($query);
        require_once QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php';
        $member = new Quickr_Admin_Member_Form_Data();
        Quickr_Logger::info('PayPal IPN', 'handle_cancel_signup :Canceling account for :' . $user_id );        
        $member->load($user_id);
        $member->account_status = Quickr_Constants::member_status_inactive;
        $member->update($user_id);
        // find member id/ email from transaction table.
        // set account status to inactive/cancelled.
        Quickr_Emailer::send_account_cancellation_email($user_id);
    }

    private function is_not_ipn_call() {
        $ipn = absint(filter_input(INPUT_GET, 'quickr_ipn_call'));
        return empty($ipn);
    }

    private function extract_ipn_data() {
        foreach ($_POST as $key => $value) {
            $this->ipn_data[$key] = sanitize_text_field($value);
        }

        if (empty($this->ipn_data)) {
            Quickr_Logger::error('PayPal IPN', 'IPN data missing');
        } else {
            Quickr_Logger::info('PayPal IPN', 'IPN data received:', $this->ipn_data);
        }
    }

    private function extract_custom_data() {
        $custom = filter_input(INPUT_POST, 'custom');
        $custom_data = null; 
        parse_str($custom, $custom_data);
        $digest = isset($custom_data['digest'])? $custom_data['digest'] : 'invalid';
        unset($custom_data['digest']);
        $custom_str = http_build_query($custom_data) . SECURE_AUTH_SALT;
        if ($digest == md5($custom_str)){
            $this->custom_data= array_map('sanitize_text_field', $custom_data);
            Quickr_Logger::error('PayPal IPN', 'parsed', $custom_data);
        }
        else{
            Quickr_Logger::error('PayPal IPN', 'custom variable looks tempered. Ingoring', $custom_data);
        }
    }

    private function save_transaction_data() {
        global $wpdb;
        $data = array(
            'email' => $this->get('payer_email'),
            'membership_ID' => $this->get('user_level'),
            'txn_subscr_id' => $this->get('subscr_id'),
            'txn_amount' => $this->get('mc_gross'),
            'txn_currency' => $this->get('mc_currency'),
            'user_ID' => $this->get('user_id'),
            'txn_id' => $this->get('txn_id'),
            'txn_date' => date('Y-m-d'),
            'txn_response_data' => json_encode($this->ipn_data),
            'txn_processor' => 'paypal',
            'txn_status' => $this->get('payment_status')
        );
        $wpdb->insert($wpdb->prefix . Quickr_Constants::transaction, $data);
        Quickr_Logger::info("Paypal IPN", 'last query' . $wpdb->last_query);
    }

    public function get($key) {
        if (isset($this->ipn_data[$key])) {
            return $this->ipn_data[$key];
        }
        return isset($this->custom_data[$key]) ? $this->custom_data[$key] : '';
    }

}
