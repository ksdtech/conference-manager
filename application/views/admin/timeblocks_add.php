<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Appointment Time Blocks</h1>
	</div>
	<?php echo validation_errors(); ?>

    <div class="mdl-card__supporting-text">
		<?php echo form_open(site_url('admin').'/timeblocks/add/'.$schedule_id); ?>
			<div><label for="time_blocks">Time blocks (hh:mm am - hh:mm pm)</label><br>
		    <textarea id="time_blocks" name="time_blocks" cols="60" rows="3"><?php echo set_value('time_blocks'); ?></textarea></div>
		    <div><input type="submit" value="Submit" /></div>
		</form>
	</div>
  </div>
</section>
