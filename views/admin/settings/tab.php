<h2 class="nav-tab-wrapper">
     <?php foreach ($tabs as $id => $label){ 
         $url = admin_url('admin.php?page=quickr_settings&tab=' . $id);
         ?>
         <a class="nav-tab <?php echo ($this->current_tab == $id) ? 'nav-tab-active' : ''; ?>" href="<?php echo $url ?>"><?php echo $label ?></a>
     <?php } ?>
 </h2>


