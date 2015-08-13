<?php

class Schedule extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function create($resource_calendar_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		$data['resource_calendar_id'] = $resource_calendar_id;
		$schedule_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('schedules', $data);
		if ($this->db->affected_rows() == 1) {
			$schedule_id = $this->db->insert_id();
		}
		$this->db->trans_complete();

		return $schedule_id;
	}
	
	public function read($schedule_id) {
		return $this->db->select('schedules.*, resource_calendars.interval_in_minutes, resource_calendars.duration_in_minutes')
		->join('resource_calendars', 'resource_calendars.id=schedules.resource_calendar_id')
		->where('schedules.id', $schedule_id)
		->limit(1)
		->get('schedules')
		->row_array();
	}
	
	public function all($resource_calendar_id) {
		return $this->db->order_by('name')->where('resource_calendar_id', $resource_calendar_id)->get('schedules')
		->result('Schedule');
	}
	
	public function update($schedule_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		return $this->db->where('id', $schedule_id)
		->update('schedules', $data);
	}
	
	public function delete($schedule_id) {
		$result = FALSE;
		
		$this->db->trans_start();
		$this->db->where('schedule_id',  $schedule_id)->delete('appointment_days');
		$this->db->where('schedule_id',  $schedule_id)->delete('schedule_times');
		$result = $this->db->where('id', $schedule_id)->delete('schedules');
		$this->db->trans_complete();
		
		return $result;
	}
	
	public function select_options() {
		$options = array();
		$schedules = $this->all();
		foreach ($schedules as $schedule) {
			$options[''.$schedule['id']] = $schedule['name'];
		}
		return $options;
	}
}
