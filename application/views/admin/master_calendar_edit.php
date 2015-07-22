<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Appointment Times on <?php echo $month.'/'.$day.'/'.$year; ?> for Calendar <?php echo $calendar['name']; ?></h1>
    </div>
    <div class="mdl-card__supporting-text">
      	<table class="mdl-data-table">
        <thead>
          <tr>
            <th class="mdl-data-table__cell--non-numeric">Start Time</th>
            <th class="mdl-data-table__cell--non-numeric">Finish Time</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($timeblocks as $timeblock) { ?>
          <tr>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $timeblock->time_start_ampm() ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $timeblock->time_end_ampm(); ?></td>
          </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
  </div>
</section>
