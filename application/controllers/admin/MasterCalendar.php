<?php
class MasterCalendar extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/appointments
	 */
	public function index()
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
	
		if ($this->require_role('admin')) {
			$today = getdate();
			$year  = $today['year'];
			$month = $today['mon'];
			
			if (!empty($this->uri->segment(4)) && !empty($this->uri->segment(5))) {
				$year  = $this->uri->segment(4);
				$month = $this->uri->segment(5);
			}

			$prefs = array (
				'day_type'        => 'short',
				'show_next_prev'  => TRUE,
				'next_prev_url'   => site_url('admin').'/mastercalendar/index/',
				'template'        => $template
			);
			
			$this->load->model('ScheduledDay', 'day');
			$cal_data = array();
			for ($day = 1; $day <= 31; $day++) {
				$cal_data[$day] = '';
				
				$schedule_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
				$scheduled_days = $this->day->all_by_date($schedule_date);
				foreach ($scheduled_days as $scheduled_day) {
					$cal_data[$day] .= '<a href="'.site_url('admin').'/mastercalendar/edit/'.$scheduled_day['id'].'">'
						.$scheduled_day['calendar_name'].'</a><br/>';
				}
				
				$cal_data[$day] .= '<a href="'.site_url('admin').'/mastercalendar/add/'.$year.'/'.$month.'/'.$day.'">[+]</a><br/>';
			}
			
			$this->load->library('calendar', $prefs);
			$data = array('calendar' => $this->calendar->generate($year, $month, $cal_data));
			$this->load->template('admin/master_calendar_index', $data);
		}
	}
	
	public function add($year, $month, $day) {
		if ($this->require_role('admin')) {
			$rules = array(
				array(
					'field' => 'schedule',
					'label' => 'Schedule',
					'rules' => 'required'
				),
			);

			$this->load->model('Schedule', 'schedule');
			$this->load->model('ResourceCalendar', 'calendar');
			$this->load->model('ScheduledDay', 'day');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$schedule_options = $this->calendar->select_options();
			if (count($schedule_options) == 0) {
				$this->session->set_flashdata('error', 'Please create at least one resource calendar first!');
				redirect(site_url('admin').'/resourcecalendars/add');
				return;
			}
			
			$this->form_validation->set_rules($rules);
				
			if ( $this->form_validation->run() == FALSE ) {
				$data = array(
					'schedules' => $schedule_options,
					'year'      => $year,
					'month'     => $month,
					'day'       => $day,
				);
				$this->load->template('admin/master_calendar_add', $data);
			} else {
				$data = $this->input->post();
				if ( $this->day->create($data) === FALSE ) {
					$this->session->set_flashdata('error', 'Unable to add the calendar!');
				}
				redirect(site_url('admin').'/mastercalendar/index/'.$year.'/'.$month);
				
				redirect(site_ur('').'/appointments/edit/'.$resource_id.'/' . $resource_calendar_id . '/' .$year.'/'.$month.'/'.$day);
				
			}
		}
	}
	
	public function edit($scheduled_day_id) {
		if ($this->require_role('admin')) {
			$this->load->model('TimeBlock', 'timeblock');
			$this->load->model('ScheduledDay', 'day');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$data = $this->day->schedule_times($scheduled_day_id);
			$this->load->template('admin/master_calendar_edit', $data);
		}
	}
}
