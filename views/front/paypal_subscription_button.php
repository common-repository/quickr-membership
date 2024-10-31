<div class="quickr-button-wrapper quickr-paypal-subscription-wrapper">
    <form action="<?php echo $server ?>" method="post"  <?php echo $window_target ?> >
        <input type="hidden" name="cmd" value="_xclick-subscriptions" />
        <input type="hidden" name="charset" value="utf-8" />
        <input type="hidden" name="bn" value="Quicrk_PAY" />
        <input type="hidden" name="business" value="<?php echo $business ?>" />
        <input type="hidden" name="amount" value="<?php echo $amount ?>" />
        <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>" />
        <input type="hidden" name="item_number" value="<?php echo $item_number ?>" />
        <input type="hidden" name="item_name" value="<?php echo $item_name ?>" />
        <?php
        if (!empty($p1)):
            ?>
            <input type="hidden" name="a1" value="<?php echo $a1 ?>" />
            <input type="hidden" name="p1" value="<?php echo $p1 ?>" />
            <input type="hidden" name="t1" value="<?php echo $t1 ?>" /> 
            <?php
        endif;
        //Main subscription billing
        if (!empty($p3)) :
            ?>
            <input type="hidden" name="a3" value="<?php echo $a3 ?>" />
            <input type="hidden" name="p3" value="<?php echo $p3 ?>" />
            <input type="hidden" name="t3" value="<?php echo $t3 ?>" />
            <?php
        endif;
        //Re-attempt on failure
        if (empty($sra)) :
            ?>
            <input type="hidden" name="sra" value="1" />
            <?php
        endif;
        //Reccurring times
        if ($srt > 1) : //do not include srt value if billing cycle count set to 1 or a negetive number.
            ?>
            <input type="hidden" name="src" value="1" />
            <input type="hidden" name="srt" value="<?php echo $srt ?>" />
        <?php elseif (empty($srt)) : ?>   
            <input type="hidden" name="src" value="1" />
        <?php endif; ?>
            
        <input type="hidden" name="no_shipping" value="1" />
        <input type="hidden" name="notify_url" value="<?php echo $notify_url ?>" />
        <input type="hidden" name="return" value="<?php echo $return ?>" />
        <input type="hidden" name="cancel_return" value="<?php echo $cancel_return ?>" />
        <input type="hidden" name="custom" value="<?php echo $custom ?>" />

        <?php echo apply_filters('quickr_pay_paypal_form_additional_fields', ''); ?>
        <?php
        if (!empty($button_image_url)):
            ?>    
            <input type="image" src="<?php echo $button_image_url ?>" class="quickr-subscription-button-submit" alt="<?php Quickr_I18n::e('Subscribe Now') ?>"/>';
            <?php
        else :
            ?>
            <input type="submit" class="quickr-subscription-button-submit" value="<?php echo $button_text ?>" />
        <?php
        endif;
        ?>
    </form>
</div>