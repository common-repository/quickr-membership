<form action=" <?php echo $uri; ?>" method="post">
    <input type="hidden" name="quickr-form-type" value="profile"/>
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" readonly="readonly" disabled="disabled" value="<?php echo $current_user->user_login ?>">
    </div>
   <div>
        <label for="membership">Membership Level</label>
        <input type="text" name="membership" readonly="readonly" disabled="disabled" value="<?php echo $member->membership_name ?>">
    </div>
    <div>
        <label for="password">Password <strong>*</strong></label>
        <input type="password" name="password" value="">
    </div>

    <div>
        <label for="email">Email <strong>*</strong></label>
        <input type="text" name="email" value="<?php echo $current_user->user_email ?>">
    </div>

    <div>
        <label for="website">Website</label>
        <input type="text" name="website" value="<?php echo $current_user->user_url ?>">
    </div>

    <div>
        <label for="firstname">First Name</label>
        <input type="text" name="fname" value="<?php echo $current_user->first_name  ?>">
    </div>

    <div>
        <label for="website">Last Name</label>
        <input type="text" name="lname" value="<?php echo $current_user->last_name  ?>">
    </div>

    <div>
        <label for="nickname">Nickname</label>
        <input type="text" name="nickname" value="<?php echo $current_user->user_nicename ?>">
    </div>
    <br class="clear">
    <input type="submit" name="quickr-form-submit" value="Update"/>
</form>        