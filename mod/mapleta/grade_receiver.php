<?php
	require_once('../../config.php');
	require_once($CFG->dirroot.'/lib/grade/constants.php');
    require_once("lib.php");
	
	$status = new mapleta_status_response();
    $xml_request = $HTTP_RAW_POST_DATA;
	$out = mapleta_get_response_from_xml($xml_request, 'mapleta_gradebook_request', $status, 'mapleta_grade_request');
	$array_request= $out->list;

	if (!empty($array_request)) {
		
		if (!mapleta_ws_check_signature($out->timestamp, $out->signature)) {
			$message = "Invalid or expired request.";
			mapleta_ws_send_error($message);
			return;
		}
		
		$element = $array_request[0];
		$subelement = $element->list[0];
		
		if ($mapleta_course_map = mapleta_get_record('mapleta_course_map', 'classid', (int)$element->classId)) {
			if($mapleta = mapleta_get_record('mapleta', 'assignmentid', (int)$element->id)) {
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
						mapleta_ws_send_success();
					} else {
						mapleta_ws_send_error('Failed updating grade');
					}
					
				} else {
					mapleta_ws_send_error('No user found');
									}
			} else {
				mapleta_ws_send_error('No assignment found');
			}
		} else {
			mapleta_ws_send_error('No course mapping found');
		}
	} else {
		mapleta_ws_send_empty('No grade data received');
	}
?>
