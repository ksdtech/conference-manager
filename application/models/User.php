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
		$random_unique_int = mt_rand(1200, 429496729);
	
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
		if ( !empty( $post_data['user_pass'] ) ) {
			$data['user_salt'] = $this->authentication->random_salt();
			$data['user_pass'] = $this->authentication->hash_passwd($post_data['user_pass'], $data['user_salt']);
		} elseif ( !empty( $post_data['oauth_uid'] ) ) {
			$data['user_salt'] = '*google*';
			$data['user_pass'] = $this->authentication->hash_passwd($post_data['oauth_uid'], $data['user_salt']);
		} else {
			return false;
		}
		
		// We don't use usernames, so 'user_name' must be entered into the record as NULL
		$data['user_level']    = $post_data['user_level'];
		$data['first_name']    = $post_data['first_name'];
		$data['last_name']     = $post_data['last_name'];
		$data['user_name']     = NULL;
		$data['user_email']    = $post_data['user_email'];
		$data['user_id']       = $this->_get_unused_id();
		$data['user_date']     = date('Y-m-d H:i:s');
		$data['user_modified'] = date('Y-m-d H:i:s');
		
		$user_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('users', $data);
		if ($this->db->affected_rows() == 1) {
			$user_id = $data['user_id'];
		}
		$this->db->trans_complete();

		return $user_id;
	}
	
	public function read($user_id) {
		return $this->db->where('user_id', $user_id)
		->limit(1)
		->get('users')
		->row_array();
	}
	
	public function find_or_create_by_email($data) {
		$user_id = FALSE;
		$created = FALSE;
		$id_array = $this->db->select('user_id')
			->limit(1)
			->get_where('users', array('user_email' => $data['user_email']))
			->row_array();

		if (count($id_array) > 0) {
			$user_id = $id_array['user_id'];
		} else {
			$user_id = $this->create($data);
			if ( $user_id ) {
				$created = TRUE;
			}
		}
		return array('user_id' => $user_id, 'created' => $created);
	}
	
	public function all() {
		return $this->db->order_by('last_name, first_name, user_email')
		->get('users')
		->result_array();
	}

	public function get_user_info($user_id)
	{
 		return $this->db->get_where('users', array('user_id' => $user_id))
 		->result_array()[0];
	}
	
	public function managed_resources($user_id)
	{
		$managed_resources = $this->db->select('r.id')
		->join('resources r', 'r.id=m.resource_id')
		->where('m.user_id', $user_id)
		->get('resource_managers m')
		->result_array();
		return $managed_resources;
	}
	
	public function managed_resource_options($user_id) {
		$managed_resources = $this->db->select('r.id, r.name')
			->order_by('r.name')
			->join('resources r', 'r.id=m.resource_id')
			->where('m.user_id', $user_id)
			->get('resource_managers m')
			->result_array();

		$options = array('' => 'Select a managed resource');
		foreach ($managed_resources as $resource) {
			$options[$resource['id']] = $resource['name'];
		}
		return $options;
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
	
	public function can_delete($user_id) {
		$user = $this->read($user_id);
		return ($user && $user['user_level'] < 9);
	}
	
	public function delete($user_id) {
		return $this->db->where('user_id', $user_id)->delete('users');
	}
}