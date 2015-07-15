<?php

class ResourceCalendar extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function create($post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		$resource_calendar_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('resource_calendars', $data);
		if ($this->db->affected_rows() == 1) {
			$resource_calendar_id = $this->db->insert_id();
		}
		$this->db->trans_complete();

		return $resource_calendar_id;
	}
	
	
	public function read($resource_calendar_id) {
		return $this->db->where('id', $resource_calendar_id)
		->limit(1)
		->get('resource_calendars')
		->row_array();
	}
	
	public function all() {
		return $this->db->order_by('name')->get('resource_calendars')
		->result_array();
	}
	
	public function update($resource_calendar_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		return $this->db->where('id', $resource_calendar_id)
		->update('resource_calendars', $data);
	}
	
	public function delete($resource_calendar_id) {
		$result = FALSE;
		
		$this->db->trans_start();
		$this->db->where('resource_calendar_id', $resource_calendar_id)->delete('appointment_days');
		$result = $this->db->where('id', $resource_calendar_id)->delete('resource_calendars');
		$this->db->trans_complete();
		
		return $result;
	}
	
	public function select_options() {
		$options = array();
		$calendars = $this->all();
		foreach ($calendars as $calendar) {
			$options[''.$calendar['id']] = $calendar['name'];
		}
		return $options;
	}
}
