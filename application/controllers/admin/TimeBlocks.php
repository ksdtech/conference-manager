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
	
	// .../admin/timeblocks/add/:schedule_id/
	public function add($schedule_id) {
		$rules = array(
				array(
				'field' => 'time_blocks',
				'label' => 'Time blocks',
				'rules' => 'trim|required|external_callbacks[model,TimeBlock,validate_tblocks,FALSE]'
			)
		);
		
		// TODO: rule for Duration <= Interval
		
		$this->load->model('TimeBlock', 'timeblock');
		$this->load->helper(array('form', 'url'));
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules($rules);
			
		if ( $this->form_validation->run() == FALSE ) {
			// Show input form
			$data = array(
				'schedule_id' => $schedule_id		
			);
			$this->load->template('admin/timeblocks_add', $data);
		} else {
			$this->load->model('Schedule', 'schedule');
			$schedule_data = $this->schedule->read($schedule_id);
			// Process form data
			$data     = $this->input->post();
			$interval = intval($schedule_data['interval_in_minutes']);
			$duration = intval($schedule_data['duration_in_minutes']);
			if ($duration == 0) {
				$duration = $interval;
			}
			$text     = $data['time_blocks'];
			$tb_array = $this->timeblock->validate_tblocks($text, 'TRUE');
			
			for ($i = 0; $i < count($tb_array); $i ++)
			{
				$current_time_interval = $tb_array[$i];

				/* Values are in minutes after midnight */
				$block_start = $current_time_interval[0];
				$block_last  = $current_time_interval[1] - $duration;

				for ($time_start =  $block_start; $time_start <= $block_last; $time_start += $interval)
				{
					$hour_start   = (int)($time_start/60);
					$minute_start = $time_start % 60;
					
					$time_end     = $time_start + $duration;
					$hour_end     = (int)($time_end/60);
					$minute_end   = $time_end % 60;
					$this->timeblock->create($schedule_id, 
							sprintf('%02d:%02d:00', $hour_start, $minute_start),
							sprintf('%02d:%02d:00', $hour_end, $minute_end));
				}
			}
			redirect(site_url('admin').'/schedules/edit/'.$schedule_id);
		}
	}	
}		
