<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedules extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/schedules
	 */
	public function index() {
		
		if ($this->require_role('admin')) {
			$this->load->model('Schedule', 'schedule');
			$this->load->helper(array('form', 'url'));
	
			$data = array('schedules' => $this->schedule->all());
			$this->load->template('admin/schedules_index', $data);	
		}
	}
	
	public function add() {
		
		if ($this->require_role('admin')) {
			$add_schedule_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|is_unique[schedules.name]'
					),
			);
			
			$this->load->model('ResourceCalendar', 'calendar');
			$this->load->model('Schedule', 'schedule');
			$this->load->model('TimeBlock', 'timeblock');
			$this->load->helper(array('form', 'url'));	
			$this->load->library('form_validation');
			
			$int_dur_options = $this->calendar->get_int_dur_options();
			if (count($int_dur_options) == 0) {
				$this->session->set_flashdata('error', 'Please create at least one resource calendar first to set duration!');
				redirect(site_url('admin').'/resourcecalendars/add');
				return;
			}
				
			$this->form_validation->set_rules($add_schedule_rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$data = array('int_dur_options' => $int_dur_options);
				$this->load->template('admin/schedules_add', $data);
			} else {
				$schedule_id = $this->schedule->create($this->input->post());
				if ($schedule_id !== FALSE) {
					$this->session->set_flashdata('info', 'Schedule '.$schedule_id.' was created.');
					redirect(site_url('admin').'/schedules/edit/'.$schedule_id);
				} else {
					$this->session->set_flashdata('error', 'Schedule '.$schedule_id.' could not be created.');
					redirect(site_url('admin').'/schedules/index');
				}
			}
		}
	}
	
	public function edit($schedule_id) {
		
		if ($this->require_role('admin')) {	
			$edit_schedule_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|edit_unique[schedules.name.id.'.$schedule_id.']'
					)	
				);
			
			$this->load->model('Schedule', 'schedule');
			$this->load->model('TimeBlock', 'timeblock');
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules($edit_schedule_rules);

			if ($this->form_validation->run() == FALSE) {
				
				$schedule = $this->schedule->read($schedule_id);
				$timeblocks = $this->timeblock->all($schedule_id);
				$data = array(
					'schedule' => $schedule,
					'timeblocks' => $timeblocks
				);
				$this->load->template('admin/schedules_edit', $data);
				
			} else {
				if ($this->schedule->update($schedule_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'Schedule '.$schedule_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'Schedule '.$schedule_id.' could not be updated.');
				}
				
				redirect(site_url('admin').'/schedules/index');
			}
		}
	}

	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function delete($schedule_id) {
		
		if ($this->require_role('admin')) {
			$this->load->model('Schedule', 'schedule');
			if ($this->schedule->delete($schedule_id)) {
				$this->session->set_flashdata('info', 'Schedule '.$schedule_id.' was deleted.');
			} else {
				$this->session->set_flashdata('error', 'Schedule '.$schedule_id.' could not be deleted.');
			}
			redirect(site_url('admin').'/schedules/index');
			
		}
	}
}
