<?php

class Resource extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function create($post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		$resource_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('resources', $data);
		if ($this->db->affected_rows() == 1) {
			$resource_id = $this->db->insert_id();
		}
		$this->db->trans_complete();

		return $resource_calendar_id;
	}
	
	
	public function read($resource_id) {
		return $this->db->where('id', $resource_id)
		->limit(1)
		->get('resources')
		->row_array();
	}
	
	public function all() {
		return $this->db->order_by('name')->get('resources')
		->result_array();
	}
	
	public function update($resource_calendar_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		return $this->db->where('id', $resource_id)
		->update('resources', $data);
	}
	
	public function delete($resource_id) {
		$result = FALSE;
		
		$this->db->trans_start();
		$this->db->where('resource_id', $resource_id)->delete('scheduled_days');
		$result = $this->db->where('id', $resource_id)->delete('resources');
		$this->db->trans_complete();
		
		return $result;
	}
	
	public function select_options() {
		$options = array();
		$resources = $this->all();
		foreach ($resources as $resource) {
			$options[''.$resource['id']] = $resource['name'];
		}
		return $options;
	}
}
