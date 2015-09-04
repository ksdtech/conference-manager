<section class="section--center mdl-grid">
  <div class="mdl-card">
    <div class="mdl-card__title">
    </div>
    <div class="mdl-card__supporting-text">
    	<?php echo form_open(site_url().'/resources'); ?>
      <input type="hidden" id="base_url" name= "base_url" value="<?php echo $base_url; ?>">
    	<input type="hidden" id="user_id" name= "user_id" value="<?php echo $user_id; ?>">
      	<table class="mdl-data-table">
        <thead>
          <tr>
            <th class="mdl-data-table__cell--non-nume.ric">Resource Name</th>
             <th class="mdl-data-table__cell--non-nume.ric">Appointment Type</th>
             <th class="mdl-data-table__cell--non-numeric"># of Appointments Booked</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($resources as $resource) { ?>
          <tr>
          	<td><input type="radio" name="selected_resource" value="<?php echo $resource->id; ?>" /><span><?php echo $resource->name; ?></span></td>
          	<td><?php echo form_dropdown("calendar_" . $resource->id, $resource->get_resource_calendar_options($resource->id), set_value('resource_calendar_id'), 
              'data-resource-id="'. $resource->id . '" class="resource_calendar_select"'); ?></td>
          	<td><span id="num_booked_<?php echo $resource->id; ?>"></span></td>
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
	$('.resource_calendar_select').on('change', function() {
		var resource_id = $(this).data('resource-id');
		var resource_calendar_id = $(this).val();
		var user_id = $('#user_id').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/booked/' + resource_id + '/' + resource_calendar_id + '/'+  user_id;
		$.ajax(url, 
			{ 
				dataType: 'text', 
				success: function(data, textStatus, jqXHR) {
					$('#num_booked_' + resource_id).text(data);
				}
			});		
	});
	
});
</script>