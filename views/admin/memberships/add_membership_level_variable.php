<h3 class="hndle"><label for="title"><?php Quickr_I18n::e ('Add/Edit Membership Level That Expires After Given Number of Days of Membership Privilege ')?></label></h3>    
<p><?php Quickr_I18n::e('Create a new membership level that expires after predefined number of days')?>.</p>
<form method="post" name="create-membership-level" id="quickr-membership-level" class="validate" novalidate="novalidate">
    <input name="action" value="create-membership-level" type="hidden">
    <?php wp_nonce_field('quickr_membership_level_nonce_submit','quickr_membership_level_nonce'); ?>
    <input type="hidden" name="tab" value="membership_add" />
    <input type="hidden" name="step" value="1" />
    <input type="hidden" name="duration_type" value="variable" />
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required<?php echo isset($this->membership_data->errors['name'])? " form-invalid" : "";?>">
                <th scope="row">
                    <label for="name"><?php Quickr_I18n::e("Membership Name");?> <span class="description">(<?php Quickr_I18n::e("required")?>)</span>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Give a title for this membership level')?></p>
                    <input name="name" id="name" aria-required="true" value="<?php echo $membership->name; ?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text">
                    
                </td>
            </tr>
            <tr class="form-field form-required<?php echo isset($this->membership_data->errors['role'])? " form-invalid" : "";?>">
                <th scope="row">
                    <label for="role"><?php Quickr_I18n::e("Role");?><span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
                </th>
                <td>
                    <p><?php Quickr_I18n::e('Default role given to members assigned to this membership level')?></p>
                    <select name="role" id="role">
                        <?php wp_dropdown_roles( $membership->role ); ?>
                    </select>
                </td>
            </tr>            
            <tr class="form-field form-required<?php echo isset($this->membership_data->errors['duration'])? " form-invalid" : "";?>">
                <th scope="row">
                    <label for="duration"><?php Quickr_I18n::e("Membership Duration")?> <span class="description">(<?php Quickr_I18n::e("required")?>)</span></label>
                </th>
                <td><p><?php Quickr_I18n::e('Specify the number of days after which access privilege to this membership level will expire. <br/><b>A 0 indicates membership never expires</b>.')?></p>
                    <input name="duration" id="duration" aria-required="true" value="<?php echo $membership->duration;?>" autocapitalize="none" autocorrect="off" maxlength="60" type="text">
                    
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row">
                    <label for="login_redirect_page"><?php Quickr_I18n::e("Login Redirect Page");?> </label>
                </th>
                <td>
                    <p><?php Quickr_I18n::e('Default home page for this membership level. Members assigned to this membership level will be redirected to this page after login. Can be left empty')?>.</p>
                    <input name="login_redirect_page" id="login_redirect_page" value="<?php echo $membership->login_redirect_page;?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" type="text">
                </td>
            </tr>            
            <tr class="form-field">
                <th scope="row">
                    <label for="protect_older_posts"><?php Quickr_I18n::e("Protect Older Posts");?> </label>
                </th>
                <td><p><?php Quickr_I18n::e('Select if members be given access to content created before her membership'); ?>.</p>
                    <input name="protect_older_posts" id="protect_older_posts" <?php echo empty($membership->protect_older_posts)? '':'checked="checked"' ?> value="1" type="checkbox">
                    
                </td>
            </tr>                        
            <tr class="form-field">
                <th scope="row">
                    <label for="campaign_name"><?php Quickr_I18n::e("Campaign Name");?> </label>
                </th>
                <td>
                    <input name="campaign_name" id="login_redirect_page" value="<?php echo $membership->campaign_name;?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" type="text">
                </td>
            </tr>                        
        </tbody>
    </table>
    <?php submit_button(Quickr_I18n::_("Save Changes"), 'button-primary', 'quickr-membership-submit'); ?>
</form>
<script type="text/javascript">
</script>