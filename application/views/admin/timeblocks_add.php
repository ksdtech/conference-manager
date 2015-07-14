<?php echo validation_errors(); ?>

<?php echo form_open(site_url('admin').'/timeblocks/add'); ?>
	<div><label for="duration">Duration in minutes</label><br>
    <input type="text" id="duration" name="duration" size="20" value="<?php echo set_value('duration'); ?>"></div>
	<div><label for="time_blocks">Time blocks</label><br>
    <input type="text" id="time_blocks" name="time_blocks" size="60" value="<?php echo set_value('time_blocks'); ?>"></div>
    <div><input type="submit" value="Submit" /></div>
</form>
<a href="<?php echo site_url('admin').'/timeblocks/index'; ?>">Cancel</a>