<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Quickr_Emailer
 *
 * @author nur85
 */
class Quickr_Emailer {

    public static function send_active_account_notification($user_id) {
        $subject = Quickr_Settings::get_instance()->get_value('bulk-activate-notify-mail-subject');
        $body = Quickr_Settings::get_instance()->get_value('bulk-activate-notify-mail-body');

        $user_data = self::get_user_terms_and_replacements($user_id);

        self::send_mail($user_id, $user_data, $subject, $body);
    }

    public static function send_registration_email($user_id) {
        $subject = Quickr_Settings::get_instance()->get_value('reg-complete-mail-subject');
        $body = Quickr_Settings::get_instance()->get_value('reg-complete-mail-body');
        $login_link = Quickr_Settings::get_instance()->get_value('login-page-url');

        $user_data = self::get_user_terms_and_replacements($user_id);
        $user_data['{login_link}'] = $login_link;
        self::send_mail($user_id, $user_data, $subject, $body);
    }

    public static function send_registration_complete_prompt_email($user_id) {
        Quickr_Logger::info('PayPal IPN', 'send_registration_complete_prompt_email: sending registration completion notice.');
        $subject = Quickr_Settings::get_instance()->get_value('reg-prompt-complete-mail-subject');
        $body = Quickr_Settings::get_instance()->get_value('reg-prompt-complete-mail-body');
        $reg_link = Quickr_Settings::get_instance()->get_value('registration-page-url');

        $user_data = self::get_user_terms_and_replacements($user_id);
        $reg_code = get_user_meta($user_id, Quickr_Constants::member_reg_code_metakey, true);
        $user_data['{reg_link}'] = add_query_arg('quickr_reg_code', $reg_code, $reg_link);

        self::send_mail($user_id, $user_data, $subject, $body);
    }

    public static function send_account_upgrade_email($user_id) {
        $subject = Quickr_Settings::get_instance()->get_value('upgrade-complete-mail-subject');
        $body = Quickr_Settings::get_instance()->get_value('upgrade-complete-mail-body');

        $user_data = self::get_user_terms_and_replacements($user_id);

        self::send_mail($user_id, $user_data, $subject, $body);
    }

    public static function send_account_cancellation_email($user_id) {
        
    }

    private function send_mail($user_id, $user_data, $subject, $body) {
        $from = Quickr_Settings::get_instance()->get_value('email-misc-from');
        $content = str_replace(array_keys($user_data), array_values($user_data), $body);
        Quickr_Logger::info('PayPal IPN', 'send_mail: sending email', array('content' => $content));
        $headers = 'From: ' . $from . "\r\n";
        $user = get_user_by('id', $user_id);
        $email = sanitize_email($user->user_email);
        wp_mail(trim($email), $subject, $content, $headers);
    }

    private static function get_user_terms_and_replacements($user_id) {
        $user = get_user_by('id', $user_id);
        if ($user) {
            $result = array();
            foreach ($user->data as $key => $value) {
                $result['{' . $key . '}'] = $value;
            }
            $user_meta = get_user_meta($user_id);

            foreach ($user_meta as $key => $value) {
                $result['{' . $key . '}'] = $value[0];
            }
            Quickr_Logger::info('PayPal IPN', 'get_user_terms_and_replacements: processing terms replacement', array('user' => $result));
            return $result;
        }
        return array();
    }
}
