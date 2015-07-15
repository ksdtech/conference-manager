<?php

class TimeBlock extends MY_Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function start_time_s() {
		$minutes = $this->start_hour*60 + $this->start_minute;
		$hour = (int)($minutes/60) % 12;
		$minute = $minutes % 60;
		$ampm = ($minutes >= 60*12) ? 'pm' : 'am';
		if ($hour == 0) {
			$hour = 12;
		}
		
		return sprintf("%d:%02d %s", $hour, $minute, $ampm);
	}

	public function finish_time_s() {
		$minutes = $this->start_hour*60 + $this->start_minute + $this->duration_in_minutes;
		$hour = (int)($minutes/60) % 12;
		$minute = $minutes % 60;
		$ampm = ($minutes >= 60*12) ? 'pm' : 'am';
		if ($hour == 0) {
			$hour = 12;
		}

		return sprintf("%d:%02d %s", $hour, $minute, $ampm);
	}
	
	public function all($schedule_id) {
		return $this->db->where('schedule_id', $schedule_id)
			->order_by('start_hour, start_minute')
			->get('time_blocks')->result('TimeBlock');
	}
	
	public function delete($timeblock_id) {
		return $this->db->where('id', $timeblock_id)->delete('time_blocks');
	}
	
	public function validate_duration($text) {
		$minutes = intval($text);
		if ($minutes < 5 || $minutes > 120) {
			return FALSE;
		}
		return $text;
	}
	
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
			return $hour * 100 + $minutes;
		} else {
			$error_message = 'time must be hh:mm am. was '. $time_element;
			return -1;
		}
	}
	
	private function checkForOverlap($TimeIntervalArray)
	{
		for ($i = 0; $i < count($TimeIntervalArray)-1; $i ++)
		{
			$TimeInterval1 = $TimeIntervalArray[$i];
	
			for ($j = 1; $j < count($TimeIntervalArray); $j ++)
			{
				$TimeInterval2 = $TimeIntervalArray[$j];
					
				if (($TimeInterval2[0] > $TimeInterval1[0] && $TimeInterval2[0] < $TimeInterval1[1]) ||
						($TimeInterval2[1] < $TimeInterval1[1] && $TimeInterval2[1]>$TimeInterval1[0]))
				{
					return FALSE;
				}
					
			}
		}
		return TRUE;
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
		if ( $error_message == '' && !$this->checkForOverlap($results) ) {
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

