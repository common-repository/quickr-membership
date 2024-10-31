<h3 class="hndle"><label for="title">Search Members</label></h3>
<i><?php Quickr_I18n::e('Search user using email or name'); ?></i>
<br /><br />
<form method="post" action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI') ?>">
    <input type="hidden" name="tab" value="members_approve" />
    <input name="quickr_member_search" type="text" size="40" value="<?php echo $quickr_member_search; ?>"/>
    <input type="submit" name="quickr_member_search_submit" class="button" value="<?php echo Quickr_I18n::_('Search'); ?>" />
</form>
<p><i><?php Quickr_I18n::e('Click on the checkboxes below on the left of the member records and select account status from the <b>Bulk Actions</b> dropdown that you want to apply to selected members. '
        . ' Note that selecting <b>Set Status to Active And Notify</b> will send email notification to the user.');?></i></p>
<?php $grid->prepare_items(); ?>
<form id="tables-filter" method="post" onSubmit="return confirm('Are you sure you want to perform this bulk operation on the selected entries?');">
    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
    <input type="hidden" name="tab" value="members_approve" />
    <input type="hidden" name="page" value="<?php echo filter_input(INPUT_GET, 'page'); ?>" />
    <?php $grid->display(); ?>
</form>

