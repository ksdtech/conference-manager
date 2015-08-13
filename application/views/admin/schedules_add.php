<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Add a Schedule</h1>
	</div>
	<?php echo validation_errors(); ?>
    <div class="mdl-card__supporting-text">

	<?php echo form_open(site_url('admin').'/schedules/add/'.$resource_calendar_id); ?>	
		<div><label for="name">Schedule name</label><br>
    	<input type="text" id="name" name="name" size="40" value="<?php echo set_value('name'); ?>"></div>
		<div><label for="description">Description</label><br>
    	<textarea id="description" name="description" cols="60" rows="3"><?php echo set_value('description'); ?></textarea></div>
    	<div><input type="submit" value="Submit" /></div>
	</form>
	<a href="<?php echo site_url('admin').'/schedules/index'; ?>">Cancel</a>
	<div>
  </div>
</section>
