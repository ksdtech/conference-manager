<?php

class User extends Auth_model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get an unused ID for user creation
	 *
	 * @return  int between 1200 and 4294967295
	 */
	private function _get_unused_id() {
		// Create a random user id
		$random_unique_int = mt_rand(1200, 4294967295);
	
		// Make sure the random user_id isn't already in use
		$query = $this->db->where('user_id', $random_unique_int)
			->get('users');
	
		if ($query->num_rows() > 0) {
			$query->free_result();
	
			// If the random user_id is already in use, get a new number
			return $this->_get_unused_id();
		}
		return $random_unique_int;
	}
	
	public function create($post_data) {
		
		// We don't use usernames, so 'user_name' must be entered into the record as NULL
		$data['user_level']    = $post_data['user_level'];
		$data['first_name']    = $post_data['first_name'];
		$data['last_name']     = $post_data['last_name'];
		$data['user_name']     = NULL;
		$data['user_email']    = $post_data['user_email'];
		$data['user_salt']     = $this->authentication->random_salt();
		$data['user_pass']     = $this->authentication->hash_passwd($post_data['user_pass'], $data['user_salt']);
		$data['user_id']       = $this->_get_unused_id();
		$data['user_date']     = date('Y-m-d H:i:s');
		$data['user_modified'] = date('Y-m-d H:i:s');
		
		$this->db->insert('users', $data);
		if ($this->db->affected_rows() == 1) {
			return $data['user_id'];
		}
		return FALSE;
	}
	
	public function read($user_id) {
		return $this->db->where('user_id', $user_id)
		->limit(1)
		->get('users')
		->row_array();
	}
	
	public function all() {
		return $this->db->get('users')->result_array();
	}
	
	public function update($user_id, $post_data) {
		$data['user_level']    = $post_data['user_level'];
		$data['first_name']    = $post_data['first_name'];
		$data['last_name']     = $post_data['last_name'];
		$data['user_email']    = $post_data['user_email'];

		if (!empty($post_data['user_pass'])) {
			$data['user_salt'] = $this->authentication->random_salt();
			$data['user_pass'] = $this->authentication->hash_passwd($post_data['user_pass'], $data['user_salt']);
		}
		
		$data['user_modified'] = date('Y-m-d H:i:s');
		
		return $this->db->where('user_id', $user_id)
		->update('users', $data);
	}
	
	public function delete($user_id) {
		return $this->db->where('user_id', $user_id)->delete('users');
	}
}