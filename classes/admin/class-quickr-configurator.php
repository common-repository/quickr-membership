<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Configurator
 *
 * @author nur
 */
class Quickr_Configurator {

    private $settings;
    private $active_version;

    public function __construct() {
        $this->settings = Quickr_Settings::get_instance();
        $this->active_version = $this->settings->get_value('active-version');
    }

    public function setup() {
        if (empty($this->active_version)) {
            $this->setup_emails();
            $this->setup_pages();
           $this->setup_db();
        }
        $this->settings->set_value('swpm-active-version', Quickr_Constants::version)->save();
    }
    private function setup_db(){
        global $wpdb;
        $sql = $wpdb->prepare("INSERT IGNORE INTO " . $wpdb->prefix.Quickr_Constants::membership . " 
            (ID, name, role, duration, duration_type, duration_unit) 
            VALUES 
            (%d,%s,%s,%s,%s,%s)",
                1,
                Quickr_I18n::_ ("Free Membership"), 
                'subscriber',
                'none', 
                'unlimited', 
                'none'); 
        //$wpdb->query($sql);
        
    }
    private function setup_emails() {
        $this->settings->set_value('email-misc-from', trim(get_option('admin_email')));
        //Set other default settings values
        $this->reg_prompt();
        $this->reg_complete();
        $this->upgrade_email();
        //$this->password_reset_email();
        $this->status_change_email();
        $this->bulk_activate_email();
    }

    private function setup_pages() {
        $this->setup_sign_up_page();
        $this->setup_login_page();
        $this->setup_reset_password_page();
        $this->setup_profile_page();
        $this->setup_registration_page();
    }

    private function reg_prompt() {
        $reg_prompt_email_subject = "Complete your registration";
        $reg_prompt_email_body = "Dear {user_login}" .
                "\n\nThank you for joining us!" .
                "\n\nPlease complete your registration by visiting the following link:" .
                "\n\n{reg_link}" .
                "\n\nThank You";
        $this->settings->set_value('reg-prompt-complete-mail-subject', stripslashes($reg_prompt_email_subject))
                ->set_value('reg-prompt-complete-mail-body', stripslashes($reg_prompt_email_body));
    }

    private function reg_complete() {
        $reg_email_subject = "Your registration is complete";
        $reg_email_body = "Dear {user_login} \n\n" .
                "Your registration is now complete!\n\n" .
                "Registration details:\n" .
                "Username: {user_login}\n" .
                "Password: [password will be sent in another mail]\n\n" .
                "Please login to the member area at the following URL:\n\n" .
                "{login_link}\n\n" .
                "Thank You";
        $this->settings->set_value('reg-complete-mail-subject', stripslashes($reg_email_subject))
                ->set_value('reg-complete-mail-body', stripslashes($reg_email_body));
    }

    private function upgrade_email() {
        $upgrade_email_subject = "Subject for email sent after account upgrade";
        $upgrade_email_body = "Dear {first_name} {last_name}" .
                "\n\nYour Account Has Been Upgraded." .
                "\n\nThank You";
        $this->settings->set_value('upgrade-complete-mail-subject', stripslashes($upgrade_email_subject))
                ->set_value('upgrade-complete-mail-body', stripslashes($upgrade_email_body));
    }

    private function password_reset_email() {
        $reset_email_subject = get_bloginfo('name') . ": New Password";
        $reset_email_body = "Dear {first_name} {last_name}" .
                "\n\nHere is your new password:" .
                "\n\nUsername: {user_name}" .
                "\nPassword: {password}" .
                "\n\nYou can change the password from the edit profile section of the site (after you log into the site)" .
                "\n\nThank You";
        $this->settings->set_value('reset-mail-subject', stripslashes($reset_email_subject))
                ->set_value('reset-mail-body', stripslashes($reset_email_body));
    }

    private function status_change_email() {
        $status_change_email_subject = "Account Updated!";
        $status_change_email_body = "Dear {first_name} {last_name}," .
                "\n\nYour account status has been updated!" .
                " Please login to the member area at the following URL:" .
                "\n\n {login_link}" .
                "\n\nThank You";
        $this->settings->set_value('account-change-email-subject', stripslashes($status_change_email_subject))
                ->set_value('account-change-email-body', stripslashes($status_change_email_body));
    }

    private function bulk_activate_email() {
        $bulk_activate_email_subject = "Account Activated!";
        $bulk_activate_email_body = "Hi," .
                "\n\nYour account has been activated!" .
                "\n\nYou can now login to the member area." .
                "\n\nThank You";
        $this->settings->set_value('bulk-activate-notify-mail-subject', stripslashes($bulk_activate_email_subject))
                ->set_value('bulk-activate-notify-mail-body', stripslashes($bulk_activate_email_body));
    }

    private function setup_sign_up_page() {
        $content  = "<h4>Free Membership</h4>";
        $content .= "Unlimited access to free content. <a href='?quickr-register=1'>Register </a>";
        $content .= "For paid membership level signup,
                     {insert payment button here}";
        $name = "quickr-signup";
        $title = Quickr_I18n::_ ("Signup");
        
        $sign_up = self::create_or_update_page($name, $title, $content);
        $this->settings->set_value('signup-page-url', $sign_up);        
    }
    

    private function setup_login_page() {
        $content = "[quickr_login_form]";
        $name = "quickr-login";
        $title = Quickr_I18n::_ ("Login");
        
        $permalink = self::create_or_update_page($name, $title, $content);
        $this->settings->set_value('login-page-url', $permalink);                
    }

    private function setup_reset_password_page() {
        $content = "[quickr_password_reset_form]";
        $name = "quickr-reset-password";
        $title = Quickr_I18n::_ ("Reset Password");
        
        $permalink = self::create_or_update_page($name, $title, $content);
        $this->settings->set_value('password-reset-page-url', $permalink);                
    }

    private function setup_profile_page() {
        $content = "[quickr_profile_form]";
        $name = "quickr-profile";
        $title = Quickr_I18n::_ ("Profile");
        
        $permalink = self::create_or_update_page($name, $title, $content);
        $this->settings->set_value('profile-page-url', $permalink);                
    }

    private function setup_registration_page() {
        $content = "[quickr_registration_form]";
        $name = "quickr-registration";
        $title = Quickr_I18n::_ ("Registration");
        
        $permalink = self::create_or_update_page($name, $title, $content);
        $this->settings->set_value('registration-page-url', $permalink);                
    }
    public static function create_or_update_page($name, $title, $content){
        $page_config = array(
            'post_title' => $title,
            'post_name' => $name,
            'post_content' => $content,
            'post_parent' => 0,
            'post_status' => 'publish',
            'post_type' => 'page',
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        );

        $page = get_page_by_path($name); 
        if (empty($page)){
            $page_id = wp_insert_post($page_config);
            return get_permalink($page_id);
        }

        if ($page->post_status == 'trash') { //For cases where page may be in trash, bring it out of trash
            wp_update_post(array('ID' => $page->ID, 'post_status' => 'publish'));
        } 
        
        $permalink = get_permalink($page);
        return $permalink;
    }

}
