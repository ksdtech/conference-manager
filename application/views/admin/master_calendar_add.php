<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Add a Schedule for <?php echo $date; ?></h1>
	</div>
	<?php echo validation_errors(); ?>
    <div class="mdl-card__supporting-text">

	<?php echo form_open(site_url('admin').'/mastercalendar/add/'.$date); ?>
		<input type="hidden" name="date" value="<?php echo $date; ?>" />
		<div><label for="schedule">Schedule</label><br>
		<?php echo form_dropdown('schedule', $schedules, '', 'id="schedule"'); ?>
		<div><label for="calendar">Resource calendar</label><br>
		<?php echo form_dropdown('calendar', $calendars, '', 'id="calendar"'); ?>
    	<div><input type="submit" value="Submit" /></div>
	</form>
	<a href="<?php echo site_url('admin').'/mastercalendar/index/'.$date; ?>">Cancel</a>
	<div>
  </div>
</section>
