<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resources extends MY_Controller {


	public function index() {

		if ($this->require_min_level(1))
		{
		if ($this->input->post())
		{
			$post_data = $this->input->post();
			
			$resource_id = $post_data['selected_resource'];
			$resource_calendar_id = $post_data['calendar_' . $resource_id];

			redirect(site_url('appointments').'/index/'.$resource_id.'/'.$resource_calendar_id);
		
		}
		else
		{
	//if ($this->require_role('admin')) {
		$this->load->model('Resource', 'resource');
		$this->load->helper(array('form', 'url'));

		$data = array(
			'resources' => $this->resource->all(), 
			'user_id' => 1,
			'base_url' => site_url('appointments'));
		
		$this->load->template('resource_selection', $data);
		}
		
	//}
	}
	}
	
}