<?php

class Schedule extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function create($post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		$data['interval_in_minutes'] = $post_data['interval_in_minutes'];
		$data['duration_in_minutes'] = $post_data['duration_in_minutes'];
		
		$schedule_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('schedules', $data);
		if ($this->db->affected_rows() == 1) {
			$schedule_id = $this->db->insert_id();
		}
		$this->db->trans_complete();

		return $schedule_id;
	}
	
	public function add_time_block($schedule_id, $post_data) {
		$data['schedule_id']         = $schedule_id;
		$data['start_hour']          = $post_data['start_hour'];
		$data['start_minute']        = $post_data['start_minute'];
		$data['duration_in_minutes'] = $post_data['duration_in_minutes'];
		
		$timeblock_id = FALSE;
		
		$this->db->trans_start();
		$this->db->insert('time_blocks', $data);
		if ($this->db->affected_rows() == 1) {
			$timeblock_id = $this->db->insert_id();
		}
		$this->db->trans_complete();
		
		return $timeblock_id;
	}
	
	public function read($schedule_id) {
		$schedule = $this->db->where('id', $schedule_id)
		->limit(1)
		->get('schedules')
		->row_array();
		$timeblocks = $this->db->where('schedule_id', $schedule_id)
		->get('time_blocks')
		->order_by('start_hour, start_minute')
		->result_array();
		return array('schedule' => $schedule, 'timeblocks' => $timeblocks);
	}
	
	public function all() {
		return $this->db->get('schedules')
		->order_by('name')
		->result_array();
	}
	
	public function update($schedule_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		$data['interval_in_minutes'] = $post_data['interval_in_minutes'];
		$data['duration_in_minutes'] = $post_data['duration_in_minutes'];
		
		return $this->db->where('id', $schedule_id)
		->update('schedules', $data);
	}
	
	public function delete($schedule_id) {
		$result = FALSE;
		
		$this->db->trans_start();
		$this->db->where('schedule_id', $schedule_id)->delete('appointment_days');
		$this->db->where('schedule_id', $schedule_id)->delete('time_blocks');
		$result = $this->db->where('id', $schedule_id)->delete('schedules');
		$this->db->trans_complete();
		
		return $result;
	}
}
