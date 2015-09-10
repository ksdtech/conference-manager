<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
      <h1>Choose a Resource</h1>
    </div>

    <div class="mdl-card__supporting-text">
      <ul>
      <?php foreach($options as $id => $text) { if ($id != '') { ?>
      <li><a href="<?php echo site_url('managers').'/appointments/index/'.$id; ?>"><?php echo $text; ?></a></li>
      <?php } } ?>
    </div>
  </div>
</section>
