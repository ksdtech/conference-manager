<?php

class Resource extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function create($post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		$data['default_location']   = $post_data['default_location'];
		
		$resource_id = FALSE;

		$this->db->trans_start();
		$this->db->insert('resources', $data);
		if ($this->db->affected_rows() == 1) {
			$resource_id = $this->db->insert_id();
		}
		$this->db->trans_complete();

		return $resource_id;
	}
	
	
	public function read($resource_id) {
		return $this->db->where('id', $resource_id)
		->limit(1)
		->get('resources')
		->row_object('Resource');
	}
	
	public function all() {
		return $this->db->order_by('name')->get('resources')
		->result('Resource');
	}
	
	public function all_calendar_items($resource_id) {
		
	}
	
	public function group_class_names() {
		$groups = $this->db->select('resource_group_id')
		->join('resources', 'resources.id=resource_group_members.resource_id')
		->where('resources.id', $this->id)
		->get('resource_group_members')->result_array();
		$class_names = '';
		foreach($groups as $group) {
			$class_names .= ' group_' . $group['resource_group_id'];
		}
		return $class_names;
	}
	
	
	
	public function get_all_resource_calendars($resource_id)
	{
		$resource_calendars = $this->db->select('c.id, c.name')
		->join('calendar_resources cr', 'cr.resource_calendar_id=c.id')
		->where('cr.resource_id', $resource_id)
		->get('resource_calendars c')
		->result_array();
	
		return $resource_calendars;
	}
	
	public function get_resource_calendar_options($resource_id) {
		$resource_calendars = $this->get_all_resource_calendars($resource_id);
		$options = array('' => 'Select the calendar');
		foreach ($resource_calendars as $calendar) {
			$options[$calendar['id']] = $calendar['name'];
		}
		return $options;
	}
	
	
	public function is_on_calendar($resource_calendar_id)
	{
	   	$num_results = $this->db->where(array("resource_calendar_id" => $resource_calendar_id, "resource_id" => $this->id))->get("calendar_resources")->num_rows();
	    
	   	if ($num_results == 1)
	   	{
	   		return true;
	   	}
	   	else if ($num_results > 1)
	   	{
	   		die("This resource is already associated with this resource calendar");
	   	}
	   	else
	   	{
	   		return false;
	   	}
	
	}
	
	public function update($resource_id, $post_data) {
		$data['name']          = $post_data['name'];
		$data['description']   = $post_data['description'];
		$data['default_location']   = $post_data['default_location'];
		
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
