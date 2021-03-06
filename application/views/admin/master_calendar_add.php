<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Add a Schedule for <?php echo $month.'/'.$day.'/'.$year; ?></h1>
	</div>
	<?php echo validation_errors(); ?>
    <div class="mdl-card__supporting-text">

	<?php echo form_open(site_url('admin').'/mastercalendar/add/'.$year.'/'.$month.'/'.$day); ?>
		<input type="hidden" name="date" value="<?php echo sprintf('%04d-%02d-%02d', $year, $month, $day); ?>" />
		<div><label for="schedule">Schedule</label><br>
		<?php echo form_dropdown('schedule', $schedules, '', 'id="schedule"'); ?>
    	<div><input type="submit" value="Submit" /></div>
	</form>
	<a href="<?php echo site_url('admin').'/mastercalendar/index/'.$year.'/'.$month; ?>">Cancel</a>
	<div>
  </div>
</section>
