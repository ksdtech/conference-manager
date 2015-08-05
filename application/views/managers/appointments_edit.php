<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Appointment Times on <?php echo $month.'/'.$day.'/'.$year; ?></h1>
    </div>
    <div class="mdl-card__supporting-text">
    	<?php echo form_open(site_url('managers').'/appointments/edit/'.$resource_id.'/'.$year.'/'.$month.'/'.$day); ?>
      	<table class="mdl-data-table">
        <thead>
          <tr>
            <th class="mdl-data-table__cell--non-numeric">Delete</th>
            <th class="mdl-data-table__cell--non-numeric">Booked</th>
            <th class="mdl-data-table__cell--non-numeric">Appointment With</th>
            <th class="mdl-data-table__cell--non-numeric">Start Time</th>
            <th class="mdl-data-table__cell--non-numeric">Finish Time</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($reservations as $reservation) { ?>
          <tr>
          	<th><input type="checkbox" name="delete_<?php echo $reservation->form_id(); ?>" value = "Delete"  /></th>
          	<th><input type="checkbox" id="check_group" value = "Booked" <?php echo $reservation->is_booked() ? 'checked="checked"' : ''; ?>/></th>
          	 <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->user_name(); ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_start_ampm(); ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_end_ampm(); ?></td>
          </tr>
        <?php } ?>
        </tbody>
        </table>
        	<div><input type="submit" value="Submit" /></div>
        </form>
    </div>
  </div>
</section>
