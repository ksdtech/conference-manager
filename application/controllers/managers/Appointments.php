<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/teachers/appointments
	 */
	
	public function resources() {
		if ($this->require_min_level(6)) {
			$this->load->model('User', 'user');
			$data = array('options' => $this->user->managed_resource_options( $this->auth_user_id ));
			$this->load->template('managers/resources_list', $data);
		}
	}
	
	public function index($resource_id) {
		if ($this->require_min_level(6))
		{
		
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
	
	if ($this->require_min_level(6)) {
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
		$data = array('calendar' => $this->calendar->generate($year, $month, $cal_data), 'resource_id' => $resource_id);
		$this->load->template('managers/appointments_index', $data);
	}
	}
	}
	
	/* $resource_id = 2; */
	/* managers/appointments/edit/2/2015/7/1 */
	/* 2015-07-01 */
	
	public function edit($resource_id, $year, $month, $day)
	{
		if ($this->require_min_level(6))
		{
		$this->load->model('Timeblock', 'timeblock');
		$this->load->model('Reservation', 'reservation');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$schedule_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
		
		if (!$this->input->post()) {
				
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
				//Intialaize $data array
				//$data['test'] = 0;
				if (preg_match('/delete_(\d+)-(\d+-\d+-\d+)-([\d:]+)-([\d:]+)/', $key, $matches)) {
					/* $resource_id = $matches[1]; */
					/* $schedule_date = $matches[2]; */
					
					$time_start = $matches[3];
					$time_end = $matches[4];
					$data['schedule_date'] = $schedule_date;
					$data['time_start'] = $time_start;
					$date['time_end'] = $time_end;
					$data['resource_id'] = $resource_id;	
					$data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
					$data['user_id'] = null;
					$data['location'] = null;
					$data['resource_id'] = $resource_id;
					$data['last_notified_at'] = null;
				}
				else if (preg_match('/delete_(\d+)/', $key, $matches)) {
					$reservation_id = $matches[1];
					$data['updated_at'] = date('Y-m-d H:i:s');
					$data['id'] = $reservation_id;
					//die(var_dump($data['id']));
				}
				else 
				{
					continue;
				}
				$data['schedule_date'] = sprintf('%04d-%02d-%02d', $year, $month, $day);
				$data['status'] = "U";
				$result = $this->reservation->create_or_update($data);
			}
		}
		}
		
	}
	
	public function send_email_reminder()
	{
		if ($this->require_min_level(6))
		{
			
			$this->load->library('email');
			
			
			$config = array('smtp_host' => config_item('smtp_host'),
					'protocol' => 'smtp',
					'smtp_port' => 25
			);
			
			
			$this->email->initialize($config);
			
			$this->load->model('User', 'user');
			$this->load->model('ResourceCalendar', 'resourcecalendar');
			$User_id = $this->auth_user_id;
			
			
			
			$teacher_info = $this->user->get_user_info($this->auth_user_id);		
			$teacher_email = $teacher_info['user_email'];
			$teacher_name = $teacher_info['first_name'] . ' ' . $teacher_info['last_name'];
			
			$reservations = $this->get_all_reservations_for_teacher();
			
		
			
			foreach ($reservations as $reservation)
			{
				
				//Note: The user is the person who booked the appointment with the current teacher.
				$user_info = $this->user->get_user_info($reservation -> user_id);
				$user_email = $user_info['user_email'];
				$user_name = $user_info['first_name'] . ' ' . $user_info['last_name'];
				
				$resource_calendar_name = $this->resourcecalendar->get_resource_calendar_name($reservation -> resource_calendar_id);
				
				$this->email->clear();
				$this->email->from($teacher_email, $teacher_name);
				$this->email->to($user_email);
				
				$this->email->subject($resource_calendar_name . " Appointment Reminder");
				
				$message = 'This is an automated reminder that you have an appointment scheduled with ' . $teacher_name . ' on ' . $reservation -> schedule_date . ' at ' . $reservation -> time_start . ".";		
					
				//die(var_dump($message));
				
				$this->email->message($message);
				
				$value = $this->email->send();
				
				die(var_dump($this->email->print_debugger()));
			
			}
		}
	}
	
	
	private function get_all_reservations_for_teacher()
	{
		
		$this->load->model('Timeblock', 'timeblock');
		$this->load->model('User', 'user');
		$this->load->model('Reservation', 'reservation');
		
		
		$User_id = $this->auth_user_id;
		
		$managed_resources = $this->user->managed_resources($User_id)[0];
		$resource_ids = array();
		
		foreach ($managed_resources as $managed_resource)
		{
			array_push($resource_ids, $managed_resources['id']);
		}
		
		
		$reservations = $this->reservation->all_booked_appointments_for_teacher($resource_ids);
	
		return $reservations;
		
	}
	
	public function edit_all()
	{
		if ($this->require_min_level(6))
		{
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
	
			if (!$this->input->post()) {
	
				$data = array('reservations' => $this->get_all_reservations_for_teacher());
				$this->load->template('managers/appointments_list',$data);
			}
			else
			{
				$post_data = $this->input->post();
					
				foreach(array_keys($post_data) as $key) {
					//Intialaize $data array
					//$data['test'] = 0;
					if (preg_match('/unbook_(\d+)/', $key, $matches)) {
						$data['status'] = "A";
						$data['user_id'] = null;
						$reservation_id = $matches[1];
						$data['updated_at'] = date('Y-m-d H:i:s');
						$data['id'] = $reservation_id;
						//die(var_dump($data['id']));
					}
					else
					{
						continue;
					}
					$result = $this->reservation->create_or_update($data);
				}
					
					
				redirect(site_url('managers').'/appointments/edit_all/' . $resource_id);
			}
		}
	
	}
	
	private function add_minutes_to_time($time, $minutes)
	{
		$time_data = explode(":", $time);
		$new_time_in_minutes = $time_data[0] * 60 + $time_data[1] + $minutes;
		return sprintf("%02d:%02d:00", intval($new_time_in_minutes/60), intval($new_time_in_minutes%60));
	}
}
