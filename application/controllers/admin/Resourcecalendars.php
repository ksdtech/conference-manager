<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResourceCalendars extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/resource_calendars
	 */
	public function index() {
		
		if ($this->require_role('admin')) {
			$this->load->model('ResourceCalendar', 'calendar');
			$this->load->helper(array('form', 'url'));
	
			$data = array('calendars' => $this->calendar->all());
			$this->load->template('admin/resource_calendars_index', $data);	
		}
	}
	
	public function add() {
		
		if ($this->require_role('admin')) {
			$add_calendar_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|is_unique[resource_calendars.name]'
					),
					array(
							'field' => 'interval_in_minutes',
							'label' => 'Interval',
							'rules' => 'trim|required|numeric|greater_than_equal_to[5]|less_than_equal_to[120]'
					
					),
					array(
							'field' => 'duration_in_minutes',
							'label' => 'Duration',
							'rules' => 'trim|required|numeric|less_than_equal_to_field[interval_in_minutes]'
					)
			);
			
			$this->load->model('Timeblock', 'timeblock');
			$this->load->model('ResourceCalendar', 'calendar');
			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($add_calendar_rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$data = array(
					'interval_options' => $this->timeblock->get_duration_options('Select'),
					'duration_options' => $this->timeblock->get_duration_options('Same as interval')
				);		
				$this->load->template('admin/resource_calendars_add', $data);
			} else {
				$resource_calendar_id = $this->calendar->create($this->input->post());
				if ($resource_calendar_id !== FALSE) {
					$this->session->set_flashdata('info', 'Resource calendar '.$resource_calendar_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'Resource calendar '.$resource_calendar_id.' could not be created.');
				}
				redirect(site_url('admin').'/resourcecalendars/index');
				
			}
		}
	}
	
	public function edit($resource_calendar_id) {
		
		if ($this->require_role('admin')) {	
			$edit_calendar_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|edit_unique[resource_calendars.name.id.'.$resource_calendar_id.']'
					),
					array(
							'field' => 'interval_in_minutes',
							'label' => 'Interval',
							'rules' => 'trim|required|numeric|greater_than_equal_to[5]|less_than_equal_to[120]'
					
					),
					array(
							'field' => 'duration_in_minutes',
							'label' => 'Duration',
							'rules' => 'trim|required|numeric|less_than_equal_to_field[interval_in_minutes]'
					)
			);
			
			$this->load->model('Timeblock', 'timeblock');
			$this->load->model('ResourceCalendar', 'calendar');
			$this->load->model('Resource', 'resource');
			$this->load->model('Schedule', 'schedule');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($edit_calendar_rules);

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'calendar' => $this->calendar->read($resource_calendar_id), 
					'groups' => $this->group_options(), 
					'resources' => $this->resource->all(),
					'schedules' => $this->schedule->all($resource_calendar_id),
					'interval_options' => $this->timeblock->get_duration_options('Select'),
					'duration_options' => $this->timeblock->get_duration_options('Same as interval')
				);
				
				$this->load->template('admin/resource_calendars_edit', $data);
				
			} else {
				if ($this->resource_calendar->update($resource_calendar_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'Resource calendar '.$resource_calendar_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'Resource calendar '.$resource_calendar_id.' could not be updated.');
				}
				redirect(site_url('admin').'/resourcecalendars/index');

			}
		}
	}
	
	private function group_options()
	{
	   $groups = $this->db->order_by("name")->get("resource_groups")->result_array();
	   $options = array("all" => "All", "none" => "None");
	   foreach ($groups as $group) {
		   $options[''.$group['id']] = $group['name'];
	   }
	   return $options;
	}
	
	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function update_calendar_schedules($resource_calendar_id) {
			if ($this->require_role('admin')) {
			$post_data = $this->input->post();
			if ($post_data['add_schedule'])
			{
				redirect(site_url('admin').'/schedules/add/'.$resource_calendar_id);
			}
		}
	}
	
	public function update_calendar_resources($resource_calendar_id) {
		if ($this->require_role('admin')) {
			$post_data = $this->input->post();
			
			// array('submit' => 'Submit', 'orig_item_35' => '35, 'item_35' => '1', 'item_55' => '1');
			$orig_items = array();
			$new_items = array();
			foreach(array_keys($post_data) as $key) {
				if (preg_match('/(orig_)?item_(\d+)/', $key, $matches)) {
					$resource_id = $matches[2];
					if ($matches[1] == 'orig_') {
						array_push($orig_items, $resource_id);
					} else {
						array_push($new_items, $resource_id);
					}
				}
			}
			
			$to_delete = array_diff($orig_items, $new_items);
			$to_add = array_diff($new_items, $orig_items);
			
			if (count($to_delete) > 0) {
				$this->db->where('resource_calendar_id', $resource_calendar_id)
				->where_in('resource_id', $to_delete)
				->delete('calendar_resources');
			}
			if (count($to_add) > 0) {
				foreach($to_add as $resource_id) {
					$this->db->insert('calendar_resources', array('resource_id' => $resource_id,
							'resource_calendar_id' => $resource_calendar_id
					));
				}
			}
			$this->session->set_flashdata('info', 'Added '.count($to_add).' items, deleted '.count($to_delete).' items');
			redirect(site_url('admin').'/resourcecalendars/edit/'.$resource_calendar_id);
		}
	}
	
	public function delete($resource_calendar_id) {
		
		if ($this->require_role('admin')) {
			$this->load->model('ResourceCalendar', 'calendar');
			if ($this->calendar->delete($resource_calendar_id)) {
				$this->session->set_flashdata('info', 'Resource calendar '.$resource_calendar_id.' was deleted.');
			} else {
				$this->session->set_flashdata('error', 'Resource calendar '.$resource_calendar_id.' could not be deleted.');
			}
			redirect(site_url('admin').'/resourcecalendars/index');
		}
	}
}
