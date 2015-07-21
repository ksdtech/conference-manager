<?php

class TimeBlock extends MY_Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function get_duration_options($zero_string) {
		$val_array = $this->db->where('name', 'durations')->get('preferences')->row_array();
		$vals = explode(',', $val_array['value']);
		$options = array('0' => $zero_string);
		foreach ($vals as $val) {
			$options[$val] = $val;
		}
		return $options;
	}
	
	public function time_ampm($val) {
		$match = preg_match('/(\d\d):(\d\d):\d\d/', $val, $matches);
		$hour =   intval($matches[1]);
		$minute = intval($matches[2]);
		$ampm = ($hour >= 12) ? 'pm' : 'am';
		if ($hour == 0) {
			$hour = 12;
		} elseif ($hour > 12) {
			$hour -= 12;
		}
		return sprintf("%d:%02d %s", $hour, $minute, $ampm);
	}

	public function time_start_ampm() {
		return $this->time_ampm($this->time_start);
	}
	
	public function time_end_ampm() {
		return $this->time_ampm($this->time_end);
	}
	
	public function create($schedule_id, $time_start, $time_end) {	
		$schedule_time_id = FALSE;
	
		$this->db->trans_start();
		$n_conflicts = $this->conflicts($schedule_id, $time_start, $time_end);
		if ($n_conflicts == 0) {
			$data = array('schedule_id' => $schedule_id, 'time_start' => $time_start, 'time_end' => $time_end);
			$this->db->insert('schedule_times', $data);
			if ($this->db->affected_rows() == 1) {
				$schedule_time_id = $this->db->insert_id();
			}
		}
		$this->db->trans_complete();
	
		return $schedule_time_id;
	}
	
	public function all($schedule_id) {
		return $this->db->where('schedule_id', $schedule_id)
			->order_by('time_start', 'time_end')
			->get('schedule_times')->result('TimeBlock');
	}
	
	public function conflicts($schedule_id, $time_start, $time_end) {
		return $this->db->where(
			array(
				'schedule_id' => $schedule_id,
				'time_start <' => $time_end,
				'time_end >' => $time_start))
			->count_all_results('schedule_times');
	}
	
	public function delete($schedule_time_id) {
		return $this->db->where('id', $schedule_time_id)->delete('schedule_times');
	}
	
	public function validate_duration($text) {
		$minutes = intval($text);
		if ($minutes < 5 || $minutes > 120) {
			return FALSE;
		}
		return $text;
	}
	
	/* Parse time of day hh:mm am into minutes after midnight */
	private function validate_time_element($time_element, &$error_message) {
		$match = preg_match('/([\d]{1,2}):([\d]{2})\s*(am|pm)/i', $time_element, $matches);
		if ($match) {
			$hour = intval($matches[1]);
			$minutes = intval($matches[2]);
			$ampm = $matches[3];
			if ($hour < 1 || $hour > 12 || $minutes > 59) {
				$error_message = 'invalid hour or minute';
			}
			if ($hour == 12) {
				$hour = 0;
			}
			if ($ampm == 'pm') {
				$hour += 12;
			}
			return $hour * 60 + $minutes;
		} else {
			$error_message = 'time must be hh:mm am. was '. $time_element;
			return -1;
		}
	}
	
	private function check_overlap($tb_array) {
		for ($i = 0; $i < count($tb_array)-1; $i ++) {
			$start_finish_1 = $tb_array[$i];
	
			for ($j = 1; $j < count($tb_array); $j ++) {
				$start_finish_2 = $tb_array[$j];
					
				if (($start_finish_2[0] > $start_finish_1[0] && $start_finish_2[0] < $start_finish_1[1]) ||
						($start_finish_2[1] < $start_finish_1[1] && $start_finish_2[1]>$start_finish_1[0])) {
					return FALSE;
				}		
			}
		}
		return TRUE;
	}
	
	/* 
	 * Validate the text that specifies the time blocks.
	 * 
	 * Format is expected to be in 12-hour clock time with am and pm specified:
	 *   hh:mm am - hh:mm am, hh:mm am - hh:mm am, ...
	 * 
	 * If $arguments[0] is not 'FALSE', return results as an array of array($start_minutes, $finish_minutes)
	 * where times are represented as minutes after midnight.
	 * 
	 */
	public function validate_tblocks($text, $arguments) {
		$results = array();
		$error_message = '';
		$no_space_text = strtolower(preg_replace('/\s+/', '', $text));
		$blocks = preg_split('/,/', $text);
		foreach ( $blocks as $block ) {
			$start_finish = preg_split('/-/', $block);
			if ( count($start_finish) != 2 ) {
				$error_message = 'start and end times must be separated by a hyphen';
			} else {
				$start  = $start_finish[0];
				$finish = $start_finish[1];
				$start_minutes  = $this->validate_time_element($start,  $error_message);
				$finish_minutes = $this->validate_time_element($finish, $error_message);
				if ( $error_message == '' ) {
					if ($finish_minutes > $start_minutes) {
						array_push($results, array($start_minutes, $finish_minutes));
					} else {
						$error_message = 'end time '. $finish . ' must be larger than ' . $start;
					}
				}
			}
			if ($error_message != '') {
				break;
			}
		}
		if ( $error_message == '' && !$this->check_overlap($results) ) {
			$error_message = 'your time blocks overlap.  Please fix it.';
		}
		if ( $error_message == '' ) {
			if ($arguments[0] != 'FALSE') {
				return $results;
			} else {
				return $text;
			}
		} else {
			$this->form_validation->set_message(
				'external_callbacks',
				'<span class="redfield">%s</span>: ' . $error_message
			);
			return FALSE;
		}
	}
}

