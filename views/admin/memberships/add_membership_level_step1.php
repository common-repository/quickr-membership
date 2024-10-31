<h3 class="hndle"><label for="title">Add Membership Level</label></h3>    
<p><?php Quickr_I18n::e('Create a new membership level. Membership level allow you to categorize published articles/content into groups that you can selectively control access') ?>. </p>
<form method="get" name="create-membership-level" id="quickr-membership-level" class="validate" novalidate="novalidate">
    <?php //wp_nonce_field('quickr_membership_level_nonce_submit','quickr_membership_level_nonce'); ?>
    <input type="hidden" name="tab" value="membership_add" />
    <input type="hidden" name="step" value="1" />
    <input type="hidden" name="page" value="quickr_membership_level" />
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="duration_type"><?php Quickr_I18n::e("Fixed Duration");?>
                    </label>
                </th>
                <td><p><?php Quickr_I18n::e('Select this option if you want the membership level you are about to create, will expire on a specific date')?>.</p>
                    <input name="duration_type" id="duration_type_fixed" aria-required="true" value="fixed" type="radio" checked="checked"> <br/>                    
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row">
                    <label for="role"><?php Quickr_I18n::e("Variable Duration");?></label>
                </th>
                <td>
                    <p><?php Quickr_I18n::e('Select this option if you want the membership level you are about to create, will expire after fixed number of days from day membership started')?>.</p>
                    <input name="duration_type" id="duration_type_variable" aria-required="true" value="variable" type="radio"><br/>                    
                </td>
            </tr>                                    
        </tbody>
    </table>
    <?php submit_button(Quickr_I18n::_("Next"), 'button-primary', 'quickr-membership-submit'); ?>
</form>
<script type="text/javascript">
</script>