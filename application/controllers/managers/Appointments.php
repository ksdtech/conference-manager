<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/teachers/appointments
	 */
	/* $resource_id = 2; */
	/* managers/appointments/edit/2/2015/7/1 */
	/* 2015-07-01 */
	public function edit($resource_id, $year, $month, $day)
	{
		$this->load->model('Timeblock', 'timeblock');
		$this->load->model('Reservation', 'reservation');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		if (!$this->input->post()) {
				
			$schedule_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
			$reservations = $this->reservation->all_by_date($resource_id, $schedule_date);
			$data = array('reservations' => $reservations, 'resource_id' => $resource_id, 'year'=>$year, 'month'=>$month, 'day'=>$day);
			$this->load->template('managers/appointments_edit',$data);
		}
		else
		{
			/*
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
					`created_at` datetime NOT NULL,
					`updated_at` datetime NOT NULL,
					`last_notified_at` datetime DEFAULT NULL,
					PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;
			*/
			
			
			$post_data = $this->input->post();
			
			foreach(array_keys($post_data) as $key) {
				if (preg_match('/delete_(\d+)_(.+)/', $key, $matches)) {
					$resource_calendar_id = $matches[1];
					
					$duration = $this ->db-> get_where ('resource_calendars', array('resource_calendar_id' => $resource_calendar_id))
					-> select('duration_in_minutes')[0]['duration_in_minutes'];
					
					$time_start = $matches[2];
					$data['resource_calendar_id'] = $resource_calendar_id;	
					$data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
					$data['user_id'] = null;
					$data['location'] = null;
					$data['resource_id'] = $resource_id;
					$data['last_notified_at'] = null;
					$data['$time_start'] = $time_start;
					$data['$time_end'] = add_minutes_to_time($time_start, $duration);
				}
				else if (preg_match('/delete_(\d+)/', $key, $matches)) {
					$reservation_id = $matches[1];
					$data['updated_at'] = date('Y-m-d H:i:s');
					$data['id'] = $reservation_id;
					$data['resource_calendar_id'] = $this -> db -> get_where ('reservations', array('id' => $reservation_id))
					->select('resource_calendar_id')[0]['resource_calendar_id'];
				}
				$data['schedule_date'] = sprintf('%04d-%02d-%02d', $year, $month, $day);
				$data['status'] = "U";
				$result = $this->reservation->create_or_update($data);
				die($result);
			}
		}
		
	}
	
	public function add_minutes_to_time($time, $minutes)
	{
		$time_data = explode(":", $time);
		$new_time_in_minutes = $time_data[0] * 60 + $time_data[1] + $minutes;
		return str($new_time_in_minutes/60) . ":" . str($new_time_in_minutes % 60) . ":00";
	}
}
