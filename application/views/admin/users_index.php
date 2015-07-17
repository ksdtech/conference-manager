<section class="section--center mdl-grid">
  <div class="mdl-card">
  <?php $attributes = array('id' => 'user_form'); ?>
  <?php echo form_open(site_url('admin').'/users/action_perform', $attributes); ?>
  <input type="hidden" id="selected_action" name="selected_action" value=""></input>
    <div class="mdl-card__title">
    <ul>
    <li><a href="<?php echo site_url('admin').'/users/add'; ?>">Add</a></li>
    <li><a href="#" id="action_delete">Delete</a></li>
    </ul>
    </div>
    <div class="mdl-card__supporting-text">
      <table class="mdl-data-table">
        <thead>
          <tr>
          	<th><input type="checkbox" id="check_group" /></th>
            <th>Level</th>
            <th class="mdl-data-table__cell--non-numeric">First name</th>
            <th class="mdl-data-table__cell--non-numeric">Last name</th>
            <th class="mdl-data-table__cell--non-numeric">Email</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user) { ?>
          <tr>
          	<td><input type="checkbox" class="check_item" id="check_<?php echo $user['user_id']; ?>" /></td>
            <td><?php echo $user['user_level']; ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $user['first_name']; ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $user['last_name']; ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $user['user_email']; ?></td>
            <td><a href="<?php echo site_url('admin').'/users/edit/'.$user['user_id']; ?>">Edit</a></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </form>
  </div>
</section>

<section class="section--center mdl-grid">
  <div class="mdl-card">
    <?php echo form_open_multipart(site_url('admin').'/users/upload_users'); ?>
	  <div><label for="userfile">CSV file to upload</label><br>
      <input type="file" id="userfile" name="userfile" size="20" />
      <div><input type="submit" value="Submit" /></div>
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
		$('#user_form').submit();
	});
});
</script>