<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimeBlocks extends MY_Controller {
		
	public function action_perform($schedule_id) {
		$this->load->model('TimeBlock', 'timeblock');
		
		$data = $this->input->post();
		if ($data['selected_action'] == 'delete') {
			foreach (array_keys($data) as $key) {
				$match = preg_match('/^item_(.+)$/', $key, $matches);
				if ($match) {
					$timeblock_id = $matches[1];
					if ($data[$key] == '1') {
						$this->timeblock->delete($timeblock_id);
					}
				}
			}
		}
		redirect(site_url('admin').'/schedules/edit/'.$schedule_id);
	}
	
	public function add($schedule_id) {
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
			
			for ($i = 0; $i < count($tb_array); $i ++)
			{
				$current_time_interval = $tb_array[$i];
				$start_time = ($current_time_interval[0] % 100) + (int)(($current_time_interval[0]/100)*60);
				$end_time = ($current_time_interval[1] % 100) + (int)(($current_time_interval[1]/100)*60);
			
				for ($time =  $start_time; $time <= $end_time- $duration; $time += $duration)
				{
					$hour = (int)($time/60);
					$minute = $time % 60;
					$block = 
							array("schedule_id" => $schedule_id,
									"start_hour" => $hour,
									"start_minute" => $minute,
									"duration_in_minutes" => $duration);
					$this->db->insert('time_blocks', $block);
				}
			}
			redirect(site('admin').'/schedules/edit/'.$schedule_id);
		}
	}	
}		
