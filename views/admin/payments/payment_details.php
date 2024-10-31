<h2>Quickr::<?php Quickr_I18n::e('Raw transaction data')?></h2>
<table>
    <?php foreach($response_data as $key=>$value): ?>
    <tr>
        <th><?php echo $key;?></th>
        <td><?php echo $value?></td>
    </tr>
    <?php endforeach;?>
</table>