<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Available Appointment Times on <?php echo $month.'/'.$day.'/'.$year; ?></h1>
    </div>
    <div class="mdl-card__supporting-text">
    	<?php echo form_open(site_url().'/appointments/edit/'.$resource_id.'/' . $resource_calendar_id . '/' .$year.'/'.$month.'/'.$day); ?>
    	<input type = "hidden" name = "user_id" value = 41883951>
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
          <tr>
       		<?php if (! $reservation->is_booked()){ ?>
       			<?php if ($user_booked_appointment == false){?>
          	<td><input type="submit" value = "Book" name = "book_<?php echo $reservation->form_id(); ?>" id="check_group" value = <?php echo $reservation->id ?>;</td>
       				<?php }else{?>
       				<td><input type="submit" disabled = true value = "Book" name = "book_<?php echo $reservation->form_id(); ?>" id="check_group" value = <?php echo $reservation->id ?>;</td>
       				<?php } ?>
       		<?php }else{?>
       			<td><input type="submit" value = "Unbook" name = "unbook_<?php echo $reservation->id; ?>" id="check_group" value = <?php echo $reservation->id ?>;</td>
       		<?php } ?>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_start_ampm(); ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_end_ampm(); ?></td>
          </tr>
        <?php } ?>
        </tbody>
        </table>
        
        </form>
    </div>
  </div>
</section>

