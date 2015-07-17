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
	
	private function do_add( $is_cli, $data ) {
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
		if ( $is_cli ) {
			$this->form_validation->set_data( $data );
		}
		$this->form_validation->set_rules( $add_user_rules );
		
		if ( $this->form_validation->run() == FALSE ) {
			if ( $is_cli ) {
				die(validation_errors());
				echo "Invalid data";
			} else {
				$this->load->template('admin/users_add');
			}
		} else {
			if ( !$is_cli ) {
				$data = $this->input->post();
			}
			$user_id = $this->user->create( $data );
			if ( $is_cli ) {
				if ( $user_id !== FALSE ) {
					echo "Created user: ".$user_id;
				} else {
					echo "User creation failed";
				}
			} else {
				if ( $user_id !== FALSE ) {
					$this->session->set_flashdata('info', 'User '.$user_id.' was created.');
				} else {
					$this->session->set_flashdata('error', 'User '.$user_id.' could not be created.');
				}
				redirect(site_url('admin').'/users/index');
			}
		}
	}
	
	public function add() {
		if ($this->require_role('admin')) {
			do_add( FALSE, null );
		}
	}
		
	public function add_cli($first_name, $last_name, $email, $password, $user_level = '1') {
		if ( $this->input->is_cli_request() ) {
			$email    = urldecode( $email );
			$password = urldecode( $password );
			$data = array(
					'first_name'   => $first_name,
					'last_name'    => $last_name,
					'user_email'   => $email,
					'user_pass'    => $password,
					'confirm_pass' => $password,
					'user_level'   => $user_level
			);
			$this->do_add( TRUE, $data );
		} else {
			show_404();
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
				redirect(site_url('admin').'/users/index');

			}
		}
	}

	public function action_perform() {
		$data = $this->input->post();
		die("action_perform: ".$data['selected_action']);
	}
	
	public function delete($user_id) {
		
		if ($this->require_role('admin')) {
			$this->load->model('User', 'user');
			if ($this->user->can_delete($user_id)) {
				if ($this->user->delete($user_id)) {
					$this->session->set_flashdata('info', 'User '.$user_id.' was deleted.');
				} else {
					$this->session->set_flashdata('error', 'User '.$user_id.' could not be deleted.');
				}
			} else {
				$this->session->set_flashdata('error', 'Admin user '.$user_id.' could not be deleted. Please change user level first.');
			}
			redirect(site_url('admin').'/users/index');
			
		}
	}
	
	private function find_or_create_by_name($table, $name, $extra_data = array()) {
		if ($name == '') {
			return FALSE;
		}
		$id = FALSE;
		$name_array = array('name' => $name);
		$id_array = $this->db->select('id')->limit(1)->get_where($table, $name_array)->row_array();
		if (count($id_array) > 0) {
			$id = $id_array['id'];
			if (count($extra_data) > 0) {
				$this->db->where('id', $id)->update($table, $extra_data);
			}
		} else {
			$data = array_merge($extra_data, $name_array);
			$this->db->trans_start();
			$this->db->insert($table, $data);
			if ($this->db->affected_rows() == 1) {			
				$id = $this->db->insert_id();
			}
			$this->db->trans_complete();
		}
		return $id;
	}

	private function add_group_member($resource_group_id, $resource_id) {
		$member_id = FALSE;
		$data = array('resource_group_id' => $resource_group_id, 'resource_id' => $resource_id);
		$id_array = $this->db->select('id')->limit(1)->where($data)->get('resource_group_members')->row_array();
		if (count($id_array) > 0) {
			$member_id = $id_array['id'];
		} else {
			$this->db->trans_start();
			$this->db->insert('resource_group_members', $data);
			if ($this->db->affected_rows() == 1) {
				$member_id = $this->db->insert_id();
			}
			$this->db->trans_complete();
		}
		return $member_id;
	}
	
	private function is_truthy($val) {
		$val = substr(strtolower(trim(strval($val))), 0, 1);
		if ($val == '1' || $val == 'y' || $val == 't') {
			return TRUE;
		}
		return FALSE;
	}
	
	private function import_row($line_no, $row, &$error_message) {
		$is_admin    = $this->is_truthy($row['is_admin']);
		$is_resource = $this->is_truthy($row['is_resource']);
		$user_level  = 1;
		if ($is_admin) {
			$user_level = 9;
		} elseif ($is_resource) {
			$user_level = 6;
		}
		$row['user_level'] = $user_level;
		$user_id = $this->user->find_or_create_by_email($row);
		if (!$user_id) {
			$error_message .= 'Line '.$line_no.', could not find or create user '.$row['user_email'];
			return FALSE;
		}
		
		if (!$is_resource) {
			return TRUE;
		}
		
		$full_name = trim($row['first_name'].' '.$row['last_name']);
		$location  = trim($row['location']);
		$extra_data = array();
		if ($location != '') {
			$extra_data['default_location'] = $location;
		}
		$resource_id = $this->find_or_create_by_name('resources', $full_name, $extra_data);
		if (!$resource_id) {
			$error_message .= 'Line '.$line_no.', could not update or create resource '.$full_name;
			return FALSE;
		}
		
		$resource_type_id = $this->find_or_create_by_name('resource_types', $row['resource_type']);
		if (!$resource_type_id) {
			$error_message .= 'Line '.$line_no.', could not find or create resource type '.$row['resource_type'];
			return FALSE;
		}
		
		$extra_data = array('resource_type_id' => $resource_type_id);
		$resource_calendar_id = $this->find_or_create_by_name('resource_calendars', $row['resource_calendar'], $extra_data);
		if (!$resource_calendar_id) {
			$error_message .= 'Line '.$line_no.', could not find or create resource calendar '.$row['resource_calendar'];
			return FALSE;
		}
		
		$resource_group_id = $this->find_or_create_by_name('resource_groups', $row['resource_group']);
		if (!$resource_group_id) {
			$error_message .= 'Line '.$line_no.', could not update or create resource group '.$row['resource_group'];
			return FALSE;
		}
		
		$member_id = $this->add_group_member($resource_group_id, $resource_id);
		if (!$member_id) {
			$error_message .= 'Line '.$line_no.', could not add resource '.$full_name.' to group '.$row['resource_group'];
			return FALSE;
		}
		return TRUE;
	}
	
	public function upload_users() {
		$config = array(
				'upload_path'   => $this->config->item('upload_path'),
				'allowed_types' => 'csv',
				'overwrite'     => TRUE,
				'remove_spaces' => TRUE
		);
		$this->load->library('upload', $config);
		
		if ( $this->upload->do_upload() === FALSE ) {
			$this->session->set_flashdata('error', $this->upload->display_errors());
		} else {
			$upload_data = $this->upload->data();
			$error_message = '';
			$users_processed = $this->admin_import($upload_data['full_path'], $error_message);
			if ( $users_processed > 0 ) {
				$this->session->set_flashdata('info', ''.$users_processed.' users were processed');
			}
			if ( $error_message != '' ) {
				$this->session->set_flashdata('error', $error_message);
			}
		}
		redirect(site_url('admin').'/users/index');
	}
	
	private function admin_import($file_path, &$error_message) {
		$fh = @fopen($file_path, 'rb', FALSE);
		if ($fh == null) {
			$error_message = 'Could not open uploaded file "'.$file_path.'".';
			return 0;
		}
		
		$this->load->model('User', 'user');
		$mandatory = array('first_name', 'last_name', 'user_email', 'user_pass', 'location',
				'is_admin', 'is_resource', 'resource_type', 'resource_calendar', 'resource_group');
		$headers = array();
		$line_no = 0;
		$users_processed = 0;
		while (($data = fgetcsv($fh)) != null) {
			$line_no++;
			if ($line_no == 1) {
				$check = array_diff($mandatory, $data);
				if (count($check) > 0) {
					$error_message = 'File header is missing these fields: '.implode(', ', $check);
					break;
				}
				$headers = $data;
			} else {
				$row = array_combine($headers, $data);
				if ($this->import_row($line_no, $row, $error_message)) {
					$users_processed++;
				}
			}
		}
		fclose($fh);
		
		return $users_processed;
	}
}
