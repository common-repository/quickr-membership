<h4><?php Quickr_I18n::e('Define acess restrictions');?> </h4>
<p class="howto"><?php Quickr_I18n::e('Check the membership levels that have access to this content. 
    Members only in the selected membership levels will have access to the content. 
    If no membership level is selected then it will be accessible to all.');?> </p>
<fieldset>
<legend class="screen-reader-text">Membership Levels</legend>
<?php foreach($data->membership_levels as $level):?>
    <?php $is_checked = in_array($level->ID, $data->selected_levels); ?>
    <input name="quickr_membership[<?php echo $level->ID; ?>]" id="quickr-membership-<?php echo $level->ID; ?>" value="<?php echo $level->ID; ?>" <?php echo $is_checked? 'checked="checked"': ''?> type="checkbox"> 
    <label for="quickr-membership-<?php echo $level->ID; ?>" > <?php echo $level->post_title;?> (<?php echo $level->ID;?>)</label>
    <br>
<?php endforeach; ?>
</fieldset>