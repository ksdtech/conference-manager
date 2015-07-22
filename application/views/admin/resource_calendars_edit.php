<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
		<h1>Edit Resource Calendar</h1>
	</div>
	<?php echo validation_errors(); ?>

    <div class="mdl-card__supporting-text">
	<?php echo form_open(site_url('admin').'/resourcecalendars/edit/'.$calendar['id']); ?>
		<div><label for="name">Calendar name</label><br>
    	<input type="text" id="name" name="name" size="40" value="<?php echo set_value('name', $calendar['name']); ?>"></div>
		<div><label for="description" cols="60" rows="3">Description</label><br>
    	<textarea id="description" name="description"><?php echo set_value('description', $calendar['description']); ?></textarea></div>
    	<div><input type="submit" value="Submit" /></div>
	</form>
	<a href="<?php echo site_url('admin').'/resourcecalendars/index'; ?>">Cancel</a>
	</div>
  </div>
</section>

<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
	  <h1>Calendar Resources</h1>
    </div>
    <div class="mdl-card__supporting-text">
  	  <?php echo form_open(site_url('admin').'/resourcecalendars/update_calendar_resources/'.$calendar['id']); ?>
  	  	<?php foreach($resources as $resource) {
  	  		if ($resource->is_on_calendar($calendar['id'])) { ?>
  	  		<input type="hidden" class="orig_item" id="orig_item_<?php echo $resource->id; ?>" name="orig_item_<?php echo $resource->id; ?>" value="<?php echo $resource->id; ?>" />
  	  	<?php } } ?>
      	<table class="mdl-data-table">
        <thead>
          <tr>
          	<th><?php echo form_dropdown('group', $groups, '', 'id="check_group"'); ?></th>
            <th class="mdl-data-table__cell--non-numeric">Name</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($resources as $resource) { ?>
          <tr>
          	<td><input type="checkbox" class="check_item <?php echo $resource->group_class_names(); ?>" id="item_<?php echo $resource->id; ?>" name="item_<?php echo $resource->id; ?>" value="1"
          		/></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $resource->name; ?></td>
          </tr>
        <?php } ?>
        </tbody>
        </table>
        <div><input type="submit" value="Submit" /></div>
        
      </form>
    </div>
  </div>
</section>

<script>
$().ready(function() {
	$('.orig_item').each(function(i, el) {
		var resource_id = $(el).val();
		$('#item_' + resource_id).prop('checked', 'checked');
	});
	$('#check_group').on('change', function() {
		var selected_group = $('#check_group').val();
		if (selected_group == "all")
		{
			$('.check_item').prop('checked', 'checked');
		}
		else if (selected_group == "none")
		{
			$('.check_item').prop('checked', false);
		}
		else
		{
			$('.check_item').prop('checked', false);
			$('.group_' + selected_group).prop('checked', 'checked');
		}
	});
	
	$('#action_delete').on('click', function() {
		$('#selected_action').val('delete');
		$('#timeblocks_form').submit();
	});
});
</script>