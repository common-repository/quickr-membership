<a name="quickr_membership"><h2><?php Quickr_I18n::e("Membership Info")?></h2></a>
<table class="form-table">
    <tr class="form-field form-required">
        <th>
            <label for="quickr_membership"><?php Quickr_I18n::e('Membership'); ?><span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
        </th>
        <td>
            <select name="quickr_membership">
                <?php echo Quickr_Membership_Form_Data::membership_dropdown($this->member_data->membership_ID); ?>
            </select>
            <br><span class="description"><?php Quickr_I18n::e('Select the membership level. User will have access to content in the level if the account status  is \'Active\' or \'Expired\' when access is enabled for expired account. '); ?></span>
        </td>
    </tr>        
    <tr class="form-field form-required">
        <th>
            <label for="quickr_account_status"><?php Quickr_I18n::e('Account State'); ?><span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
        </th>
        <td>
            <select name="quickr_account_status">
                <?php echo Quickr_Admin_Member_Form_Data::account_status_dropdown($this->member_data->account_status); ?>
            </select>
            <br><span class="description"><?php Quickr_I18n::e('Select the account status. Accounts with \'Active\' (\'Expired\' with enabled in the settings) status is allowed to login.'); ?></span>
        </td>
    </tr>
    <tr class="form-field form-required">
        <th>
            <label for="quickr_activation_date"><?php Quickr_I18n::e('Access Start Date'); ?><span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
        </th>
        <td>
            <input type="text" name="quickr_activation_date" id="quickr_activation_date" value="<?php echo $this->member_data->activation_date;?>" class="regular-text" />
            <br><span class="description"><?php Quickr_I18n::e('Modify account activation date. \'Active\' account will be accessible. ' ); ?></span>
        </td>
    </tr>            
    <tr class="form-field form-required">
        <th>
            <label for="quickr_expiration_date"><?php Quickr_I18n::e('Expiration Date'); ?></label>
        </th>
        <td>
            <input type="text" name="quickr_expiration_date" id="quickr_expiration_date" value="<?php echo $this->member_data->expiration_date;?>" class="regular-text" />
            <br><span class="description"><?php Quickr_I18n::e('Modify account expiration date. \'Active\' account will be inaccessible. <b>If empty then expiration will be calculated based on membership level.</b>' ); ?></span>
        </td>        
    </tr>                
   <tr>
        <th>
            <label for="quickr_reg_code"><?php Quickr_I18n::e('Registration Code'); ?></label>
        </th>
        <td>
            <input type="text" name="quickr_reg_code" id="quickr_reg_code" value="<?php echo $this->member_data->reg_code; ?>" class="regular-text" />
            <br><span class="description"><?php Quickr_I18n::e('Paid members are given unique code for verification purpose(not needed for free members).'); ?></span>
        </td>
    </tr>
   <tr>
        <th>
            <label for="quickr_referrer"><?php Quickr_I18n::e('Referrer'); ?></label>
        </th>
        <td>
            <input type="text" name="quickr_referrer" id="quickr_referrer" value="<?php echo $this->member_data->referrer;?>" class="regular-text" />
            <br><span class="description"><?php Quickr_I18n::e('Optionally referrer can be specified here'); ?></span>
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