<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Paypal_Tab
 *
 * @author nur858
 */
require_once QUICKR_CLASSES . 'data/class-quickr-paypal-form-data.php';

class Quickr_Paypal_Tab {

    private $payment_data;

    public function __construct() {
        $this->payment_data = new Quickr_Paypal_Form_Data();
    }

    public function init() {
        if ($this->payment_data->is_submitted()) {
            $this->payment_data->extract();
            $this->payment_data->validate();
            $button_id = absint(filter_input(INPUT_GET, 'button_id'));
            if (!$this->payment_data->has_error()) {
                empty($button_id) ? $this->payment_data->insert() : $this->payment_data->update($button_id);
            }
        }
    }

    public function notices() {
        if (!$this->payment_data->is_submitted()) {
            return;
        }
        if (!$this->payment_data->has_error()) {
            echo Quickr_Utils::update_message('Updated Successfully');
        }
    }

    public function render_content() {

        $step = absint(filter_input(INPUT_GET, 'step'));
        if (empty($step)) {
            include (QUICKR_VIEWS . 'admin/payments/paypal_payment.php');
            return;
        }
        $button_id = absint(filter_input(INPUT_GET, 'button_id'));
        empty($button_id) ? $this->add() : $this->edit($button_id);
    }

    private function add() {
        if ($this->payment_data->is_submitted()) {
            $this->payment_data->extract();
        }
        $button_type = sanitize_text_field(filter_input(INPUT_GET, 'button_type'));
        if ($button_type == 'paypal_buy_now') {
            include (QUICKR_VIEWS . 'admin/payments/paypal_buy_now_button.php');
            return;
        }
        if ($button_type == 'paypal_subscribe') {
            include (QUICKR_VIEWS . 'admin/payments/paypal_subscription_button.php');
            return;
        }
        wp_die('Invalid operation');
    }

    private function edit($button_id) {
        $this->payment_data->is_submitted() ? $this->payment_data->extract() : $this->payment_data->load($button_id);
        $button_type = sanitize_text_field(filter_input(INPUT_GET, 'button_type'));
        if ($button_type == 'paypal_buy_now') {
            include (QUICKR_VIEWS . 'admin/payments/paypal_buy_now_button.php');
            return;
        }
        if ($button_type == 'paypal_subscribe') {
            include (QUICKR_VIEWS . 'admin/payments/paypal_subscription_button.php');
            return;
        }
        wp_die('Invalid operation');
    }

}
