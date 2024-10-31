<p class="message">Please enter your username or email address. You will receive a link to create a new password via email.</p>
<form name="lostpasswordform" id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
    <p>
        <label for="user_login">Username or Email<br>
            <input type="text" name="user_login" id="user_login" class="input" value="" size="20"></label>
    </p>
    <input type="hidden" name="redirect_to" value="<?php echo $login_url ?>">
    <br class="clear">
    <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Get New Password"></p>
</form>