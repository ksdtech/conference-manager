<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
      <h1>Calendar </h1>
    </div>
   	<div>
   	  <a href= <?php echo site_url('managers').'/appointments/edit_all/' . $resource_id ?></a> View All Booked Appointments</a><br>
	  <?php echo $calendar; ?>
	</div>
  </div>
</section>