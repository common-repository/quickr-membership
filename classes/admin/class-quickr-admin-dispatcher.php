<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Admin_Dispatcher
 *
 * @author nur
 */
class Quickr_Admin_Dispatcher {

    /**
     * 
     * @param Quickr_Initializer $initializer
     */
    public function register_hooks() {
        require_once QUICKR_CLASSES . 'admin/class-quickr-admin-profile.php';
        require_once QUICKR_CLASSES . 'admin/class-quickr-admin-metabox.php';

        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('plugin_action_links_' . QUICKR_ROOT, array($this, 'add_settings_link'));
        add_action('admin_menu', array($this, 'menu'));
        add_filter('manage_users_columns', array($this, 'inject_extra_user_col'));
        add_filter('manage_users_custom_column', array($this, 'inject_extra_user_col_value'), 10, 3);
        add_action('admin_notices', array($this, 'admin_notices'));
        $this->register_admin_profile_hooks();
        $this->register_metabox_hooks();
        $this->register_members_menu_hooks();
        $this->register_membership_menu_hooks();
        $this->register_payments_menu_hooks();
        $this->register_extensions_menu_hooks();
        $this->register_settings_menu_hooks();
    }

    public function admin_notices() {
        $settings = Quickr_Settings::get_instance();
        $membership_count = wp_count_posts('quickr_member_level');
        if ($membership_count->publish <= 0) {
            $url = admin_url('admin.php?page=quickr_membership_level&tab=membership_add');
            Quickr_Utils::error_message('You haven\'t defined membership level yet. '
                    . 'Quickr Membership needs at least one membership level to function. '
                    . 'Please define it <a href="' . $url . '" >here</a>');
        }
        $enable_free = $settings->get_value('enable-free-membership');
        $free_id = $settings->get_value('free-membership-id');
        if (!empty($enable_free) && empty($free_id)) {
            Quickr_Utils::error_message('Looks like you have enabled free membership level feature but haven\'t '
                    . 'defined free membership level ID. Please set it up on setting page.');
        }
    }

    public function register_metabox_hooks() {
        $meta_box = new Quickr_Admin_Metabox();
        add_action('load-post.php', array($meta_box, 'init_metabox'));
        add_action('load-post-new.php', array($meta_box, 'init_metabox'));
    }

    public function register_admin_profile_hooks() {
        $member = new Quickr_Admin_profile();
        add_action('show_user_profile', array($member, 'show_user_profile'));
        add_action('edit_user_profile', array($member, 'show_user_profile'));
        add_action('personal_options_update', array($member, 'save_user_profile'));
        add_action('edit_user_profile_update', array($member, 'save_user_profile'));
        add_action('user_register', array($member, 'register_user'));
        add_action('user_new_form', array($member, 'add_new_user'));
        add_filter('user_profile_update_errors', array($member, 'validate_fields'), 10, 3);
        add_action('admin_init', array($this, 'initialize_settings'));
    }

    public function initialize_settings() {
        //Read the value of tab query arg.
        $tab = sanitize_text_field(filter_input(INPUT_GET, 'tab'));
        $tab = empty($tab) ? sanitize_text_field(filter_input(INPUT_POST, 'tab')) : $tab;
        $tab = empty($tab) ? 'settings_general' : $tab;
        do_action('quickr_settings_' . $tab . '_init');
    }

    public function register_settings_menu_hooks() {
        require_once QUICKR_PATH . 'classes/admin/settings-tab/class-quickr-general-settings-tab.php';
        $general_settings_tab = new Quickr_General_Settings_Tab();
        add_action('quickr_settings_settings_general_init', array($general_settings_tab, 'init'));
        add_action('admin_notices', array($general_settings_tab, 'notices'));
        add_action('quickr_settings_tab_settings_general', array($general_settings_tab, 'render_content'));

        require_once QUICKR_PATH . 'classes/admin/settings-tab/class-quickr-email-settings-tab.php';
        $email_settings_tab = new Quickr_Email_Settings_Tab();
        add_action('quickr_settings_settings_email_init', array($email_settings_tab, 'init'));
        add_action('admin_notices', array($email_settings_tab, 'notices'));
        add_action('quickr_settings_tab_settings_general', array($email_settings_tab, 'render_content'));

        require_once QUICKR_PATH . 'classes/admin/settings-tab/class-quickr-advanced-settings-tab.php';
        $advanced_settings_tab = new Quickr_Advanced_Settings_Tab();
        add_action('quickr_settings_settings_advanced_init', array($advanced_settings_tab, 'init'));
        add_action('admin_notices', array($advanced_settings_tab, 'notices'));
        add_action('quickr_settings_tab_settings_advanced', array($advanced_settings_tab, 'render_content'));
        require_once QUICKR_PATH . 'classes/admin/settings-tab/class-quickr-payment-settings-tab.php';
        $payment_settings_tab = new Quickr_Payment_Settings_Tab();
        add_action('quickr_settings_settings_payment_init', array($payment_settings_tab, 'init'));
        add_action('admin_notices', array($payment_settings_tab, 'notices'));
        add_action('quickr_settings_tab_settings_payment', array($payment_settings_tab, 'render_content'));

        //quickr_settings_settings_payment_init
        //quickr_settings_settings_tools_init
        //quickr_settings_settings_advanced_init
        //quickr_settings_settings_extension_init
    }

