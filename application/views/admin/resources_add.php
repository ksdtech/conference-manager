<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
		<h1>Add Resource</h1>
	</div>
	<?php echo validation_errors(); ?>

    <div class="mdl-card__supporting-text">
	<?php echo form_open(site_url('admin').'/resources/add'); ?>
		<div><label for="name">Resource name</label><br>
    	<input type="text" id="name" name="name" size="40" value="<?php echo set_value('name'); ?>"></div>
		<div><label for="description" cols="60" rows="3">Description</label><br>
    	<textarea id="description" name="description"><?php echo set_value('description'); ?></textarea></div>
    	<div><label for="location" cols="60" rows="3">Description</label><br>
    	<textarea id="location" name="location"><?php echo set_value('location'); ?></textarea></div>
    	<div><input type="submit" value="Submit" /></div>
	</form>
	<a href="<?php echo site_url('admin').'/resources/index'; ?>">Cancel</a>
	</div>
  </div>
</section>
