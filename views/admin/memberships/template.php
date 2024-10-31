<?php  do_action( 'quickr_memberships_menu_start' );?>
<div class="wrap quickr-admin-menu-wrap"><!-- start wrap -->
    <h1><?php echo Quickr_I18n::_('Quickr Membership') . ' &raquo; ' . Quickr_I18n::_('Membership Levels')  ?></h1><!-- page title -->
    <!-- start nav menu tabs -->
    <?php do_action("quickr-draw-memberships-nav-tabs"); ?>
    <!-- end nav menu tabs -->
    <?php do_action( 'quickr_memberships_menu_after_nav_tabs' ); ?>
    <?php do_action('quickr_memberships_tab_' . $this->current_tab); ?>
</div><!-- end of wrap -->

