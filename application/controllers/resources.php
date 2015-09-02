<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resources extends MY_Controller {


	public function index() {

	//if ($this->require_role('admin')) {
		$this->load->model('Resource', 'resource');
		$this->load->helper(array('form', 'url'));

		$data = array(
			'resources' => $this->resource->all(), 
			'user_id' => 1,
			'base_url' => site_url('appointments'));
		
		$this->load->template('resource_selection', $data);
	//}
	}
	
	public function get_user()
	{
		return "Matthew";
	}
	
	
}