<h3 class="hndle"><label for="title">Search Buttons</label></h3>
<?php echo Quickr_I18n::_('Search for a button by button name, gateway'); ?>
<br /><br />
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input name="quickr_button_search" type="text" size="40" value="<?php echo $quickr_button_search; ?>"/>
    <input type="submit" name="quickr_button_search_btn" class="button" value="<?php echo Quickr_I18n::_('Search'); ?>" />
</form>
<?php $grid->prepare_items(); ?>
<form id="tables-filter" method="get" onSubmit="return confirm('Are you sure you want to perform this bulk operation on the selected entries?');">
    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
    <?php $grid->display(); ?>
</form>

