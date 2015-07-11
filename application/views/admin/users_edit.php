<h1>Edit User</h1>
<?php echo validation_errors(); ?>

<?php echo form_open('admin/users/edit/'.$user['user_id']); ?>
	<div><label for="user_level">User level</label><br>
    <select id="user_level" name="user_level">
    <option value="1" <?php echo set_select('user_level', '1', $user['user_level'] == 1); ?> >Parent</option>
    <option value="6" <?php echo set_select('user_level', '6', $user['user_level'] == 6); ?> >Teacher</option>
    <option value="9" <?php echo set_select('user_level', '9', $user['user_level'] == 9); ?> >Administrator</option>
    </select></div>
	<div><label for="first_name">First name</label><br>
    <input type="text" id="first_name" name="first_name" value="<?php echo set_value('first_name', $user['first_name']); ?>"></div>
	<div><label for="last_name">Last name</label><br>
    <input type="text" id="last_name" name="last_name" value="<?php echo set_value('last_name', $user['last_name']); ?>"></div>
	<div><label for="user_email">Email address</label><br>
    <input type="text" id="user_email" name="user_email" value="<?php echo set_value('user_email', $user['user_email']); ?>"></div>
	<div><label for="user_email">Change password</label><br>
    <input type="password" id="user_pass" name="user_pass" value="<?php echo set_value('user_pass'); ?>"></div>
	<div><label for="confirm_pass">Confirm password</label><br>
    <input type="password" id="confirm_pass" name="confirm_pass" value=""></div>
    <div><input type="submit" value="Submit" /></div>
</form>
<a href="<?php echo site_url('admin').'/users/index'; ?>">Cancel</a>