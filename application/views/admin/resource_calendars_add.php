<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
		<h1>Add Resource Calendar</h1>
	</div>
	<?php echo validation_errors(); ?>

    <div class="mdl-card__supporting-text">
	<?php echo form_open(site_url('admin').'/resourcecalendars/add'); ?>
		<div><label for="name">Calendar name</label><br>
    	<input type="text" id="name" name="name" size="40" value="<?php echo set_value('name'); ?>"></div>
		<div><label for="description" cols="60" rows="3">Description</label><br>
    	<textarea id="description" name="description"><?php echo set_value('description'); ?></textarea></div>
		<div><label for="interval_in_minutes">Interval between appointments (minutes)</label><br>
		<?php echo form_dropdown('interval_in_minutes', $interval_options, set_value('interval_in_minutes'), 'id="interval"'); ?>
		<div><label for="duration_in_minutes">Duration in minutes</label><br>
		<?php echo form_dropdown('duration_in_minutes', $duration_options, set_value('duration_in_minutes'), 'id="duration"'); ?>
    	<div><input type="submit" value="Submit" /></div>
	</form>
	<a href="<?php echo site_url('admin').'/resourcecalendars/index'; ?>">Cancel</a>
	</div>
  </div>
</section>
