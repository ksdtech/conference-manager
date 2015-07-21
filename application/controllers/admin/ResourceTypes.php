<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResourceTypes extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/resource_types
	 */
	public function index() {
		
		if ($this->require_role('admin')) {
			$this->load->model('ResourceType', 'calendar');
			$this->load->helper(array('form', 'url'));
	
			$data = array('calendars' => $this->calendar->all());
			$this->load->template('admin/resource_types_index', $data);	
		}
	}
	
	public function add() {
		
		if ($this->require_role('admin')) {
			$add_calendar_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|is_unique[resource_types.name]'
					)
			);
			
			$this->load->model('ResourceType', 'calendar');
			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($add_calendar_rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$this->load->template('admin/resource_types_add');
			} else {
				$resource_type_id = $this->calendar->create($this->input->post());
				if ($resource_type_id !== FALSE) {
					$this->session->set_flashdata('info', 'Resource type '.$resource_type_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'Resource type '.$resource_type_id.' could not be created.');
				}
				redirect(site_url('admin').'/resourcetypes/index');
				
			}
		}
	}
	
	public function edit($resource_type_id) {
		
		if ($this->require_role('admin')) {	
			$edit_calendar_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|edit_unique[resource_types.name.id.'.$resource_type_id.']'
					)
			);
			
			$this->load->model('ResourceType', 'calendar');
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($edit_resource_types);

			if ($this->form_validation->run() == FALSE) {
				
				$data = $this->calendar->read($resource_type_id);
				$this->load->template('admin/resource_types_edit', $data);
				
			} else {
				if ($this->resource_type->update($resource_type_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'Resource type '.$resource_type_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'Resource type '.$resource_type_id.' could not be updated.');
				}
				redirect(site_url('admin').'/resourcetypes/index');

			}
		}
	}

	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function delete($resource_type_id) {
		
		if ($this->require_role('admin')) {
			$this->load->model('ResourceType', 'calendar');
			if ($this->calendar->delete($resource_type_id)) {
				$this->session->set_flashdata('info', 'Resource type '.$resource_type_id.' was deleted.');
			} else {
				$this->session->set_flashdata('error', 'Resource type '.$resource_type_id.' could not be deleted.');
			}
			redirect(site_url('admin').'/resourcetypes/index');
		}
	}
}
