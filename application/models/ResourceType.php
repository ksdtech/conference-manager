<?php

class ResourceType extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function create($post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		$resource_type_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('resource_types', $data);
		if ($this->db->affected_rows() == 1) {
			$resource_type_id = $this->db->insert_id();
		}
		$this->db->trans_complete();

		return $resource_type_id;
	}
	
	
	public function read($resource_type_id) {
		return $this->db->where('id', $resource_type_id)
		->limit(1)
		->get('resource_types')
		->row_array();
	}
	
	public function all() {
		return $this->db->order_by('name')->get('resource_types')
		->result_array();
	}
	
	public function update($resource_type_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		return $this->db->where('id', $resource_type_id)
		->update('resource_types', $data);
	}
	
	public function delete($resource_type_id) {
		$result = FALSE;
		
		$this->db->trans_start();
		$this->db->where('resource_type_id', $resource_type_id)->delete('scheduled_days');
		$result = $this->db->where('id', $resource_type_id)->delete('resource_types');
		$this->db->trans_complete();
		
		return $result;
	}
	
	public function select_options() {
		$options = array();
		$calendars = $this->all();
		foreach ($calendars as $calendar) {
			$options[''.$calendar['id']] = $calendar['name'];
		}
		return $options;
	}
}
