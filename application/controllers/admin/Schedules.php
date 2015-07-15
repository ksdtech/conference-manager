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
							'rules' => 'required'
					),
					array(
							'field' => 'interval_in_minutes',
							'label' => 'Interval',
							'rules' => 'required|numeric'
					),
					array(
							'field' => 'duration_in_minutes',
							'label' => 'Duration',
							'rules' => 'required|numeric'
					),
			);
			
			$this->load->model('Schedule', 'schedule');
			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($add_schedule_rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$this->load->template('admin/schedules_add');
			} else {
				$schedule_id = $this->schedule->create($this->input->post());
				if ($schedule_id !== FALSE) {
					$this->session->set_flashdata('info', 'Schedule '.$schedule_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'Schedule '.$schedule_id.' could not be created.');
				}
				redirect('/admin/schedules/index');
				
			}
		}
	}
	
	public function edit($schedule_id) {
		
		if ($this->require_role('admin')) {	
			$edit_schedule_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'required'
					),
					array(
							'field' => 'interval_in_minutes',
							'label' => 'Interval',
							'rules' => 'required|numeric'
					),
					array(
							'field' => 'duration_in_minutes',
							'label' => 'Duration',
							'rules' => 'required|numeric'
					),
			);
			
			$this->load->model('Schedule', 'schedule');
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($edit_schedule_rules);

			if ($this->form_validation->run() == FALSE) {
				
				$data = $this->schedule->read($schedule_id);
				$this->load->template('admin/schedules_edit', $data);
				
			} else {
				if ($this->schedule->update($schedule_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'Schedule '.$schedule_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'Schedule '.$schedule_id.' could not be updated.');
				}
				redirect('/admin/schedules/index');

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
			redirect('/admin/schedules/index');
			
		}
	}
}
