<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Appointment Times on <?php echo $month.'/'.$day.'/'.$year; ?></h1>
    </div>
    <div class="mdl-card__supporting-text">
    	<?php echo form_open(site_url().'/appointments/edit/'.$resource_id.'/'.$year.'/'.$month.'/'.$day); ?>
      	<table class="mdl-data-table">
        <thead>
          <tr>
            <th class="mdl-data-table__cell--non-numeric">Booked</th>
            <th class="mdl-data-table__cell--non-numeric">Start Time</th>
            <th class="mdl-data-table__cell--non-numeric">Finish Time</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($reservations as $reservation) { ?>
        	if ($reservation->is_booked())
        	{
          <tr>
          	<td><input type="radio" name = "reservation_booking" id="check_group" value = "Booked"; ?>/></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_start_ampm(); ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_end_ampm(); ?></td>
          </tr>
          }
        <?php } ?>
        </tbody>
        </table>
        	<div><input type="submit" value="Submit" /></div>
        </form>
    </div>
  </div>
</section>
