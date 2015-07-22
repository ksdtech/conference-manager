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
			$this->load->model('Resource', 'resource');
			$this->load->helper(array('form', 'url'));
	
			$data = array('resources' => $this->resource->all());
			$this->load->template('admin/resources_index', $data);	
		}
	}
	
	public function add() {
		
		if ($this->require_role('admin')) {
			$rules = array(
				array(
					'field' => 'name',
					'label' => 'Name',
					'rules' => 'trim|required|is_unique[resources.name]'
				)
			);
			
			$this->load->model('Resource', 'resource');
			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$this->load->template('admin/resources_add');
			} else {
				$resource_id = $this->resource->create($this->input->post());
				if ($resource_id !== FALSE) {
					$this->session->set_flashdata('info', 'Resource '.$resource_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'Resource '.$resource_id.' could not be created.');
				}
				redirect(site_url('admin').'/resources/index');
				
			}
		}
	}
	
	public function edit($resource_id) {
		
		if ($this->require_role('admin')) {	
			$rules = array(
				array(
					'field' => 'name',
					'label' => 'Name',
					'rules' => 'trim|required|edit_unique[resources.name.id.'.$resource_id.']'
				)
			);
			
			$this->load->model('Resource', 'resource');
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() == FALSE) {
				
				$data = array('resource' => $this->resource->read($resource_id));
				$this->load->template('admin/resources_edit', $data);
				
			} else {
				if ($this->resource->update($resource_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'Resource '.$resource_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'Resource '.$resource_id.' could not be updated.');
				}
				redirect(site_url('admin').'/resources/index');

			}
		}
	}

	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function delete($resource_id) {
		
		if ($this->require_role('admin')) {
			$this->load->model('Resource', 'resource');
			if ($this->resource->delete($resource_id)) {
				$this->session->set_flashdata('info', 'Resource '.$resource_id.' was deleted.');
			} else {
				$this->session->set_flashdata('error', 'Resource '.$resource_id.' could not be deleted.');
			}
			redirect(site_url('admin').'/resources/index');
		}
	}
}
