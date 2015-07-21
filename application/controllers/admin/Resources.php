<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resources extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/resources
	 */
	public function index() {
		
		if ($this->require_role('admin')) {
			$this->load->model('Resources', 'calendar');
			$this->load->helper(array('form', 'url'));
	
			$data = array('calendars' => $this->calendar->all());
			$this->load->template('admin/resources_index', $data);	
		}
	}
	
	public function add() {
		
		if ($this->require_role('admin')) {
			$add_calendar_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|is_unique[resources.name]'
					)
			);
			
			$this->load->model('Resources', 'calendar');
			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($add_resource_rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$this->load->template('admin/resources_add');
			} else {
				$resource_calendar_id = $this->calendar->create($this->input->post());
				if ($resource_calendar_id !== FALSE) {
					$this->session->set_flashdata('info', 'Resources '.$resources_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'Resources '.$resources_id.' could not be created.');
				}
				redirect(site_url('admin').'/resources/index');
				
			}
		}
	}
	
	public function edit($resources_id) {
		
		if ($this->require_role('admin')) {	
			$edit_calendar_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|edit_unique[resources.name.id.'.$resources_id.']'
					)
			);
			
			$this->load->model('Resources', 'calendar');
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($edit_calendar_rules);

			if ($this->form_validation->run() == FALSE) {
				
				$data = $this->calendar->read($resources_id);
				$this->load->template('admin/resources_edit', $data);
				
			} else {
				if ($this->resource->update($resource_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'Resources '.$resources_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'Resources '.$resources_id.' could not be updated.');
				}
				redirect(site_url('admin').'/resources/index');

			}
		}
	}

	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function delete($resources_id) {
		
		if ($this->require_role('admin')) {
			$this->load->model('Resources', 'calendar');
			if ($this->calendar->delete($resources_id)) {
				$this->session->set_flashdata('info', 'Resources '.$resources_id.' was deleted.');
			} else {
				$this->session->set_flashdata('error', 'Resources '.$resources_id.' could not be deleted.');
			}
			redirect(site_url('admin').'/resources/index');
		}
	}
}
