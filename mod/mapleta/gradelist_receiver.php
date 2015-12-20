<?php
	require_once('../../config.php');
	require_once($CFG->dirroot.'/lib/grade/constants.php');
    require_once("lib.php");
	
	$xml_request = $HTTP_RAW_POST_DATA;
	$status = new mapleta_status_response();
	$failure_counter = 0;
	
	$out = mapleta_get_response_from_xml($xml_request, 'mapleta_gradebook_request', $status, 'mapleta_grade_request');
	$array_request= $out->list;

	if (!empty($array_request)) {
	
		if (!mapleta_ws_check_signature($out->timestamp, $out->signature)) {
			$message = "Invalid or expired request.";
			mapleta_ws_send_error($message);
			return;
		}
		
		$success_counter = 0;
		$grade_failure_counter = 0;
		$user_failure_counter = 0;
		$assignment_failure_counter = 0;
			
		foreach ( $array_request as $element ) {

			if ($mapleta_course_map = mapleta_get_record('mapleta_course_map', 'classid', (int)$element->classId)) {
				
				if($mapleta = mapleta_get_record('mapleta', 'assignmentid', (int)$element->id)) {
		       	
		       		foreach ( $element->list as $subelement ) {
				
						if ($user = mapleta_get_record('user', 'username', $subelement->userLogin)) {
							
							$mapleta->name = $element->name;
							$mapleta->assignmentmode = $element->mode;
							$mapleta->modedescription = $element->modeDescription;
							$mapleta->passingscore = $element->passingScore;
							$mapleta->totalpoints = $element->totalPoints;
							$mapleta->timelimit = $element->timeLimit;
							$mapleta->starttime = $element->start;
							$mapleta->endtime = $element->end;
							$mapleta->policy = $element->policy;
							
							$grades = array (
												'id' => $user->id,
												'userid' => $user->id,
												'rawgrade' => $subelement->score,
												'dategraded' => $subelement->dateGraded,
												'datesubmitted' => $subelement->dateGraded
											);
											
							if (mapleta_grade_item_update($mapleta, $grades) != GRADE_UPDATE_FAILED) {
								$success_counter++;
							} else {
								$grade_failure_counter++;
							}
							
						} else {
							$user_failure_counter++;
//							mapleta_ws_send_error('No user found');
						}
					}
				} else {
					$assignment_failure_counter++;
//					mapleta_ws_send_error('No assignment found');
				}
			} else {
				mapleta_ws_send_error('No course mapping found');
				return;
			}
		}
		
		$message = 	"$success_counter grade(s) updated";
		
		if ($grade_failure_counter > 0) {
			$message = $message . "\\n$grade_failure_counter update(s) failed";
		}
		if ($assignment_failure_counter > 0) {
			$message = $message . "\\n$assignment_failure_counter assignment(s) not found";
		}
		if ($user_failure_counter > 0) {
			$message = $message . "\\n$user_failure_counter user(s) not found";
		}
		
		if ($failure_counter == 0) {
			mapleta_ws_send_success($message);	
		} else {
			mapleta_ws_send_error($message);	
		}
		
	} else {
		mapleta_ws_send_empty('No grade data received');
	}
?>
