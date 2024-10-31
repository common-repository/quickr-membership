<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Quickr_Constants
 *
 * @author nur
 */
class Quickr_Constants {

    const name = "quickr-membership";
    const version = '1.0.0';
    const db_version = '1.0.0';
    const membership = "quickr_memberships";
    const membership_rel = "quickr_member_membership_rels";
    const membership_post_rel = "quickr_membership_post_rels";
    const transaction = "quickr_transactions";
    const member_status_active = 'active';
    const member_status_inactive = 'inactive';
    const member_status_expired = 'expired';
    const member_status_incomplete = 'incomplete';
    const member_status_pending = 'pending';
    const member_account_status_metakey = 'quickr_account_status';
    const member_referrer_metakey = 'quickr_referrer';
    const member_reg_code_metakey = 'quickr_reg_code';
    const member_notes_metakey = 'quickr_notes';
    const membership_role_metakey = 'quickr_role';
    const membership_duration_type_metakey = 'quickr_duration_type';
    const membership_duration_metakey = 'quickr_duration';
    const membership_login_redirect_page_metakey = 'quickr_login_redirect_page';
    const membership_protect_older_posts_metakey = 'quickr_protect_older_posts';
    const membership_campaign_name_metakey = 'quickr_campaign_name';
    const billing_amount_metakey = 'quickr_button_billing_amount';
    const billing_currency_metakey = 'quickr_billing_currency';
    const billing_email_metakey = 'quickr_billing_email';
    const billing_membership_level_metakey = 'quickr_billing_membership_level';
    const billing_return_url_metakey = 'quickr_billing_return_url';
    const billing_cancel_return_url_metakey = 'quickr_billing_cancel_return_url';
    const billing_button_image_url_metakey = 'quickr_billing_button_image_url';
    const billing_payment_processer_metakey = 'quickr_billing_paypment_processor';
    const paypal_billing_cycle_metakey = 'paypal_billing_cycle';
    const paypal_billing_cycle_term_metakey = 'paypal_billing_cycle_term';
    const paypal_billing_cycle_count_metakey = 'paypal_billing_cycle_count';
    const paypal_billing_retry_metakey = 'paypal_billing_retry';
    const paypal_billing_trial_amount_metakey = 'paypal_billing_trial_amount';
    const paypal_billing_trial_cycle_metakey = 'paypal_billing_trial_cycle';
    const paypal_billing_trial_cycle_term_metakey = 'paypal_billing_trial_cycle_term';
    
    const log_level_error = 'error';
    const log_level_info = 'info';
    const log_level_debug ='debug';
    const log_level_event = 'event';
    const log_level_fatal = 'fatal';
    
    const SANDBOX_SERVER = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    const REAL_SERVER = 'https://www.paypal.com/cgi-bin/webscr';
}
