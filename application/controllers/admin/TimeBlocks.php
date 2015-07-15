<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimeBlocks extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin/users
	 */
	public function index() {
	}
	
	public function add() {
		$rules = array(
			array(
				'field' => 'duration',
				'label' => 'Duration',
				'rules' => 'trim|required|numeric|external_callbacks[model,TimeBlock,validate_duration]'
			),
				array(
				'field' => 'time_blocks',
				'label' => 'Time blocks',
				'rules' => 'trim|required|external_callbacks[model,TimeBlock,validate_tblocks,FALSE]'
			)
		);
		
		$this->load->model('TimeBlock', 'timeblock');
		$this->load->helper(array('form', 'url'));
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules($rules);
			
		if ( $this->form_validation->run() == FALSE ) {
			// Show input form
			$this->load->template('admin/timeblocks_add');
		} else {
			// Process form data
			$data = $this->input->post();
			$duration = intval($data['duration']);
			$text = $data['time_blocks'];
			$tb_array = $this->timeblock->validate_tblocks($text, TRUE);
			// $tb_array = [0] => array(600, 1200), 
			//		   [1] => array(1500, 1800)
			// foreach...
			// $block['schedule_id'] = 1
			// $block['start_hour'] = 8
			// $block['start_minute'] = 15
			// $block['duration_in_minutes'] = $duration
			$this->db->insert('time_blocks', $block);
			redirect('admin/timeblocks/index');
		}
	}	
}		
