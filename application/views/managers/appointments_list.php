<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>All Booked Appointments</h1>
    </div>
    <div class="mdl-card__supporting-text">
    	<?php echo form_open(site_url().'/appointments/edit_all'); ?>
    	<input type = "hidden" name = "user_id" value = <?php echo $auth_user_id?>>
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
       			<td><input type="submit" value = "Unbook" name = "unbook_<?php echo $reservation->id; ?>" id="check_group" value = <?php echo $reservation->id ?>;</td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_start_ampm(); ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $reservation->time_end_ampm(); ?></td>
          </tr>
        </tbody>
        </table> 
           <?php } ?>
        </form>
    </div>
  </div>
</section>

