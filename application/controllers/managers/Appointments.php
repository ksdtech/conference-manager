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
			$post_data = $this->input->post();
			
			foreach(array_keys($post_data) as $key) {
				if (preg_match('/delete_(\d+)_(.+)/', $key, $matches)) {
					$resource_calendar_id = $matches[1];
					$time_start = $matches[2];
				}
				else if (preg_match('/delete_(\d+)/', $key, $matches)) {
					$reservation_id = $matches[1];
				}
			}
		}
	}
}