    public function register_members_menu_hooks() {
        require_once QUICKR_PATH . 'classes/admin/members-tab/class-quickr-member-import-tab.php';
        $import_tab = new Quickr_Member_Import_Tab();
        add_action('quickr_members_tab_members_import', array($import_tab, 'render_content'));
        require_once QUICKR_PATH . 'classes/admin/members-tab/class-quickr-member-approve-tab.php';
        $approve_tab = new Quickr_Member_Approve_Tab();
        add_action('quickr_members_tab_members_approve', array($approve_tab, 'render_content'));
        add_action('init', array($approve_tab, 'init'));
        add_action('admin_notices', array($approve_tab, 'notices'));
    }

    public function register_membership_menu_hooks() {
        require_once QUICKR_PATH . 'classes/admin/memberships-tab/class-quickr-membership-list-tab.php';
        $list_tab = new Quickr_Membership_List_Tab();
        add_action('init', array($list_tab, 'init'));
        add_action('quickr_memberships_tab_membership_list', array($list_tab, 'render_content'));

        require_once QUICKR_PATH . 'classes/admin/memberships-tab/class-quickr-membership-add-edit-tab.php';
        $add_tab = new Quickr_Membership_Add_Edit_Tab();
        add_action('init', array($add_tab, 'init'));
        add_action('admin_notices', array($add_tab, 'notices'));
        add_action('quickr_memberships_tab_membership_add', array($add_tab, 'render_content'));
    }

    public function register_payments_menu_hooks() {
        require_once QUICKR_PATH . 'classes/admin/payments-tab/class-quickr-payment-list-tab.php';
        $payment_history = new Quickr_Payment_List_Tab();
        add_action('quickr_payments_tab_payments_history', array($payment_history, 'render_content'));
        require_once QUICKR_PATH . 'classes/admin/payments-tab/class-quickr-button-list-tab.php';
        $payment_button = new Quickr_Button_List_Tab();
        add_action('quickr_payments_tab_payments_buttons', array($payment_button, 'render_content'));
        add_action('init', array($payment_button, 'init'));
        add_action('admin_notices', array($payment_button, 'notices'));
        require_once QUICKR_PATH . 'classes/admin/payments-tab/class-quickr-paypal-tab.php';
        $paypal_tab = new Quickr_Paypal_Tab();
        add_action('init', array($paypal_tab, 'init'));
        add_action('admin_notices', array($paypal_tab, 'notices'));
        add_action('quickr_payments_tab_payments_paypal', array($paypal_tab, 'render_content'));
    }

    public function register_extensions_menu_hooks() {
        
    }

    /**
     * 
     */
    public function enqueue_styles() {
        wp_enqueue_style('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_style(Quickr_Constants::name, QUICKR_URL . '/css/admin/admin-style.css', array(), "all");
    }

    /**
     * 
     */
    public function enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
    }

    /**
     * 
     */
    public function menu() {
        require_once QUICKR_PATH . 'classes/admin/class-quickr-menu-builder.php';
        $builder = new Quickr_Menu_Builder();
        $builder->build();
    }

    /**
     * 
     * @param array $column
     * @return string
     */
    public function inject_extra_user_col($column) {
        $column['quickr_membership'] = Quickr_I18n::_('Membership Level');
        $column['quickr_account_status'] = Quickr_I18n::_('Account Status');
        return $column;
    }

    /**
     * 
     * @param type $value
     * @param type $col_name
     * @param type $user_id
     * @return int
     */
    public function inject_extra_user_col_value($value, $col_name, $user_id) {
        global $wpdb;

        switch ($col_name) {
            case 'quickr_membership':
                $query = $wpdb->prepare("SELECT membership_ID FROM " . $wpdb->prefix . Quickr_Constants::membership_rel .
                        " WHERE user_ID=%d", $user_id);
                
                $membership_id = $wpdb->get_var($query);
                $membership = get_post($membership_id);
                if (!empty($membership) && $membership->post_type == 'quickr_member_level') {
                    return $membership->post_title;
                }
                $request_uri = sanitize_text_field(filter_input(INPUT_SERVER, 'REQUEST_URI'));
                $edit_link = esc_url(add_query_arg('wp_http_referer', urlencode(wp_unslash($request_uri)), get_edit_user_link($user_id)));

                return Quickr_I18n::_('Membership level isn\'t assigned to this user.')
                        . ' <a href="' . $edit_link . '#quickr_membership">' . Quickr_I18n::_('Assign now?') . '</a>';
            case 'quickr_account_status':
                $status = get_user_meta($user_id, Quickr_Constants::member_account_status_metakey, true);
                require_once QUICKR_CLASSES . 'data/class-quickr-admin-member-form-data.php';
                return Quickr_Admin_Member_Form_Data::get_account_status_name($status);
        }
        return $value;
    }

    /**
     * 
     * @param type $links
     * @return type
     */
    function add_settings_link($links) {
        $links[] = '<a href="admin.php?page=quickr_settings">Settings</a>';
        return $links;
    }

}
