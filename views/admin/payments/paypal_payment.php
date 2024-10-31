<h3 class="hndle"><label for="title"><?php Quickr_I18n::e('Add/Edit Paypal Button');?></label></h3>    
<p><?php Quickr_I18n::e('Create or Edit paypal button for a membership level') ?>. </p>
<form method="get" name="create-paypal-button" id="quickr-paypal-button" class="validate" novalidate="novalidate">
    <?php //wp_nonce_field('quickr_membership_level_nonce_submit','quickr_membership_level_nonce'); ?>
    <input type="hidden" name="tab" value="payments_paypal" />
    <input type="hidden" name="step" value="1" />
    <input type="hidden" name="page" value="quickr_payments" />
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="button_type"><?php Quickr_I18n::e("Paypal Buy Now Button");?>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Select this option if you want to let your customers buy one item at a time')?>.</p>
                    <input name="button_type" id="button_type_buy_now" aria-required="true" value="paypal_buy_now" type="radio" checked="checked"> <br/>                    
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row">
                    <label for="button_type"><?php Quickr_I18n::e("Paypal Subscription Button");?></label>
                </th>
                <td>
                    <p><?php Quickr_I18n::e('Select this option if you want to bill customers on a regular basis, charge membership dues, or offer subscription services and installment plans')?>.</p>
                    <input name="button_type" id="button_type_subscribe" aria-required="true" value="paypal_subscribe" type="radio"><br/>                    
                </td>
            </tr>                                    
        </tbody>
    </table>
    <?php submit_button(Quickr_I18n::_("Next"), 'button-primary', 'quickr-membership-submit'); ?>
</form>
<script type="text/javascript">
</script>