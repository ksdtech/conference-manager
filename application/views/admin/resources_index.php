<section class="section--center mdl-grid">
  <div class="mdl-card">
  <?php $attributes = array('id' => 'resource_form'); ?>
  <?php form_open(site_url('admin').'/resources/action_perform', $attributes); ?>
  <input type="hidden" id="selected_action" name="selected_action" value=""></input>
    <div class="mdl-card__title">
    <ul>
    <li><a href="<?php echo site_url('admin').'/resources/add'; ?>">Add</a></li>
    <li><a href="#" id="action_delete">Delete</a></li>
    </ul>
    </div>
    <div class="mdl-card__supporting-text">
      <table class="mdl-data-table">
        <thead>
          <tr>
          	<th><input type="checkbox" id="check_group" /></th>
            <th class="mdl-data-table__cell--non-numeric">Name</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($resources as $resource) { ?>
          <tr>
          	<td><input type="checkbox" class="check_item" id="check_<?php echo $resource->id; ?>" /></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $resource->name; ?></td>
            <td><a href="<?php echo site_url('admin').'/resources/edit/'.$resource->id; ?>">Edit</a></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </form>
  </div>
</section>

<script>
$().ready(function() {
	$('#check_group').on('click', function() {
		if ($('#check_group').prop('checked')) {
			$('.check_item').prop('checked', 'checked');
		} else {
			$('.check_item').prop('checked', false);
		}
	});
	
	$('#action_delete').on('click', function() {
		$('#selected_action').val('delete');
		$('#resource_form').submit();
	});
});
</script>
