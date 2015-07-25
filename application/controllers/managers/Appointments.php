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
		$this->load->model('TimeBlock', 'timeblock');
		$this->load->model('Reservation', 'reservation');
		$schedule_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
		$this->reservation->all_by_date($resource_id, $schedule_date);
		$this->load->template('managers/appointments_edit');
	}
}
