<a name="quickr_membership"><h2><?php Quickr_I18n::e("Membership Info")?></h2></a>
<table class="form-table">
    <tr class="form-field form-required<?php echo isset($this->member_data ->errors['membership_ID'])? " form-invalid" : "";?>">
        <th>
            <label for="quickr_membership"><?php Quickr_I18n::e('Membership'); ?><span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
        </th>
        <td>
            <select name="quickr_membership">
                <?php echo Quickr_Membership_Form_Data::membership_dropdown(); ?>
            </select>
            <br><span class="description"><?php Quickr_I18n::e('Select the membership level. User will have access to content in the level if the account status  is active or expired when access is enabled for expired account. '); ?></span>
        </td>
    </tr>    
    <tr class="form-field form-required<?php echo isset($this->member_data->errors['account_status'])? " form-invalid" : "";?>">
        <th>
            <label for="quickr_account_status"><?php Quickr_I18n::e('Account Status'); ?><span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
        </th>
        <td>
            <select name="quickr_account_status">
                <?php echo Quickr_Admin_Member_Form_Data::account_status_dropdown(); ?>
            </select>
            <br><span class="description"><?php Quickr_I18n::e('Select the account status. Accounts with \'Active\' (\'Expired\' with enabled in the settings) status is allowed to login.'); ?></span>
        </td>
    </tr>
    <tr class="form-field form-required<?php echo isset($this->member_data->errors['activation_date'])? " form-invalid" : "";?>">
        <th>
            <label for="quickr_activation_date"><?php Quickr_I18n::e('Access Start Date'); ?><span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
        </th>
        <td>
            <input type="text" name="quickr_activation_date" id="quickr_activation_date" value="<?php echo date('Y-m-d'); ?>" class="regular-text" />
            <br><span class="description"><?php Quickr_I18n::e('Set account activation date. \'Active\' account will be accessible. ' ); ?></span>
        </td>
    </tr>    
    <tr>
        <th>
            <label for="quickr_notes"><?php Quickr_I18n::e('Note'); ?></label>
        </th>
        <td>
            <textarea name="quickr_notes" id="quickr_notes" class="regular-text" ><?php echo $this->member_data->notes;?></textarea>
            <br><span class="description"><?php Quickr_I18n::e(''); ?></span>
        </td>
    </tr>
</table>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#quickr_activation_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });	
});    
</script>