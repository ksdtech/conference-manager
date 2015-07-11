<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservations extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/parents/reservations
	 */
	public function index()
	{
		$this->load->template('parents/index');
	}
	
	public function edit()
	{
		$this->load->template('parents/edit');
	}
	
}
