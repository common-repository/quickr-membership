<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Qquickr_Payment_Form_Data
 *
 * @author nur858
 */
class Quickr_Paypal_Form_Data extends Quickr_Data {

    public $ID;
    public $title;
    public $billing_amount;
    public $billing_currency;
    public $billing_email;
    public $membership_level;
    public $return_url;
    public $cancel_return_url;
    public $button_image_url;
    public $button_type;
    public $billing_cycle;
    public $billing_cycle_term;
    public $billing_cycle_count;
    public $billing_retry;
    public $billing_trial_amount;
    public $billing_trial_cycle;
    public $billing_trial_cycle_term;

    public function __construct() {
        parent::__construct();
    }

    public function extract() {
        $this->title = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'title'), 'sanitize_text_field', 'esc_html');
        $this->billing_amount = filter_input(INPUT_POST, 'billing_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $this->billing_currency = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'billing_currency'), 'sanitize_text_field', 'esc_html');
        $this->billing_email = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'billing_email'), 'sanitize_text_field', 'esc_html');
        $this->membership_level = absint(filter_input(INPUT_POST, 'membership_level'));
        $this->return_url = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'return_url'), 'sanitize_text_field', 'esc_url');
        $this->cancel_return_url = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'cancel_return_url'), 'sanitize_text_field', 'esc_url');
        $this->button_image_url = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'button_image_url'), 'sanitize_text_field', 'esc_url');
        $this->billing_cycle = absint(filter_input(INPUT_POST, 'billing_cycle'));
        $this->billing_cycle_term = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'billing_cycle_term'), 'sanitize_text_field', 'esc_html');
        $this->billing_cycle_count = absint(filter_input(INPUT_POST, 'billing_cycle_count'));
        $this->billing_retry = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'billing_retry'), 'sanitize_text_field', 'esc_html');
        $this->billing_trial_amount = filter_input(INPUT_POST, 'billing_trial_amount',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $this->billing_trial_cycle = absint(filter_input(INPUT_POST, 'billing_trial_cycle'));
        $this->billing_trial_cycle_term = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'billing_trial_cycle_term'), 'sanitize_text_field', 'esc_html');
        $this->button_type = Quickr_Utils::sanitize_and_scape(filter_input(INPUT_POST, 'button_type'), 'sanitize_text_field', 'esc_html');
    }

    public function insert() {
        $button_id = wp_insert_post(
                array(
            'post_title' => sanitize_text_field($this->title),
            'post_type' => 'quickr_pay_button',
            'post_content' => $this->button_type,
            'post_status' => 'publish'
                ), true
        );
        if (is_wp_error($button_id)) {
            $this->errors['error'] = $button_id->get_error_message();
            return;
        }
        $this->add_update_meta_info($button_id);
    }

    public function is_submitted() {
        $submit = filter_input(INPUT_POST, 'quickr-payment-button-submit');
        return !empty($submit);
    }

    public function load($id) {
        $post = get_post($id);
        if ($post != null) {
            $this->ID = $id;
            $this->title = $post->post_title;
            $this->button_type = $post->post_content;
            $this->billing_amount = get_post_meta($id, Quickr_Constants::billing_amount_metakey, true);
            $this->billing_currency = get_post_meta($id, Quickr_Constants::billing_currency_metakey, true);
            $this->billing_email = get_post_meta($id, Quickr_Constants::billing_email_metakey, true);
            $this->membership_level = get_post_meta($id, Quickr_Constants::billing_membership_level_metakey, true);
            $this->return_url = get_post_meta($id, Quickr_Constants::billing_return_url_metakey, true);
            $this->cancel_return_url = get_post_meta($id, Quickr_Constants::billing_cancel_return_url_metakey, true);
            $this->button_image_url = get_post_meta($id, Quickr_Constants::billing_button_image_url_metakey, true);
            $this->billing_cycle = get_post_meta($id, Quickr_Constants::paypal_billing_cycle_metakey, true);
            $this->billing_cycle_term = get_post_meta($id, Quickr_Constants::paypal_billing_cycle_term_metakey, true);
            $this->billing_cycle_count = get_post_meta($id, Quickr_Constants::paypal_billing_cycle_count_metakey, true);
            $this->billing_retry = get_post_meta($id, Quickr_Constants::paypal_billing_retry_metakey, true);
            $this->billing_trial_amount = get_post_meta($id, Quickr_Constants::paypal_billing_trial_amount_metakey, true);
            $this->billing_trial_cycle = get_post_meta($id, Quickr_Constants::paypal_billing_trial_cycle_metakey, true);
            $this->billing_trial_cycle_term = get_post_meta($id, Quickr_Constants::paypal_billing_trial_cycle_term_metakey, true);
        }
    }

    public function update($id) {
        wp_update_post(
                array(
                    'ID' => $id,
                    'post_title' => $this->title,
                    'post_type' => 'quickr_pay_button',
                    'post_content' => $this->button_type,
                    'post_status' => 'publish'
                )
        );
        $this->add_update_meta_info($id);
    }

    public function validate() {
        if (empty($this->title)) {
            $this->errors['title'] = Quickr_I18n::_("Title is required");
        }
        if (empty($this->button_type)) {
            $this->button_type = "paypal_but_now";
        }
        if (!is_numeric($this->billing_amount)) {
            $this->errors['billing_amount'] = Quickr_I18n::_("Billing Amount is not valid number.");
        }
        if (empty($this->billing_currency)) {
            $this->billing_currency = 'USD';
        }
        if (!is_email($this->billing_email)) {
            $this->errors['billing_email'] = Quickr_I18n::_("Email is not valid.");
        }
        if (!is_numeric($this->membership_level) || $this->membership_level <= 0) {
            $this->errors['membership_level'] = Quickr_I18n::_("Membership level is not valid.");
        }
        if ($this->button_type != 'paypal_subscribe') { return;}
        if (!is_numeric($this->billing_cycle) || $this->billing_cycle < 0) {
            $this->errors['billing_cycle'] = Quickr_I18n::_("Not a valid number");
        }
        if (empty($this->billing_cycle_term)) {
            $this->errors['billing_cycle_term'] = Quickr_I18n::_("Not a valid term");
        }
        if (!is_numeric($this->billing_cycle_count) || $this->billing_cycle_count < 0) {
            $this->errors['billing_cycle_count'] = Quickr_I18n::_("Not a valid number");
        }
        if (empty($this->billing_trial_amount) && empty($this->billing_trial_cycle) && mpty($this->billing_trial_cycle_term)) {return;}

        if (empty($this->billing_trial_amount) || !is_numeric($this->billing_trial_amount)) {
            $this->errors['billing_trial_amount'] = Quickr_I18n::_('Trial amoun cannot be empty and must be a valid number');
        }
        if (empty($this->billing_trial_cycle)) {
            $this->errors['billing_trial_cycle'] = Quickr_I18n::_('Trial cycle cannot be empty.');
        }
        if (empty($this->billing_trial_cycle_term)) {
            $this->errors['billing_trial_cycle_term'] = Quickr_I18n::_('Trial cycle cannot be empty.');
        }            

    }

    public static function get_billing_cycle_term_dropdown($selected = 'D') {
        $output = '';
        $selected = strtoupper($selected);
        $options = array(
            'D' => Quickr_I18n::_('Day(s)'),
            'W' => Quickr_I18n::_('Week(s)'),
            'M' => Quickr_I18n::_('Month(s)'),
            'Y' => Quickr_I18n::_('Year(s)'));
        foreach ($options as $key => $value) {
            $selected_option = ($selected == $key) ? "selected='selected'" : '';
            $option = '<option value="' . $key . '" ' . $selected_option . ' >' . $value . '</option>';
            $output .= $option;
        }
        return $output;
    }

    public static function currency_dropdown($selected = 'USD') {
        $output = '';
        $selected = strtoupper($selected);
        foreach (self::$currencies as $key => $value) {
            $selected_option = ($selected == $key) ? "selected='selected'" : '';
            $option = '<option value="' . $key . '" ' . $selected_option . ' >' . $value . '</option>';
            $output .= $option;
        }
        return $output;
    }

    private function add_update_meta_info($button_id) {
        add_post_meta($button_id, Quickr_Constants::billing_payment_processer_metakey, 'paypal', true);
        if (!add_post_meta($button_id, Quickr_Constants::billing_amount_metakey, $this->billing_amount, true)) {
            update_post_meta($button_id, Quickr_Constants::billing_amount_metakey, $this->billing_amount);
        }
        if (!add_post_meta($button_id, Quickr_Constants::billing_currency_metakey, $this->billing_currency, true)) {
            update_post_meta($button_id, Quickr_Constants::billing_currency_metakey, $this->billing_currency);
        }
        if (!add_post_meta($button_id, Quickr_Constants::billing_email_metakey, sanitize_email($this->billing_email), true)) {
            update_post_meta($button_id, Quickr_Constants::billing_email_metakey, sanitize_email($this->billing_email));
        }
        if (!add_post_meta($button_id, Quickr_Constants::billing_membership_level_metakey, $this->membership_level, true)) {
            update_post_meta($button_id, Quickr_Constants::billing_membership_level_metakey, $this->membership_level);
        }
        if (!add_post_meta($button_id, Quickr_Constants::billing_return_url_metakey, esc_url($this->return_url), true)) {
            update_post_meta($button_id, Quickr_Constants::billing_return_url_metakey, esc_url($this->return_url));
        }
        if (!add_post_meta($button_id, Quickr_Constants::billing_cancel_return_url_metakey, esc_url($this->cancel_return_url), true)) {
            update_post_meta($button_id, Quickr_Constants::billing_cancel_return_url_metakey, esc_url($this->cancel_return_url));
        }
        if (!add_post_meta($button_id, Quickr_Constants::billing_button_image_url_metakey, esc_url($this->button_image_url), true)) {
            update_post_meta($button_id, Quickr_Constants::billing_button_image_url_metakey, esc_url($this->button_image_url));
        }
        if ($this->button_type == 'paypal_subscribe') {return;} 
        
        if (!add_post_meta($button_id, Quickr_Constants::paypal_billing_cycle_metakey, $this->billing_cycle, true)) {
            update_post_meta($button_id, Quickr_Constants::paypal_billing_cycle_metakey, $this->billing_cycle);
        }               
        if (!add_post_meta($button_id, Quickr_Constants::paypal_billing_cycle_term_metakey, $this->billing_cycle_term, true)) {
            update_post_meta($button_id, Quickr_Constants::paypal_billing_cycle_term_metakey, $this->billing_cycle_term);
        }
        if (!add_post_meta($button_id, Quickr_Constants::paypal_billing_cycle_count_metakey, $this->billing_cycle_count, true)) {
            update_post_meta($button_id, Quickr_Constants::paypal_billing_cycle_count_metakey, $this->billing_cycle_count);
        }
        if (!add_post_meta($button_id, Quickr_Constants::paypal_billing_retry_metakey, $this->billing_retry, true)) {
            update_post_meta($button_id, Quickr_Constants::paypal_billing_retry_metakey, $this->billing_retry);
        }
        if (!add_post_meta($button_id, Quickr_Constants::paypal_billing_trial_amount_metakey, $this->billing_trial_amount, true)) {
            update_post_meta($button_id, Quickr_Constants::paypal_billing_trial_amount_metakey, $this->billing_trial_amount);
        }
        if (!add_post_meta($button_id, Quickr_Constants::paypal_billing_trial_cycle_metakey, $this->billing_trial_cycle, true)) {
            update_post_meta($button_id, Quickr_Constants::paypal_billing_trial_cycle_metakey, $this->billing_trial_cycle);
        }
        if (!add_post_meta($button_id, Quickr_Constants::paypal_billing_trial_cycle_term_metakey, $this->billing_trial_cycle_term, true)) {
            update_post_meta($button_id, Quickr_Constants::paypal_billing_trial_cycle_term_metakey, $this->billing_trial_cycle_term);
        }
    }

    public static $currencies = array(
        "USD" => 'US Dollars ($)',
        "EUR" => 'Euros (€)',
        "GBP" => 'Pounds Sterling (£)',
        "AUD" => 'Australian Dollars ($)',
        "BRL" => 'Brazilian Real (R$)',
        "CAD" => 'Canadian Dollars ($)',
        "CNY" => 'Chinese Yuan',
        "CZK" => 'Czech Koruna',
        "DKK" => 'Danish Krone',
        "HKD" => 'Hong Kong Dollar ($)',
        "HUF" => 'Hungarian Forint',
        "INR" => 'Indian Rupee',
        "IDR" => 'Indonesia Rupiah',
        "ILS" => 'Israeli Shekel',
        "JPY" => 'Japanese Yen (¥)',
        "MYR" => 'Malaysian Ringgits',
        "MXN" => 'Mexican Peso ($)',
        "NZD" => 'New Zealand Dollar ($)',
        "NOK" => 'Norwegian Krone',
        "PHP" => 'Philippine Pesos',
        "PLN" => 'Polish Zloty',
        "SGD" => 'Singapore Dollar ($)',
        "ZAR" => 'South African Rand (R)',
        "KRW" => 'South Korean Won',
        "SEK" => 'Swedish Krona',
        "CHF" => 'Swiss Franc',
        "TWD" => 'Taiwan New Dollars',
        "THB" => 'Thai Baht',
        "TRY" => 'Turkish Lira',
        "VND" => 'Vietnamese Dong');

}
