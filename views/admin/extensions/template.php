<?php  do_action( 'quickr_extensions_menu_start' );?>
<div class="wrap quickr-admin-menu-wrap"><!-- start wrap -->
    <h1><?php echo Quickr_I18n::_('Quickr Extension') . ' &raquo; ' . Quickr_I18n::_('Members') ?></h1><!-- page title -->
    <!-- start nav menu tabs -->
    <?php do_action("quickr-draw-extensions-nav-tabs"); ?>
    <!-- end nav menu tabs -->
    <?php do_action( 'quickr_extensions_menu_after_nav_tabs' ); ?>
    <?php do_action('quickr_extensions_tab_' . $this->current_tab); ?>
</div><!-- end of wrap -->

