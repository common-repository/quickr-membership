<form method="post" action="<?php echo filter_input(INPUT_SERVER, 'REQUEST_URI') ?>">
<h2><?php Quickr_I18n::e("Log Export")?></a>
<p><?php Quickr_I18n::e('Log viewing/exporting debugging issues with payment processing.'
        . '<b>Note that you need to enable logging to get this functionality working</b>.'
        . 'Logging can be enabled from <a href="'.admin_url(sprintf('admin.php?page=quickr_settings&tab=settings_advanced')).'">here</a')?></p>
<table class="form-table">
    <tr class="form-field">
        <th>
        </th>
        <td>
            <input type="checkbox" name="export-as-file" /> <?php Quickr_I18n::e('Download as file?');?>
            <br><span class="description"><i><?php Quickr_I18n::e('Check this field if you want to download log as file.'); ?></i></span>
        </td>
    </tr>   
</table>
<?php echo submit_button('Export Log', 'submit', 'quickr-log-export-submit'); ?>
</form>