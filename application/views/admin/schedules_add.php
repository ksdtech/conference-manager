<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Add a Schedule</h1>
	</div>
	<?php echo validation_errors(); ?>
    <div class="mdl-card__supporting-text">

	<?php echo form_open(site_url('admin').'/schedules/add'); ?>
		<div><label for="name">Schedule name</label><br>
    	<input type="text" id="name" name="name" size="40" value="<?php echo set_value('name'); ?>"></div>
		<div><label for="description">Description</label><br>
    	<textarea id="description" name="description" cols="60" rows="3"><?php echo set_value('description'); ?></textarea></div>
		<div><label for="interval_in_minutes">Interval between appointments (minutes)</label><br>
    	<input type="text" id="interval_in_minutes" name="interval_in_minutes" value="<?php echo set_value('interval_in_minutes'); ?>"></div>
		<div><label for="duration_in_minutes">Duration of each appointment (minutes)</label><br>
    	<input type="text" id="duration_in_minutes" name="duration_in_minutes" value="<?php echo set_value('duration_in_minutes'); ?>"></div>
    	<div><input type="submit" value="Submit" /></div>
	</form>
	<a href="<?php echo site_url('admin').'/schedules/index'; ?>">Cancel</a>
	<div>
  </div>
</section>
