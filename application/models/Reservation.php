<?php

class Reservation extends TimeBlock {

	public function __construct() {
		parent::__construct();
	}


/*
-- status is
--   'A' - available (as master schedule)
--   'M' - available (added by master)
--   'U' - unavailable
CREATE TABLE IF NOT EXISTS `reservations` (
		`id` int(10) unsigned NOT NULL auto_increment,
		`resource_id` int(10) unsigned NOT NULL,
		`resource_calendar_id` int(10) unsigned NOT NULL,
		`schedule_date` date NOT NULL,
		`time_start` varchar(8) NOT NULL,
		`time_end` varchar(8) NOT NULL,
		`status` varchar(1) NOT NULL DEFAULT 'A',
		`user_id` int(10) unsigned DEFAULT NULL,
		`location` varchar(40) DEFAULT NULL,
*/
	public function all_by_date($resource_id, $schedule_date) {
		/* Get resource_calendar ids for this resource from the calendar_resources table
		 Get schedule_id for this resource from scheduled_days table for on this date
		 with any of the resource calendar ids.
		 Then get slots from master calendar(s) from schedule_times table

		"SELECT d.schedule_date, t.time_start, t.time_end 
		FROM schedule_times t, calendar_resources c
		INNER JOIN scheduled_days d ON d.resource_calendar_id=c.resource_calendar_id
		WHERE d.schedule_date='$schedule_date'
		AND c.resource_id='$resource_id'"
		*/
			
		$timeSlots = $this->db->select('c.resource_id, d.resource_calendar_id, d.schedule_date, t.time_start, t.time_end, r.default_location AS location')
		->join('scheduled_days d', 'd.resource_calendar_id=c.resource_calendar_id')
		->join('resources r', 'r.id=c.resource_id')
		->where('d.schedule_date', $schedule_date)
		->where('c.resource_id', $resource_id)
		->order_by('d.schedule_date, t.time_start, t.time_end')
		->get('schedule_times t, calendar_resources c')
		->result("Reservation");
		
		/* Now pad with default attributes */
		$numSlots = count($timeSlots);
		for ($i = 0; $i < $numSlots; $i++) {
			$timeSlots[$i]->id = 0;
			$timeSlots[$i]->status = 'A';
			$timeSlots[$i]->user_id = null;
		}
		
		return $timeSlots;
		
		/*
		 * array(6) { [0]=> array(9) { ["resource_id"]=> string(1) "2" ["resource_calendar_id"]=> string(1) "1" 
		 * ["schedule_date"]=> string(10) "2015-07-01" 
		 * ["time_start"]=> string(8) "00:00:00" ["time_end"]=> string(8) "00:20:00" 
		 * ["location"]=> string(7) "Room 22" 
		 * ["id"]=> int(0) 
		 * ["status"]=> string(1) "A" 
		 * ["user_id"]=> NULL } }
		 *
		 *
		 * Then get adds and deletes for this resource from reservations table
		 */
		
		
	}
	public function user_name()
	{
		$user_id = $this->user_id;
		if ($user_id) {
			$user =  $this->db->where('user_id', $user_id)
			->limit(1)
			->get('users')
			->row_array();
			$user_full_name = $user["first_name"] . " " . $user["last_name"];
			
			return $user_full_name;
		}
		
		
	}
	public function create_or_update($data)
	{
		$res = $this->db->where(array('resource_calendar_id' => $data['resource_calendar_id'], 'schedule_date' => $data['schedule_date'], 'time_start' => $data['time_start']))
		->limit(1)
		->get('reservations')
		->row_array();
		
		if ($this -> db ->num_rows() == 1)
		{
			$this->db->update('reservations', $data);
		}
		else 
		{
			$this->db->insert('reservations', $data);
		}
		
		return $res['id'];
	}
	
	
	public function form_id() {
		if ($this->id != 0) {
			return sprintf("%d", $this->id);
		}
		return sprintf("%d_%s", $this->resource_calendar_id, $this->time_start);
	}
	
	public function is_booked()
	{
		return $this->user_id != null;
	}
	
	public function is_available()
	{
		return $this->status != 'U';
	}
}