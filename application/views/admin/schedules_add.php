<h1>Edit Schedule</h1>
<?php echo validation_errors(); ?>

<?php echo form_open(site_url('admin').'/schedules/add'); ?>
	<div><label for="name">First name</label><br>
    <input type="text" id="name" name="name" value="<?php echo set_value('name'); ?>"></div>
	<div><label for="description">Last name</label><br>
    <textarea id="description" name="description"><?php echo set_value('description'); ?></textarea></div>
    <div><input type="submit" value="Submit" /></div>
	<div><label for="interval_in_minutes">Interval between appointments (minutes)</label><br>
    <input type="text" id="interval_in_minutes" name="interval_in_minutes" value="<?php echo set_value('interval_in_minutes'); ?>"></div>
	<div><label for="duration_in_minutes">Duration of each appointment (minutes)</label><br>
    <input type="text" id="duration_in_minutes" name="duration_in_minutes" value="<?php echo set_value('duration_in_minutes'); ?>"></div>
</form>
<a href="<?php echo site_url('admin').'/schedules/index'; ?>">Cancel</a>
