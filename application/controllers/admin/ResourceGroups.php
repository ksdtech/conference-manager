<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResourceGroups extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/resource_groups
	 */
	public function index() {
		
		if ($this->require_role('admin')) {
			$this->load->model('ResourceGroup', 'group');
			$this->load->helper(array('form', 'url'));
	
			$data = array('groups' => $this->group->all());
			$this->load->template('admin/resource_groups_index', $data);	
		}
	}
	
	public function add() {
		
		if ($this->require_role('admin')) {
			$add_resource_group_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|is_unique[resource_groups.name]'
					)
			);
			
			$this->load->model('ResourceGroup', 'group');
			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($add_resource_group_rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$this->load->template('admin/resource_groups_add');
			} else {
				$resource_group_id = $this->group->create($this->input->post());
				if ($resource_group_id !== FALSE) {
					$this->session->set_flashdata('info', 'Resource group '.$resource_group_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'Resource group '.$resource_group_id.' could not be created.');
				}
				redirect(site_url('admin').'/resourcegroups/index');
				
			}
		}
	}
	
	public function edit($resource_group_id) {
		
		if ($this->require_role('admin')) {	
			$edit_resource_group_rules = array(
					array(
							'field' => 'name',
							'label' => 'Name',
							'rules' => 'trim|required|edit_unique[resource_groups.name.id.'.$resource_group_id.']'
					)
			);
			
			$this->load->model('ResourceGroup', 'resourcegroup');
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($edit_resource_group_rules);

			if ($this->form_validation->run() == FALSE) {
				
				$data = $this->resourcegroup->read($resource_type_id);
				$this->load->template('admin/resource_groups_edit', $data);
				
			} else {
				if ($this->resource_group->update($resource_group_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'Resource group '.$resource_group_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'Resource group '.$resource_group_id.' could not be updated.');
				}
				redirect(site_url('admin').'/resourcegroups/index');

			}
		}
	}

	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function delete($resource_group_id) {
		
		if ($this->require_role('admin')) {
			$this->load->model('ResourceGroup', 'resourcegroup');
			if ($this->resourcegroup->delete($resource_group_id)) {
				$this->session->set_flashdata('info', 'Resource group '.$resource_group_id.' was deleted.');
			} else {
				$this->session->set_flashdata('error', 'Resource group '.$resource_group_id.' could not be deleted.');
			}
			redirect(site_url('admin').'/resourcegroups/index');
		}
	}
}
