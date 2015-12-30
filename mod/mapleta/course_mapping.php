<?php


/*
 * Created on 20-Jul-09
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('../../config.php');
require_once("lib.php");
require_once($CFG->dirroot.'/mod/mapleta/lib.php');

global $COURSE;
global $CFG;

$id= required_param('id', PARAM_INT); // Course Module ID
$action= required_param('action', PARAM_ALPHA); // Action: create or delete
$mapping_type= optional_param('mapping_type', 0, PARAM_INT);
$featured_class= optional_param('featured_class', 0, PARAM_INT);
$existing_class= optional_param('existing_class', 0, PARAM_INT);

$url = new moodle_url('/mapleta/course_mapping.php', array('id'=>$id, 'action'=>$action, 'mapping_type'=>$mapping_type, 'featured_class'=>$featured_class, 'existing_class'=> $existing_class));
$PAGE->set_url($url);

require_login($id);

if($id) {
	if(!$course= mapleta_get_record('course', 'id', $id)) {
		print_error('incorrect_course_id','mapleta');
	}
} else {
	print_error('missing_course_id','mapleta');
}

$errorURL="$CFG->wwwroot/course/view.php?id=$course->id";

if($course->category) {
	$navigation= "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
} else {
	$navigation= '';
}



			
if(!$course_mapping= mapleta_get_record('mapleta_course_map', 'courseid', $id)) {
	if(mapleta_is_administrator($id) || mapleta_is_teacher($id)) {
		if(!$mapping_type) {

			$connect= mapleta_ws_connect($id);
            
			if($connect == null) {
				print_error('connection_error','mapleta', $errorURL);
			} else
				if($connect->code == 1) {
					print_error($connect->message,'mapleta', $errorURL);
				}

			$status_featured= new mapleta_status_response();
			$featured_mta_classes= mapleta_ws_get_featured_classes($connect->session, $status_featured);

			if($status_featured->code == 1) {
				mapleta_ws_disconnect($connect->session, $connect);
				print_error($status_featured->message, 'mapleta', $errorURL);
			}
			
			$status_all= new mapleta_status_response();
			$all_mta_classes= mapleta_ws_get_all_classes($connect->session, $status_all);
			
			if($status_all->code == 1) {
				mapleta_ws_disconnect($connect->session, $connect);
				print_error($status_all->message, 'mapleta', $errorURL);
			}
			
			mapleta_ws_disconnect($connect->session, $connect);
			
			echo "<br/>";
			echo "<form method='post' action='course_mapping.php'>";
			echo "<div style='text-align: center'>";
			
			echo "<input type='hidden' name='id' value='$id'/>";
			echo "<input type='hidden' name='action' value='$action'/>";
			echo "<table id='mapleta-config' class='generaltable boxaligncenter'  cellpadding='5'>";
			echo "<caption><strong>".get_string('course_is_not_mapped','mapleta')."<br/>".get_string('choose_mapping_options_below','mapleta')."</strong></caption>";
			echo "<tr><td colspan='2'>&nbsp;</td></tr>";

			if ($status_featured->code != 100) {
				echo "<tr>";
				echo "<td align='left'>";
				echo "<input type='radio' name='mapping_type' value='1' checked='checked'/>".get_string('create_new_child_class','mapleta');
				echo "</td>";
				echo "<td align='left'>";
				echo "<select name='featured_class'>";
				foreach($featured_mta_classes as $mta_class) {
					echo "<option value='$mta_class->id'>$mta_class->name</option>";
				}
				echo "</select>";
				echo "</td>";
				echo "</tr>";
			}

			if ($status_all->code != 100) {
				echo "<tr>";
				echo "<td align='left'>";
				echo "<input type='radio' name='mapping_type' value='2'/>".get_string('direct_class_mapping','mapleta');
				echo "</td>";
				echo "<td align='left'>";
				echo "<select name='existing_class'>";
				foreach($all_mta_classes as $mta_class) {
					echo "<option value='$mta_class->id'>$mta_class->name</option>";
				}
				echo "</select>";
				echo "</td>";
				echo "</tr>";
			}

			echo "<tr>";
			echo "<td align='left'>";
			echo "<input type='radio' name='mapping_type' value='3'/>".get_string('create_empty_class','mapleta');
			echo "</td>";
			echo "<td align='left'>";
			echo "&nbsp;";
			echo "</td>";
			echo "</tr>";
			echo "<tr><td colspan='2'>&nbsp;</td></tr>";
			echo "<tr><td colspan='2' align='center'><input type='submit' value='".get_string('map_the_course','mapleta')."'/></td></tr>";
			echo "</table>";
			echo "</div>";
			echo "</form>";

		} else { // $mapping_type is set
			$connect= mapleta_ws_connect($id);

			if($connect == null) {
				print_error('connection_error', 'mapleta', $errorURL);
			} else
				if($connect->code == 1) {
					print_error($connect->message, 'mapleta', $errorURL);
				}

			$status= new mapleta_status_response();
			$new_class= array();

			switch($mapping_type) {
				case 1 : //Create a child of the featured class.
					if($featured_class) {
						$new_class= mapleta_ws_create_class($connect->session, $featured_class, 0, $course->fullname, $course->id, $status);
					} else {
						print_error('missing_parent_class','mapleta', $errorURL);
					}
					break;

				case 2 : //Map to existing class
					if($existing_class) {
						if ($mapleta_course_map = mapleta_get_record('mapleta_course_map', 'classid', $existing_class)) {
							print_error('class_already_mapped', 'mapleta', $errorURL);
						} else {
							$new_class= mapleta_ws_create_class($connect->session, 0, $existing_class, $course->fullname, $course->id, $status);
						}
					} else {
						print_error('missing_class','mapleta', $errorURL);
					}
					break;

				case 3 : //Create brand new class
					$new_class= mapleta_ws_create_class($connect->session, 0, 0, $course->fullname, $course->id, $status);
					break;

				default :
					mapleta_ws_disconnect($connect->session, $connect);
					print_error('wrong_data_format','mapleta', $errorURL);
			}

			mapleta_ws_disconnect($connect->session, $connect);

			if($status->code == 1) {
				print_error($status->message, 'mapleta', $errorURL);
			} else {
				$db_object= new mapleta_course_map();
				$db_object->courseid= $course->id;
				$db_object->classid= $new_class[0]->id;
				$db_object->classname= mapleta_urlencode($new_class[0]->name);
				
//				var_dump($db_object);
				
				if(!mapleta_insert_record('mapleta_course_map', $db_object)) {
					print_error('error_storing_mapping','mapleta', $errorURL);
				} else {
					echo "<br/><center><strong>Course $course->fullname has been successfully mapped to Maple TA class '".$new_class[0]->name."'.</strong></center><br/>";
				}
			}
		}
	} else { // Not admin or teacher
		print_error('not_authorized_for_action', 'mapleta', $errorURL);
	}
} else
	if($action == 'delete') { // Course already mapped
		if(mapleta_is_administrator($id) || mapleta_is_teacher($id)) {
			echo "<form method='post' action='course_mapping.php'>";
			echo "<input type='hidden' name='id' value='$id'/>";
			echo "<input type='hidden' name='action' value='dodelete'/>";
			echo "<br/><center><strong>Course '$course->fullname' has been mapped to Maple T.A. class '". mapleta_urldecode($course_mapping->classname)."'.</strong></center><br/>";

			$mapleta_count = mapleta_count_records('mapleta', 'course', $id);
			if ($mapleta_count == 0) { 
				echo "<center><input type='submit' value='Delete mapping'/></center>";
		    } else {
		    	echo 	"<center>" .
		    			"<span id='mapleta-config'>" .
		    			"<font color='red'>" .
		    			"The course mapping cannot be deleted<br>" .
		    			"because there are Maple T.A. assignments still referenced from this course. <br>" .
		    			"Please remove all Maple T.A. assignment references and try again. <br>" .
		    			"</font>" .
		    			"</span>" .
		    			"</center>";
		    }

			
			echo "</form>";
		} else { // Not admin or teacher
			print_error('not_authorized_for_action', 'mapleta', $errorURL);
		}
	} else if($action == "dodelete") {
		if(mapleta_is_administrator($id) || mapleta_is_teacher($id)) {
			if (mapleta_delete_records("mapleta_course_map", "id", $course_mapping->id)) {
				echo "<br/><center><strong>Mapping for course $course->fullname has been deleted.</strong></center><br/>";
			} else {
				print_error('error_deleting_mapping', 'mapleta', $errorURL);
			}
		} else { // Not admin or teacher
			print_error('not_authorized_for_action', 'mapleta', $errorURL);
		}
	} else {
		echo "<br/><center><strong>Course $course->fullname has been mapped to Maple TA class '".$course_mapping->classname."'.</strong></center><br/>";
	}

?>
