
<!--<h3 class="hndle"><label for="title">Search Membership Levels</label></h3>
<?php echo Quickr_I18n::_('Search for a transaction by using email or name'); ?>
<br /><br />
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <?php echo wp_nonce_field('quickr_membership_search_nonce', 'quickr_membership_search_nonce_submit'); ?>
    <input name="quickr_membership_search" type="text" size="40" value="<?php echo $quickr_membership_search; ?>"/>
    <input type="submit" name="quickr_membership_search" class="button" value="<?php echo Quickr_I18n::_('Search'); ?>" />
</form>-->
<p><i>List Shows all available membership level defined.</i></p>
<?php $grid->prepare_items(); ?>
<form id="tables-filter"  method="post" onSubmit="return confirm('Are you sure you want to perform this bulk operation on the selected entries?');">
    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
    <?php $grid->display(); ?>
</form>

