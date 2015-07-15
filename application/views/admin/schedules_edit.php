<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
		<h1>Edit Schedule</h1>
	</div>
	<?php echo validation_errors(); ?>

    <div class="mdl-card__supporting-text">
	<?php echo form_open(site_url('admin').'/schedules/edit/'.$schedule['id']); ?>
		<div><label for="name">First name</label><br>
    	<input type="text" id="name" name="name" value="<?php echo set_value('name', $schedule['name']); ?>"></div>
		<div><label for="description">Last name</label><br>
    	<textarea id="description" name="description"><?php echo set_value('description', $schedule['description']); ?></textarea></div>
    	<div><input type="submit" value="Submit" /></div>
		<div><label for="interval_in_minutes">Interval between appointments (minutes)</label><br>
    	<input type="text" id="interval_in_minutes" name="interval_in_minutes" value="<?php echo set_value('interval_in_minutes', $schedule['interval_in_minutes']); ?>"></div>
		<div><label for="duration_in_minutes">Duration of each appointment (minutes)</label><br>
    	<input type="text" id="duration_in_minutes" name="duration_in_minutes" value="<?php echo set_value('duration_in_minutes', $schedule['duration_in_minutes']); ?>"></div>
	</form>
	<a href="<?php echo site_url('admin').'/schedules/index'; ?>">Cancel</a>
	</div>
  </div>
</section>

<section class="section--center mdl-grid">
  <div class="mdl-card">
  	<?php $attributes = array('id' => 'user_form'); ?>
    <div class="mdl-card__title">
	  <h1>Appointment Time Blocks</h1>
	  <ul>
    	<li><a href="<?php echo site_url('admin').'/schedules/'.$schedule['id'].'/timeblocks/add'; ?>">Add</a></li>
    	<li><a href="#" id="action_delete">Delete</a></li>
      </ul>
    </div>
    <div class="mdl-card__supporting-text">
      <?php $attributes = array('id' => 'timeblocks_form'); ?>
  	  <?php form_open(site_url('admin').'/timeblocks/action_perform', $attributes); ?>
  	  <input type="hidden" id="schedule_id" name="schedule_id" value="<?php echo $schedule['id']; ?>"></input>
  	  <input type="hidden" id="selected_action" name="selected_action" value=""></input>
      	<table class="mdl-data-table">
        <thead>
          <tr>
          	<th><input type="checkbox" id="check_group" /></th>
            <th class="mdl-data-table__cell--non-numeric">Start Time</th>
            <th class="mdl-data-table__cell--non-numeric">Finish Time</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($timeblocks as $timeblock) { ?>
          <tr>
          	<td><input type="checkbox" class="check_item" id="check_<?php echo $timeblock['id']; ?>" /></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $timeblock->start_time_s() ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $timeblock->finish_time_s(); ?></td>
          </tr>
        <?php } ?>
        </tbody>
        </table>
      </form>
    </div>
  </div>
</section>

<script>
$().ready(function() {
	$('#check_group').on('click', function() {
		if ($('#check_group').prop('checked')) {
			$('.check_item').prop('checked', 'checked');
		} else {
			$('.check_item').prop('checked', false);
		}
	});
	
	$('#action_delete').on('click', function() {
		$('#selected_action').val('delete');
		$('#timeblocks_form').submit();
	});
});
</script>