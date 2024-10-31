<h3 class="hndle"><label for="title"><?php Quickr_I18n::e ('Add/Edit PayPal Buy Now Button')?></label></h3>    
<p><?php Quickr_I18n::e('Create/edit a paypal buy now button that lets your customers buy one item at a time')?>.</p>
<form method="post" name="create-paypal-button" id="quickr-paypal-button" class="validate" novalidate="novalidate">
    <input name="action" value="create-paypal-button" type="hidden">
    <?php wp_nonce_field('quickr_paypal_button_nonce_submit','quickr_paypal_button_nonce'); ?>
    <input type="hidden" name="tab" value="payments_paypal" />
    <input type="hidden" name="step" value="1" />
    <input type="hidden" name="button_type" value="paypal_buy_now" />
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required<?php echo isset($this->payment_data->errors['membership_level'])? " form-invalid" : "";?>" >
                <th scope="row">
                    <label for="membership_level"><?php Quickr_I18n::e("Membership Level");?> <span class="description">(<?php Quickr_I18n::e("required")?>)</span>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Select membership level for this button. Any user registration through this button will be assigned to selected membership level')?></p>
                    <select name="membership_level">
                        <?php echo Quickr_Membership_Form_Data::membership_dropdown($this->payment_data->membership_level);?>
                    </select>                                    
                </td>
            </tr>
            <tr class="form-field form-required<?php echo isset($this->payment_data->errors['title'])? " form-invalid" : "";?>" >
                <th scope="row">
                    <label for="title"><?php Quickr_I18n::e("Title");?> <span class="description">(<?php Quickr_I18n::e("required")?>)</span>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Give a title for this button')?></p>
                    <input name="title" id="name" aria-required="true" value="<?php echo $this->payment_data->title ?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text"><br/>                                    
                </td>
            </tr>
            <tr class="form-field form-required<?php echo isset($this->payment_data->errors['billing_email'])? " form-invalid" : "";?>" >
                <th scope="row">
                    <label for="billing_email"><?php Quickr_I18n::e("PayPal Email");?> <span class="description">(<?php Quickr_I18n::e("required")?>)</span>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Enter your PayPal email address. The payment will go to this PayPal account')?></p>
                    <input name="billing_email" id="name" aria-required="true" value="<?php echo $this->payment_data->billing_email; ?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text"><br/>                                    
                </td>
            </tr>  
            <tr class="form-field form-required<?php echo isset($this->payment_data->errors['billing_currency'])? " form-invalid" : "";?>" >
                <th scope="row">
                    <label for="billing_currency"><?php Quickr_I18n::e("Payment Currency");?> <span class="description">(<?php Quickr_I18n::e("required")?>)</span>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Select currency for above payment amount')?></p>
                    <select name="billing_currency">
                        <?php echo Quickr_Paypal_Form_Data::currency_dropdown($this->payment_data->billing_currency)?>
                    </select> 
                </td>
            </tr>                                  
            <tr class="form-field form-required<?php echo isset($this->payment_data->errors['billing_amount'])? " form-invalid" : "";?>" >
                <th scope="row">
                    <label for="billing_amount"><?php Quickr_I18n::e("Payment Amount");?> <span class="description">(<?php Quickr_I18n::e("required")?>)</span>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Select a payment amount without currency(currency can selected from input field below).')?></p>
                    <input name="billing_amount" id="name" aria-required="true" value="<?php echo $this->payment_data->billing_amount ?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text"><br/>                                    
                </td>
            </tr>
        </tbody>
    </table>
    <h3>Optional Fields</h3>
        <table class="form-table">
        <tbody>
            <tr class="form-field" >
                <th scope="row">
                    <label for="return_url"><?php Quickr_I18n::e("Payment Success URL");?>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('The user will be redirected to after a successful payment. Enter the URL of your Thank You page here. The user will be redirected to home page if this field is empty.')?></p>
                    <input name="return_url" id="name" aria-required="true" value="<?php echo $this->payment_data->return_url; ?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text"><br/>                                    
                </td>
            </tr>            
            <tr class="form-field" >
                <th scope="row">
                    <label for="cancel_return_url"><?php Quickr_I18n::e("Payment Failed URL");?>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('The user will be redirected to if payment fails. The user will be redirected to home page if this field is empty.')?></p>
                    <input name="cancel_return_url" id="name" aria-required="true" value="<?php echo $this->payment_data->cancel_return_url; ?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text"><br/>                                    
                </td>
            </tr>            
            <tr class="form-field" >
                <th scope="row">
                    <label for="button_image"><?php Quickr_I18n::e("Button Image URL");?>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('If you want to customize the look of the button using an image then enter the URL of the image.')?></p>
                    <input name="button_image_url" id="name" aria-required="true" value="<?php echo $this->payment_data->button_image_url; ?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text"><br/>                                    
                </td>
            </tr> 

        </tbody>
    </table>
    <?php submit_button(Quickr_I18n::_("Save Changes"), 'button-primary', 'quickr-payment-button-submit'); ?>
</form>