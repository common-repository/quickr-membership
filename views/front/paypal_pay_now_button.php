<div class="quickr-button-wrapper quickr-paypal-buy-now-wrapper">
    <form action="<?php echo $server ?>" method="post"  <?php echo $window_target ?> >
        <input type="hidden" name="cmd" value="_xclick" />
        <input type="hidden" name="charset" value="utf-8" />
        <input type="hidden" name="bn" value="Quicrk_PAY" />
        <input type="hidden" name="business" value="<?php echo $business  ?>" />
        <input type="hidden" name="amount" value="<?php echo $amount ?>" />
        <input type="hidden" name="currency_code" value="<?php echo  $currency_code ?>" />
        <input type="hidden" name="item_number" value="<?php echo  $item_number ?>" />
        <input type="hidden" name="item_name" value="<?php echo  $item_name ?>" />

        <input type="hidden" name="no_shipping" value="1" />

        <input type="hidden" name="notify_url" value="<?php echo  $notify_url ?>" />
        <input type="hidden" name="return" value="<?php echo  $return ?>" />
        <input type="hidden" name="cancel_return" value="<?php echo  $cancel_return ?>" />

        <input type="hidden" name="custom" value="<?php echo  $custom ?>" />

        <?php echo apply_filters('quickr_pay_paypal_form_additional_fields', ''); ?>
        <?php
        if (!empty($button_image_url)):
            ?>    
        <input type="image" src="<?php echo  $button_image_url ?>" class="quickr-buy-now-button-submit" alt="<?php  Quickr_I18n::e('Buy Now') ?>"/>';
            <?php
        else :
            ?>
            <input type="submit" class="quickr-buy-now-button-submit" value="<?php echo  $button_text ?>" />
        <?php
        endif;
        ?>
    </form>
</div>