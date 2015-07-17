<?php
class ScheduledDay extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function create($post_data) {
		$data['schedule_id']          = $post_data['schedule'];
		$data['resource_calendar_id'] = $post_data['calendar'];
		$data['schedule_date']        = $post_data['date'];
	
		$scheduled_day_id = FALSE;
	
		$this->db->trans_start();
		$this->db->insert('scheduled_days', $data);
		if ($this->db->affected_rows() == 1) {
			$schedule_day_id = $this->db->insert_id();
		}
		$this->db->trans_complete();
	
		return $schedule_day_id;
	}
	
	public function all_by_date($date) {
		return $this->db->select('scheduled_days.*, resource_calendars.name as calendar_name, schedules.name as schedule_name')
		->where('schedule_date', $date)
		->join('resource_calendars', 'resource_calendars.id=scheduled_days.resource_calendar_id')
		->join('schedules', 'schedules.id=scheduled_days.schedule_id')
		->order_by('resource_calendars.name, schedules.name')
		->get('scheduled_days')
		->result_array();	
	}
}