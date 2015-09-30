<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
      PowerSchool Guardian Access
    </div>
    <div class="mdl-card__supporting-text">
      <p><?php echo ($logged_in ? "Yes, you're logged in." : "No, not logged in"); ?></p>
      <p>Query String</p>
      <?php echo var_dump($get); ?>
      <p>Post Data</p>
      <?php echo var_dump($post); ?>
      <p>Authentication Attributes</p>
      <?php echo var_dump($attributes); ?>
      <?php if ($logged_in) { ?> 
      <p><a href="<?php site_url('psguardians').'/logout' ?>">Log out</a></p>
      <?php } ?>
    </div>
  </div>
</section>
