<?php

class TimeBlock extends MY_Model {

	public function __construct() {
		parent::__construct();
	}

	public function validate_duration($text) {
		$minutes = intval($text);
		if ($minutes < 5 || $minutes > 120) {
			return FALSE;
		}
		return $text;
	}
	
	private function validate_time_element($time_element, &$error_message) {
		$match = preg_match('/([\d]{1,2}):([\d]{2})\s*(am|pm)/', $time_element, $matches);
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
			return $hour * 100 + $minutes;
		} else {
			$error_message = 'time must be hh:mm am';
			return -1;
		}
	}
	
	/* validate the text to specify the time blocks */
	public function validate_tblocks($text, $arguments) {
		$results = array();
		$error_message = '';
		$no_space_text = strtolower(preg_replace('/\s+/', '', $text));
		$blocks = preg_split('/,/', $text);
		foreach ($blocks as $block) {
			$start_finish = preg_split('/-/', $block);
			if (count($start_finish) != 2) {
				$error_message = 'start and end times must be separated by a hyphen';
			} else {
				$start = $start_finish[0];
				$finish = $start_finish[1];
				$start_mil = $this->validate_time_element($start, $error_message);
				$finish_mil = $this->validate_time_element($finish, $error_message);
				if ($error_message == '') {
					if ($finish_mil > $start_mil) {
						array_push($results, array($start_mil, $finish_mil));
					} else {
						$error_message = 'end time '. $finish . ' must be larger than ' . $start;
					}
				}
			}
			if ($error_message != '') {
				break;
			}
		}
		if ( $error_message == '') {
			// make sure there's no overlap
			// results = [0] => array(600, 1200)
			//           [1] => array(1500, 1800)
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

