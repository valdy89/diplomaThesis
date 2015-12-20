<?php


// $Id: mod_form.php,v 1.11.2.12 2008/08/13 03:18:11 tjhunt Exp $

global $CFG;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once $CFG->dirroot.'/mod/mapleta/lib.php';

class mod_mapleta_mod_form extends moodleform_mod {
	var $_feedbacks;

	function definition() {

		global $COURSE, $CFG;

		$mform= & $this->_form;
		$mtasession= null;
		$valid= true;
		$errorURL="$CFG->wwwroot/course/view.php?id=$COURSE->id";
		
		if (!empty($this->_instance)) {
			if($mapleta = mapleta_get_record('mapleta', 'id', (int)$this->_instance)) {
				$assignmentid = $mapleta->assignmentid;
			} else {
				print_error('corrupted_assignment', 'mapleta', $errorURL);
			}
		} else {
			$assignmentid=0;
		}


		if($course_mapping= mapleta_get_record('mapleta_course_map', 'courseid', $COURSE->id)) {

			$mform->addElement('hidden', 'course', $course_mapping->courseid);

			$connect= mapleta_ws_connect_to_class($COURSE->id, $course_mapping->classid);

			if($connect == null) {
				print_error('error_connecting_to_ta', 'mapleta', $errorURL);
			} else
				if($connect->code == 1) {
					print_error($connect->message, 'mapleta', $errorURL);
				}

			$status= new mapleta_status_response();
			$mta_assignments= mapleta_ws_get_assignments($course_mapping->classid, $connect->session, $status);
			
			if($status->code == 1) {
				mapleta_ws_disconnect($connect->session, $connect);
				print_error($status->message, 'mapleta', $errorURL);
			} else
				if($status->code == 100) {
					mapleta_ws_disconnect($connect->session, $connect);
					print_error('no_assignments_in_class', 'mapleta', $errorURL);
				}

			mapleta_ws_disconnect($connect->session, $connect);

			$mform->addElement('header', 'header1', get_string('availableassignments', 'mapleta'));
			$mform->closeHeaderBefore('name');
			
			$mform->addElement('html', "<div style='text-align: center'><table id='mapleta-config' class='generaltable boxaligncenter'  cellpadding='5'>");
				$mform->addElement('html', "<tr>");
				$mform->addElement('html', "<th class='header c0'>Name</th>");
				$mform->addElement('html', "<th class='header c0'>Type</th>");
				$mform->addElement('html', "<th class='header c0'>Availability</th>");
				$mform->addElement('html', "<th class='header c0'>Time Limit</th>");
				$mform->addElement('html', "</tr>");
			
			$counter = 0;
			$default_index = -1;
			
			foreach($mta_assignments as $mta_assignment) {
				$mform->addElement('html', "<tr>");
				
				$search                     = array("\"","'");
				$replace_by_entities_number = array("&#34;","&#39;");
				
				$local_name = str_replace($search,$replace_by_entities_number,$mta_assignment->name);				
//				$local_name = addslashes ($mta_assignment->name);
				$local_modeDescription = str_replace($search,$replace_by_entities_number,$mta_assignment->modeDescription);
				$local_start = addslashes ($mta_assignment->start);
				$local_end = addslashes ($mta_assignment->end);
				$local_policy = str_replace($search,$replace_by_entities_number,$mta_assignment->policy);
				
				$attributes="javascript:{document.getElementsByName('assignmentid')[0].value='$mta_assignment->id';" .
						"document.getElementsByName('name')[0].value='$local_name';" .
						"document.getElementsByName('assignmentmode')[0].value='$mta_assignment->mode';" .
						"document.getElementsByName('modedescription')[0].value='$local_modeDescription';" .
						"document.getElementsByName('passingscore')[0].value='$mta_assignment->passingScore';" .
						"document.getElementsByName('totalpoints')[0].value='$mta_assignment->totalPoints';" .
						"document.getElementsByName('timelimit')[0].value='$mta_assignment->timeLimit';" .
						"document.getElementsByName('starttime')[0].value='$local_start';" .
						"document.getElementsByName('endtime')[0].value='$local_end';" .
						"document.getElementsByName('policy')[0].value='$local_policy';}";
				$attributes='onclick="'.$attributes.'"';		
				if ($assignmentid == 0) {
					if ($counter == 0) {
						$attributes = $attributes;
						$checked = 'checked="checked"';
						$default_index = 0;
					} else {
						$checked = '';
					}
				} else {
					if ($mta_assignment->id == $assignmentid) {
						$attributes = $attributes;
						$checked = 'checked="checked"';
						$default_index = $counter;
					} else {
						$attributes = $attributes." disabled";
						$checked = 'disabled="disabled"';
					}
				}
				
				if ($default_index >= 0 && empty($script)) {
					$script="<script>" .
							"document.getElementsByName('assignmentid')[0].value='$mta_assignment->id';" .
							"document.getElementsByName('name')[0].value='$local_name';" .
							"document.getElementsByName('assignmentmode')[0].value='$mta_assignment->mode';" .
							"document.getElementsByName('modedescription')[0].value='$local_modeDescription';" .
							"document.getElementsByName('passingscore')[0].value='$mta_assignment->passingScore';" .
							"document.getElementsByName('totalpoints')[0].value='$mta_assignment->totalPoints';" .
							"document.getElementsByName('timelimit')[0].value='$mta_assignment->timeLimit';" .
							"document.getElementsByName('starttime')[0].value='$local_start';" .
							"document.getElementsByName('endtime')[0].value='$local_end';" .
							"document.getElementsByName('policy')[0].value='$local_policy';".
							"</script>";
				}
				
				$counter = $counter + 1;
				
				$mform->addElement('html', "<td align='left' class='cell c0'>");
				$mform->addElement('html', "<input type='radio' name='assgnid' value='$mta_assignment->id' $attributes $checked/>&nbsp;&nbsp;<strong>$mta_assignment->name</strong>");
				$mform->addElement('html', "</td>");
				$mform->addElement('html', "<td align='center' class='cell c0'>$mta_assignment->modeDescription</td>");
				if ($mta_assignment->start != null && $mta_assignment->end != null) {
					$mform->addElement('html', "<td align='center' class='cell c0'>".date("d/m/y h:i A",$mta_assignment->start/1000)." - ".date("d/m/y h:i A",$mta_assignment->end/1000)."</td>");
				} else if ($mta_assignment->start != null) {
					$mform->addElement('html', "<td align='center' class='cell c0'>After ".date("d/m/y h:i A",$mta_assignment->start/1000)."</td>");
				} else if ($mta_assignment->end != null) {
					$mform->addElement('html', "<td align='center' class='cell c0'>Before ".date("d/m/y h:i A",$mta_assignment->end/1000)."</td>");
				} else {
					$mform->addElement('html', "<td align='center' class='cell c0'>Unlimited</td>");
				}
				
				if ($mta_assignment->timeLimit != '-1') {
					$mform->addElement('html', "<td align='center' class='cell c0'>$mta_assignment->timeLimit minutes</td>");
				} else {
					$mform->addElement('html', "<td align='center' class='cell c0'>N/A</td>");
				}
				$mform->addElement('html', "</tr>");
			}
			$mform->addElement('html', "</table></div>");
			

			$mform->addElement('hidden', 'assignmentid', $mta_assignments[$default_index]->id);
			$mform->addElement('hidden', 'name', $mta_assignments[$default_index]->name);
			$mform->addElement('hidden', 'assignmentmode', $mta_assignments[$default_index]->mode);
			$mform->addElement('hidden', 'modedescription', $mta_assignments[$default_index]->modeDescription);
			$mform->addElement('hidden', 'passingscore', $mta_assignments[$default_index]->passingScore);
			$mform->addElement('hidden', 'totalpoints', $mta_assignments[$default_index]->totalPoints);
			$mform->addElement('hidden', 'timelimit', $mta_assignments[$default_index]->timeLimit);
			$mform->addElement('hidden', 'starttime', $mta_assignments[$default_index]->start);
			$mform->addElement('hidden', 'endtime', $mta_assignments[$default_index]->end);
			$mform->addElement('hidden', 'policy', $mta_assignments[$default_index]->policy);

			$mform->addElement('html', $script);

			$this->standard_coursemodule_elements();
			$this->add_action_buttons();
		} else {
			print_error('course_not_mapped', 'mapleta', $errorURL);
		}
	}

	function data_preprocessing(& $default_values) {
	}

	function validation($data, $files) {
	}

}
?>