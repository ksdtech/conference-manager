<?php

class ResourceGroup extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function create($post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		$resource_calendar_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('resource_groups', $data);
		if ($this->db->affected_rows() == 1) {
			$resource_calendar_id = $this->db->insert_id();
		}
		$this->db->trans_complete();

		return $resource_group_id;
	}
	
	
	public function read($resource_group_id) {
		return $this->db->where('id', $resource_group_id)
		->limit(1)
		->get('resource_groups')
		->row_array();
	}
	
	public function all() {
		return $this->db->order_by('name')->get('resource_groups')
		->result_array();
	}
	
	public function update($resource_group_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		
		return $this->db->where('id', $resource_group_id)
		->update('resource_groups', $data);
	}
	
	public function delete($resource_group_id) {
		$result = FALSE;
		
		$this->db->trans_start();
		$this->db->where('resource_group_id', $resource_group_id)->delete('scheduled_days');
		$result = $this->db->where('id', $resource_group_id)->delete('resource_groups');
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
