<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * QuickrUtils
 *
 * @author nur
 */
class Quickr_Utils {

    public static function update_message($text) {
        ?> 
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
            <p>
                <strong><?php Quickr_I18n::e($text); ?></strong>
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>  
        <?php
    }

    public static function error_message($text) {
        ?>
        <div id="setting-error-settings_updated" class="error settings-error notice is-dismissible"> 
            <p>
                <strong><?php Quickr_I18n::e($text); ?></strong>
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div> 
        <?php
    }

    public static function get_edit_user_link($user_id) {
        $request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        return esc_url(add_query_arg('wp_http_referer', urlencode(wp_unslash($request_uri)), get_edit_user_link($user_id)));
    }

    public static function get_free_membership_level() {
        $enable_free_level = Quickr_Settings::get_instance()->get_value('enable-free-membership');
        $free_level = Quickr_Settings::get_instance()->get_value('free-membership-id');
        if (empty($enable_free_level) || empty($free_level)) {
            return;
        }

        return $free_level;
    }

    public static function is_valid_membership_level($id) {
        if (empty($id)) {
            return false;
        }
        $post = get_post($id);
        if (empty($post)) {
            return false;
        }

        return $post->post_type == 'quickr_member_level';
    }

    public static function get_user_ip_address() {
        $user_ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }
        if (strstr($user_ip, ',')) {
            $ip_values = explode(',', $user_ip);
            $user_ip = $ip_values['0'];
        }
        return apply_filters('quickr_get_user_ip_address', $user_ip);
    }
    public static function calculate_expiration_date($start_date, $expiration_date, $level_id){
        if ((int)$expiration_date > 0) {return $expiration_date;}
        $membership= get_post($level_id);
        if(!empty($membership)){
            $duration = $membership->post_content;
            $duration_type = get_post_meta($level_id, Quickr_Constants::membership_duration_type_metakey, true);
            $expiration_date = self::adjust_expiration($start_date, $duration, $duration_type);
        }
        return $expiration_date;
    }
    public static function adjust_expiration($start_date, $duration, $duration_type ){
        if ($duration_type == 'fixed') {return $duration;}       
        return date('Y-m-d',strtotime('+'.$duration.' days', strtotime($start_date)));
    }
    
     public static function get_query_param($param){
        $value = sanitize_text_field(filter_input(INPUT_GET, $param));
        return empty($value) ? sanitize_text_field(filter_input(INPUT_POST, $param)) : $value;        
    }  
    public static function sanitize_and_scape($value, $sanitize, $scape){
        return $scape($sanitize($value));
    }
}
