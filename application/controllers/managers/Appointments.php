<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/teachers/appointments
	 */
	
	public function index($resource_id) {
		
	$template = '
   {table_open}<table border="0" cellpadding="0" cellspacing="0">{/table_open}
	
   {heading_row_start}<tr>{/heading_row_start}
	
   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
	
   {heading_row_end}</tr>{/heading_row_end}
	
   {week_row_start}<tr>{/week_row_start}
   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
   {week_row_end}</tr>{/week_row_end}
	
   {cal_row_start}<tr>{/cal_row_start}
   {cal_cell_start}<td width="100" height="100" style="border:1px solid; text-align:center; vertical-align:top">{/cal_cell_start}
	
   {cal_cell_content}{day}<br/>{content}{/cal_cell_content}
   {cal_cell_content_today}<span class="highlight">{day}</span><br/>{content}{/cal_cell_content_today}
	
   {cal_cell_no_content}{day}{/cal_cell_no_content}
   {cal_cell_no_content_today}<span class="highlight">{day}</span>{/cal_cell_no_content_today}
	
   {cal_cell_blank}&nbsp;{/cal_cell_blank}
	
   {cal_cell_end}</td>{/cal_cell_end}
   {cal_row_end}</tr>{/cal_row_end}
	
   {table_close}</table>{/table_close}
';
	
	if (TRUE /* $this->require_role('admin') */) {
		$today = getdate();
		$year  = $today['year'];
		$month = $today['mon'];
		
		/* managers/appointments/index/:resource_id/:year/:month/:day */
		if (!empty($this->uri->segment(5)) && !empty($this->uri->segment(6))) {
			$year  = $this->uri->segment(5);
			$month = $this->uri->segment(6);
		}
	
		$prefs = array (
				'day_type'        => 'short',
				'show_next_prev'  => TRUE,
				'next_prev_url'   => site_url('managers').'/managers/appointments/'.$resource_id,
				'template'        => $template
		);
			
		$this->load->model('Timeblock', 'timeblock');
		$this->load->model('Reservation', 'reservation');
		$cal_data = array();
		for ($day = 1; $day <= 31; $day++) {
			$cal_data[$day] = '';
	
			$schedule_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
			$reservations = $this->reservation->all_by_date($resource_id, $schedule_date);
			$num_res = count($reservations);
			if ($num_res > 0) {
				$cal_data[$day] .= '<a href="'.site_url('managers').'/appointments/edit/'.$resource_id.'/'.$year.'/'.$month.'/'.$day.'">'
						.$num_res.' reservations</a><br/>';
			} else {
				$cal_data[$day] .= '<a href="'.site_url('managers').'/appointments/edit/'.$resource_id.'/'.$year.'/'.$month.'/'.$day.'">'
						.'[+]</a><br/>';
			}
		}
			
		$this->load->library('calendar', $prefs);
		$data = array('calendar' => $this->calendar->generate($year, $month, $cal_data));
		$this->load->template('admin/master_calendar_index', $data);
	}
	}
	
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
