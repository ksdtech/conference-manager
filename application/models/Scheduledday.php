<?php
class ScheduledDay extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function create($post_data) {
		$data['schedule_id']          = $post_data['schedule'];
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
		->join('schedules', 'schedules.id=scheduled_days.schedule_id')
		->join('resource_calendars', 'resource_calendars.id=schedules.resource_calendar_id')
		->order_by('resource_calendars.name, schedules.name')
		->get('scheduled_days')
		->result_array();	
	}
	
	public function schedule_times($schedule_day_id) {
		$scheduled_day = $this->db->select('scheduled_days.*, resource_calendars.id AS calendar_id, resource_calendars.name as calendar_name, schedules.name as schedule_name')
		->where('scheduled_days.id', $schedule_day_id)
		->limit(1)
		->join('schedules', 'schedules.id=scheduled_days.schedule_id')
		->join('resource_calendars', 'resource_calendars.id=schedules.resource_calendar_id')
		->get('scheduled_days')
		->row_array();
				
		$timeblocks = $this->db->select('schedule_times.*')
		->where('scheduled_days.id', $schedule_day_id)
		->join('scheduled_days', 'scheduled_days.schedule_id=schedule_times.schedule_id')
		->order_by('time_start')
		->get('schedule_times')
		->result('Timeblock');
		
		$ymd = explode('-', $scheduled_day['schedule_date']);
		return array(
			'timeblocks' => $timeblocks,
			'schedule' => array(
				'id'   => $scheduled_day['schedule_id'],
				'name' => $scheduled_day['schedule_name']
			),
			'calendar' => array(
				'id'   => $scheduled_day['calendar_id'],
				'name' => $scheduled_day['calendar_name']
			),
			'year'  => intval($ymd[0]),
			'month' => intval($ymd[1]),
			'day'   => intval($ymd[2])
		);
	}
}