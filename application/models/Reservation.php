
<?php

function time_compare($a, $b) {
	if ($a->time_start < $b->time_start) {
		return -1;
	}
	return 1;
}

class Reservation extends TimeBlock {

	public function __construct() {
		parent::__construct();
	}

	public function res_unique() {
		return implode('-', array($this->resource_id, $this->schedule_date, $this->time_start));
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
	public function all_by_date_for_calendar($resource_id,$schedule_date, $resource_calendar_id, $User_id)
	{
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
				
		$reservations = $this->db->where('resource_id', $resource_id)
		->get('reservations')
		->result('Reservation');
		$numReservations = count($reservations);
		$res_uniques = array("");
		
		for ($i = 0; $i < $numReservations; $i++) {
			array_push($res_uniques, $reservations[$i]->res_unique());
		}
		
		$timeSlots = $this->db->select("c.resource_id, c.resource_calendar_id, d.schedule_date, t.time_start, t.time_end, r.default_location AS location")
		->join('schedules s', 's.resource_calendar_id=c.resource_calendar_id')
		->join('scheduled_days d', 'd.schedule_id=s.id')
		->join('resources r', 'r.id=c.resource_id')
		->where('d.schedule_date', $schedule_date)
		->where('c.resource_id', $resource_id)
		->where('c.resource_calendar_id', $resource_calendar_id)
		->where_not_in("CONCAT_WS('-', c.resource_id, d.schedule_date, t.time_start)", $res_uniques)
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
		
		
		$current_user_reservations = $this->db->get_where('reservations', array('resource_id' => $resource_id, 'user_id' => $User_id, 'resource_calendar_id' => $resource_calendar_id))
		->result('Reservation');
		
		//Only get the open reservations and the reservations for the current user.
	
		for ($i = 0; $i < count($current_user_reservations); $i++) {
			array_push($timeSlots, $current_user_reservations[$i]);
		}
		usort($timeSlots, 'time_compare');
		
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
		
		$reservations = $this->db->where('resource_id', $resource_id)
		->get('reservations')
		->result('Reservation');
		$res_uniques = array("");
		$numReservations = count($reservations);
		for ($i = 0; $i < $numReservations; $i++) {
			array_push($res_uniques, $reservations[$i]->res_unique());
		}
		
		$timeSlots = $this->db->select("c.resource_id, c.resource_calendar_id, d.schedule_date, t.time_start, t.time_end, r.default_location AS location")
		->join('schedules s', 's.resource_calendar_id=c.resource_calendar_id')
		->join('scheduled_days d', 'd.schedule_id=s.id')
		->join('resources r', 'r.id=c.resource_id')
		->where('d.schedule_date', $schedule_date)
		->where('c.resource_id', $resource_id)
		->where_not_in("CONCAT_WS('-', c.resource_id, d.schedule_date, t.time_start)", $res_uniques)
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
		for ($i = 0; $i < $numReservations; $i++) {
			array_push($timeSlots, $reservations[$i]);
		}
		usort($timeSlots, 'time_compare');
		
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
		$reservation_id = 0;
		if (isset($data['id'])) {
			$reservation_id = $data['id'];
		} else {
			$res_query = $this->db->where(array('resource_id' => $data['resource_id'], 'schedule_date' => $data['schedule_date'], 'time_start' => $data['time_start']))
			->limit(1)
			->get('reservations');
			//die(var_dump(count($res)));
			if ($res_query->num_rows() == 1)
			{
				$reservation_id = $res_query->row_array()['id'];
			}
		}
		
		if ($reservation_id != 0) {
			unset($data['resource_calendar_id']);
			unset($data['schedule_data']);
			unset($data['time_start']);
			$this->db->where('id', $reservation_id)->update('reservations', $data);
			return $reservation_id;
		}
		else 
		{
		
			$this->db->insert('reservations', $data);
			return $this->db->insert_id();
			//die(var_dump("a " . $data['time_end']));
		}
		
	}
	
	
	public function form_id() {
		if ($this->id != 0) {
			return sprintf("%d", $this->id);
		}
		return $this->res_unique().'-'.$this->time_end;
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