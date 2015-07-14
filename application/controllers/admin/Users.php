<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/users
	 */
	public function index() {
		
		if ($this->require_role('admin')) {
			$this->load->model('User', 'user');
			$this->load->helper(array('form', 'url'));
	
			$data = array('users' => $this->user->all());
			$this->load->template('admin/users_index', $data);	
		}
	}
	
	public function add() {
		
		if ($this->require_role('admin')) {
			$add_user_rules = array(
					array(
							'field' => 'first_name',
							'label' => 'First name',
							'rules' => 'required'
					),
					array(
							'field' => 'last_name',
							'label' => 'Last name',
							'rules' => 'required'
					),
					array(
							'field' => 'user_pass',
							'label' => 'Password',
							'rules' => 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]',
					),
					array(
							'field' => 'confirm_pass',
							'label' => 'Password confirmation',
							'rules' => 'required|matches[user_pass]',
					),
					array(
							'field' => 'user_email',
							'label' => 'Email address',
							'rules' => 'trim|required|valid_email|is_unique[users.user_email]'
					),
					array(
							'field' => 'user_level',
							'label' => 'Level',
							'rules' => 'required|integer|in_list[1,6,9]'
					)
			);
			
			$this->load->model('User', 'user');
			$this->load->helper(array('form', 'url'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($add_user_rules);
			
			if ( $this->form_validation->run() == FALSE ) {
				$this->load->template('admin/users_add');
			} else {
				if ($this->user->create($this->input->post())) {
					$this->session->set_flashdata('info', 'User '.$user_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'User '.$user_id.' could not be created.');
				}
				redirect('/admin/users/index');
				
			}
		}
	}
	
	public function edit($user_id) {
		
		if ($this->require_role('admin')) {	
			$edit_user_rules = array(
					array(
							'field' => 'first_name',
							'label' => 'First name',
							'rules' => 'required'
					),
					array(
							'field' => 'last_name',
							'label' => 'Last name',
							'rules' => 'required'
					),
					array(
							'field' => 'user_pass',
							'label' => 'Password',
							'rules' => 'trim|external_callbacks[model,formval_callbacks,_check_password_strength,FALSE]',
					),
					array(
							'field' => 'confirm_pass',
							'label' => 'Password confirmation',
							'rules' => 'matches[user_pass]',
					),
					array(
							'field' => 'user_email',
							'label' => 'Email address',
							'rules' => 'trim|required|valid_email|edit_unique[users.user_email.user_id.'.$user_id.']'
					),
					array(
							'field' => 'user_level',
							'label' => 'Level',
							'rules' => 'required|integer|in_list[1,6,9]'
					)
			);
			
			$this->load->model('User', 'user');
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules($edit_user_rules);

			if ($this->form_validation->run() == FALSE) {
				
				$data = array('user' => $this->user->read($user_id));
				$this->load->template('admin/users_edit', $data);
				
			} else {

				if ($this->user->update($user_id, $this->input->post())) {
					$this->session->set_flashdata('info', 'User '.$user_id.' was updated.');
				} else {
					$this->session->set_flashdata('error', 'User '.$user_id.' could not be updated.');
				}
				redirect('/admin/users/index');

			}
		}
	}

	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function delete($user_id) {
		
		if ($this->require_role('admin')) {
			
			$this->session->set_flashdata('info', 'User '.$user_id.' would have been deleted.');
			redirect('/admin/users/index');
			
		}
	}
}
