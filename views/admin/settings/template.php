<?php  do_action( 'quickr_settings_menu_start' );?>
<div class="wrap quickr-admin-menu-wrap"><!-- start wrap -->
    <h1><?php echo Quickr_I18n::_('Quickr Membership') . ' &raquo; ' . Quickr_I18n::_('Settings') ?></h1><!-- page title -->
    <!-- start nav menu tabs -->
    <?php do_action("quickr-draw-settings-nav-tabs"); ?>
    <!-- end nav menu tabs -->
    <?php do_action( 'quickr_settings_menu_after_nav_tabs' ); ?>
    <!-- This file outputs the settings form fields for a lot of the settings pages -->
     <? settings_errors(); ?>
    <form action="options.php" method="POST">
        <input type="hidden" name="tab" value="<?php echo $this->current_tab; ?>" />
        <?php settings_fields('quickr_settings_tab_' . $this->current_tab); ?>
        <?php do_settings_sections('quickr_settings'); ?>
        <?php submit_button(); ?>
    </form>
</div><!-- end of wrap -->

