<form action="<?php echo $uri; ?>" method="post">
    <?php
            $codes = $errors->get_error_codes();
            if(!empty($codes)) {
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = $errors->get_error_message($code);
		        echo '<p class="error"><strong>'. '</strong> ' . $message . '</p>';
		    }
	}	    
    ?>
    <div>
        <label for="membership_level"><?php  Quickr_I18n::e('Membership Level'); ?> </label>        
        <input type="text" name="membership_level" readonly="readonly" value="<?php echo $membership_level;?>" />
    </div>    
    <input type="hidden" name="quickr-form-type" value="registration"/>
    <div>
        <label for="user_login">Username <strong>*</strong></label>
        <input type="text" name="user_login" value="<?php echo $user_login;?>">
        <?php echo empty($login_error)? '': '<p class="error">'  . $login_error . '</p>';?>
    </div>

    <!--<div>
        <label for="password">Password <strong>*</strong></label>
        <input type="password" name="password" value="">
    </div>-->

    <div>
        <label for="user_email">Email <strong>*</strong></label>
        <input type="text" name="user_email" value="<?php echo $user_email;?>">
        <?php echo empty($email_error)? '': '<p class="error">'  . $email_error . '</p>';?>
    </div>

    <!--<div>
        <label for="website">Website</label>
        <input type="text" name="website" value="">
    </div>

    <div>
        <label for="firstname">First Name</label>
        <input type="text" name="fname" value="">
    </div>

    <div>
        <label for="website">Last Name</label>
        <input type="text" name="lname" value="">
    </div>

    <div>
        <label for="nickname">Nickname</label>
        <input type="text" name="nickname" value="">
    </div>

    <div>
        <label for="bio">About / Bio</label>
        <textarea name="bio"></textarea>
    </div>-->
    <?php 
    $registration_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
    $redirect_to = apply_filters( 'registration_redirect', $registration_redirect );
    do_action( 'register_form' )
    ?>
    <p id="reg_passmail"><?php _e('Registration confirmation will be emailed to you.'); ?></p>
    <br class="clear" />
    <input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />    
    <input type="submit" name="quickr-form-submit" value="Register"/>
</form>        
