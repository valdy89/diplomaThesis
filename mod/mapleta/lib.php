<?php


// 
/**
 * Library of functions and constants for module mapleta
 *
 * @author
 * @version $Id: lib.php,v 1.4 2006/08/28 16:41:20 mark-nielsen Exp $
 * @package mapleta
 **/

define('RES_WS_PING', '/ws/ping');
define('RES_WS_CONNECT', '/ws/connect');
define('RES_WS_DISCONNECT', '/ws/disconnect');
define('RES_WS_GET_CLASSES', '/ws/class');
define('RES_WS_CREATE_CLASS', '/ws/createclass');
define('RES_WS_GET_ASSIGNMENTS', '/ws/assignment');
define('RES_WS_LAUNCH', '/ws/launcher');
define('STR_WS_PING', 'PING YOU');

class mapleta_status_response {
	public $session, $code, $message;
}

class mapleta_assignment_response {
	public $classId,
	$id,
	$name,
	$mode,
	$modeDescription,
	$passingScore,
	$totalPoints,
	$weight,
	$start,
	$end,
	$timeLimit,
	$policy;
}

class mapleta_grade_request {
	public $userLogin,
    $score,
    $dateGraded,
    $externalData;
}

class mapleta_gradebook_request extends mapleta_assignment_response {
	public $list;
}

class mapleta_ping_response {
	public $value;
}

class mapleta_class_response {
	public $id, $name, $instructor;
}

class mapleta_create_class_response {
	public $id, $name;
}

class mapleta_course_map {
	public $courseid, $classid, $classname;
}

function mapleta_count_records($table, $field1='', $value1='', $field2='', $value2='', $field3='', $value3='') {
	global $DB;
	
	$conditions = array();
	
	if ($field1 != '') {
		$conditions[$field1] = $value1;		
	}
	
	if ($field2 != '') {
		$conditions[$field2] = $value2;		
	}
	
	if ($field3 != '') {
		$conditions[$field3] = $value3;		
	}
	
	return $DB->count_records($table, $conditions);
}

function mapleta_get_record($table, $field1, $value1, $field2='', $value2='', $field3='', $value3='', $fields='*') {
	
	global $DB;
	
	$conditions = array();
	
	if ($field1 != '') {
		$conditions[$field1] = $value1;		
	}
	
	if ($field2 != '') {
		$conditions[$field2] = $value2;		
	}
	
	if ($field3 != '') {
		$conditions[$field3] = $value3;		
	}
	
	return $DB->get_record($table, $conditions, $fields, IGNORE_MISSING);
}

function mapleta_delete_records($table, $field1, $value1, $field2='', $value2='', $field3='', $value3='') {
	
	global $DB;
	
	$conditions = array();
	
	if ($field1 != '') {
		$conditions[$field1] = $value1;		
	}
	
	if ($field2 != '') {
		$conditions[$field2] = $value2;		
	}
	
	if ($field3 != '') {
		$conditions[$field3] = $value3;		
	}
	
	return $DB->delete_records($table, $conditions);
}

function mapleta_insert_record($table, $dataobject, $returnid=true, $primarykey='id') {
	
	global $DB;
	
	return $DB->insert_record($table, $dataobject, $returnid, false);
}

function mapleta_update_record($table, $dataobject) {
	
	global $DB;
	
	return $DB->update_record($table, $dataobject, false);
}

/**
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, null if doesn't know
 */
function mapleta_supports($feature) {
    switch($feature) {
//        case FEATURE_GROUPS:                  return true;
//        case FEATURE_GROUPINGS:               return true;
//        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return false;
//        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
//        case FEATURE_GRADE_HAS_GRADE:         return true;
//        case FEATURE_GRADE_OUTCOMES:          return true;
//        case FEATURE_GRADE_HAS_GRADE:         return true;
//        case FEATURE_BACKUP_MOODLE2:          return true;

        default: return null;
    }    
}


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted mapleta record
 **/
function mapleta_add_instance($mapleta) {

	# May have to add extra stuff in here #

	$mapleta->timemodified = time();

	$id = mapleta_insert_record("mapleta", $mapleta);
	$mapleta->id = $id;
	
	mapleta_grade_item_update($mapleta);

	return $id;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function mapleta_update_instance($mapleta) {

	$mapleta->timemodified = time();
	
	$mapleta->id= $mapleta->instance;

	# May have to add extra stuff in here #

	$id = mapleta_update_record("mapleta", $mapleta);

	mapleta_grade_item_update($mapleta);
	
	return $id;
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function mapleta_delete_instance($id) {

	
	if(!$mapleta= mapleta_get_record("mapleta", "id", "$id")) {
		return false;
	}

	$result= true;

	# Delete any dependent records here #

	if(!mapleta_delete_records("mapleta", "id", "$mapleta->id")) {
		$result= false;
	} 
	
	mapleta_grade_item_delete($mapleta);

	return $result;
}

/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function mapleta_user_outline($course, $user, $mod, $mapleta) {
	return null;
}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function mapleta_user_complete($course, $user, $mod, $mapleta) {
	return true;
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in mapleta activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function mapleta_print_recent_activity($course, $isteacher, $timestart) {
	global $CFG;

	return false; //  True if anything was printed, otherwise false
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function mapleta_cron() {
	global $CFG;

	return true;
}

/**
 * Must return an array of grades for a given instance of this module,
 * indexed by user.  It also returns a maximum allowed grade.
 *
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $mapletaid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
function mapleta_grades($mapletaid) {
	return NULL;
}

/**
 * Create grade item for given assignment
 *
 * @param object $assignment object with extra cmidnumber
 * @param mixed optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return int 0 if ok, error code otherwise
 */
function mapleta_grade_item_update($mapleta, $grades=NULL) {
    global $CFG;
    
    if ($mapleta->assignmentmode == -1 || $mapleta->assignmentmode == 2 || $mapleta->assignmentmode == 4) {
    	return true;
    }
    
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }    

    $itemdetails = array(	'itemname'	=>	$mapleta->name, 
							'idnumber'	=>	$mapleta->assignmentid, 
							'gradetype'	=>	GRADE_TYPE_VALUE, 
							'grademin'	=>	0, 
							'grademax'	=>	$mapleta->totalpoints, 
							'gradepass'	=>	$mapleta->passingscore);

	$source			=	'mod/mapleta'; 
	$courseid		=	$mapleta->course; 
	$itemtype		=	'mod'; 
	$itemmodule		=	'mapleta'; 
	
	$iteminstance	=	null; 
	
	$itemnumber		=	$mapleta->assignmentid;
	
    return grade_update($source, $courseid, $itemtype, $itemmodule, $iteminstance, $itemnumber, $grades, $itemdetails);
}

function mapleta_grade_item_delete($mapleta) {
    global $CFG;

	$mapleta_count = mapleta_count_records('mapleta', 'assignmentid', $mapleta->assignmentid, 'course', $mapleta->course);
	if ($mapleta_count > 0) { 
    	return true;
    }
    
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }    

    $itemdetails = array('deleted'=>1);

	$source			=	'mod/mapleta'; 
	$courseid		=	$mapleta->course; 
	$itemtype		=	'mod'; 
	$itemmodule		=	'mapleta'; 
	
	$iteminstance	=	null; 
	
	$itemnumber		=	$mapleta->assignmentid;
	
    return grade_update($source, $courseid, $itemtype, $itemmodule, $iteminstance, $itemnumber, NULL, $itemdetails);
}


/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of mapleta. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $mapletaid ID of an instance of this module
 * @return mixed boolean/array of students
 **/
function mapleta_get_participants($mapletaid) {
	return false;
}

/**
 * This function returns if a scale is being used by one mapleta
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $mapletaid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function mapleta_scale_used($mapletaid, $scaleid) {
	$return= false;
	return $return;
}

//////////////////////////////////////////////////////////////////////////////////////
/// Any other mapleta functions go here.  Each of them must have a name that
/// starts with mapleta_

function mapleta_context($courseId) {
   	return get_context_instance(CONTEXT_COURSE, $courseId);
}

function mapleta_is_administrator($courseId) {
	global $USER;
	return has_capability('block/mapleta_course_tools:managesystem', mapleta_context($courseId), $USER->id);
}

function mapleta_is_teacher($courseId) {
	global $USER;
	return has_capability('block/mapleta_course_tools:manageclass', mapleta_context($courseId), $USER->id);
}

function mapleta_is_proctor($courseId) {
	global $USER;
	return has_capability('block/mapleta_course_tools:proctorassignment', mapleta_context($courseId), $USER->id);
}

function mapleta_is_student($courseId) {
	global $USER;
	return has_capability('block/mapleta_course_tools:participate', mapleta_context($courseId), $USER->id);
}

function mapleta_get_role($courseId) {
	if(mapleta_is_administrator($courseId)) {
		return 'ADMINISTRATOR';
	}
	if(mapleta_is_teacher($courseId)) {
		return 'INSTRUCTOR';
	}
	if(mapleta_is_proctor($courseId)) {
		return 'PROCTOR';
	}
	if(mapleta_is_student($courseId)) {
		return 'STUDENT';
	}
	return 'UNSET';
}

function mapleta_do_ping_request($url, $data, $optional_headers= null) {
		$response='';
	try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $optional_headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$response = $response . curl_exec($ch);
		
		curl_close($ch);
		return $response;
	} catch(Exception $ex) {
		return $response;
	}
	}

function mapleta_do_post_request($url, $data, $optional_headers= null) {
	$response='';
	try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $optional_headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$response = $response . curl_exec($ch);
		
		curl_close($ch);
		return $response;
	} catch(Exception $ex) {
		return $response;
	}
}

function mapleta_do_xml_post_request($url, $data, $optional_headers= null) {
	$the_headers = array();
	$the_headers[] = "Content-Type: text/xml";
	if ($optional_headers != null) {
		$the_headers[] = $optional_headers;
	}
	return mapleta_do_post_request($url, $data, $the_headers);
}

function mapleta_do_xml_ping_request($url, $data, $optional_headers= null) {
	$the_headers = array();
	$the_headers[] = "Content-Type: text/xml";
	if ($optional_headers != null) {
		$the_headers[] = $optional_headers;
	}
	return mapleta_do_ping_request($url, $data, $the_headers);
}

function mapleta_get_response_from_xml($response, $elementClass, $status, $subelementClass = null) {

	$xml= new XMLReader();
	$xml->XML($response);

	$level= 0;
	$elementLevel= false;
	$subelementLevel= false;
	$statusLevel= false;
	$out = new object();
	$out->list= array();
	$theElement= null;
	$theSubelement= null;
	$name= null;
	$value= null;

	while($xml->read()) {
		switch($xml->nodeType) {
			case XMLReader :: END_ELEMENT :
				if($xml->name == 'status' && $statusLevel) {
					$statusLevel= false;
				} else if($xml->name == 'element') {
					$out->list[]= $theElement;
					$elementLevel = false;
				} else if($xml->name == 'subelement') {
					$theElement->list[]= $theSubelement;
					$subelementLevel = false;
				}
				$level -= 1;
				break;
			case XMLReader :: ELEMENT :
				$level += 1;
				if($xml->name == 'Request' || $xml->name == 'Response' || $xml->name == 'list') {
					break;
				} else
					if($xml->name == 'status' && $level == 2) {
						$statusLevel= true;
						break;
					} else
						if($xml->name == 'element' && !$xml->isEmptyElement) {
							eval("\$theElement = new \$elementClass();");
							$theElement->list = array();
							$elementLevel = true;
						} else if($xml->name == 'subelement' && !$xml->isEmptyElement) {
							eval("\$theSubelement = new \$subelementClass();");
							$subelementLevel = true;
						} else
							if(!$xml->isEmptyElement) {
								$name= $xml->name;
								$xml->read();
								$value= $xml->value;
								if($statusLevel) {
									$status-> {
										$name }
									= $value;
								} else if ($subelementLevel) {
									$theSubelement-> {
										$name }
									= $value;
								} else if ($elementLevel) {
									$theElement-> {
										$name }
									= $value;
								} else {
									$out-> {
										$name }
									= $value;
								} 
							}
				break;
		}
	}
	$error= 'OK';
	return $out;
}


function mapleta_xmlencode($string){
	return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
}

function mapleta_urlencode($string){
	return rawurlencode($string);
}

function mapleta_urldecode($string){
	return rawurldecode($string);
}

function mapleta_ws_get_signature($timestamp) {
	global $CFG;
	$signature=base64_encode(md5($timestamp.$CFG->mapleta_secret, true));
	return $signature;
}

function mapleta_ws_signature() {
	global $CFG;
	$timestamp = time()*1000;
	return '<timestamp>'.$timestamp.'</timestamp><signature>'.mapleta_xmlencode(mapleta_ws_get_signature($timestamp)).'</signature>';
}

function mapleta_ws_signature_fields() {
	global $CFG;
	$timestamp = time()*1000;
	return 	'<input type="hidden" name="timestamp" value="'.$timestamp.'">'.
			'<input type="hidden" name="signature" value="'.mapleta_xmlencode(mapleta_ws_get_signature($timestamp)).'">';
}

function mapleta_ws_check_signature($timestamp, $signature) {
	global $CFG;
	
	$current_timestamp = time()*1000;
	
	if ((abs($current_timestamp - $timestamp)/1000) > 120) {
		return false;
	}
	$correct_signature=mapleta_ws_get_signature($timestamp);
	if ($correct_signature == $signature) {
		return true;
	} else {
		return false;
	}
}

function mapleta_ws_connect($courseId) {
	global $USER, $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_CONNECT;

	$firstname = mapleta_xmlencode($USER->firstname);
	$lastname = mapleta_xmlencode($USER->lastname);
	$username = mapleta_xmlencode($USER->username);
	$email = mapleta_xmlencode($USER->email);
	$idnumber = mapleta_xmlencode($USER->idnumber);
	$role = mapleta_xmlencode(mapleta_get_role($courseId));
	$signature = mapleta_ws_signature();
	$request =	"<Request>
					<firstName>$firstname</firstName>
					<middleName></middleName>
					<lastName>$lastname</lastName>
					<userLogin>$username</userLogin>
					<userEmail>$email</userEmail>
					<studentId>$idnumber</studentId>
					<userRole>$role</userRole>
					<classId>-1</classId>
					<courseId></courseId>
					$signature
				</Request>";
	
	$xml_response= mapleta_do_xml_post_request($url, $request);

	$status= new mapleta_status_response();
	
	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_status_response', $status);
	$array_response= $out->list;

	return $status;
}

function mapleta_ws_ping() {
	global $USER, $CFG;
	$result = false;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_PING;

	$value = mapleta_xmlencode(STR_WS_PING);
	$signature = mapleta_ws_signature();
	
	$request =	"<Request>
					<value>$value</value>
					$signature
				</Request>";
	
	$xml_response= mapleta_do_xml_ping_request($url, $request);
	
	if ($xml_response) {
		$status= new mapleta_status_response();
		$out = mapleta_get_response_from_xml($xml_response, 'mapleta_ping_response', $status);
		$array_response= $out->list;
		if (count($array_response) > 0) {
			$result = ($array_response[0]->value == STR_WS_PING);	
		}
	}
	
	return $result;
}

function mapleta_ws_connect_to_class($courseId, $classId) {
	global $USER, $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_CONNECT;

	$firstname = mapleta_xmlencode($USER->firstname);
	$lastname = mapleta_xmlencode($USER->lastname);
	$username = mapleta_xmlencode($USER->username);
	$email = mapleta_xmlencode($USER->email);
	$idnumber = mapleta_xmlencode($USER->idnumber);
	$role = mapleta_xmlencode(mapleta_get_role($courseId));
	$classId = mapleta_xmlencode($classId);
	$courseId = mapleta_xmlencode($courseId);
	$signature = mapleta_ws_signature();
	
	$request =	"<Request>
					<firstName>$firstname</firstName>
					<middleName></middleName>
					<lastName>$lastname</lastName>
					<userLogin>$username</userLogin>
					<userEmail>$email</userEmail>
					<studentId>$idnumber</studentId>
					<userRole>$role</userRole>
					<classId>$classId</classId>
					<courseId>$courseId</courseId>
					$signature
				</Request>";
				
	$xml_response= mapleta_do_xml_post_request($url, $request);

	$status= new mapleta_status_response();

	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_status_response', $status);
	$array_response= $out->list;

	return $status;
}

function mapleta_ws_disconnect($mapleta_session, $status) {
	global $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_DISCONNECT;
	$signature = mapleta_ws_signature();

	$request= "<Request>$signature</Request>";

	$xml_response= mapleta_do_xml_post_request($url, $request, "Cookie: JSESSIONID=$mapleta_session");

	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_status_response', $status);
	$array_response= $out->list;

	return $array_response;
}

function mapleta_ws_get_featured_classes($mapleta_session, $status) {
	global $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_GET_CLASSES;
	$signature = mapleta_ws_signature();
	
	$request= "<Request><classId>0</classId><featured>true</featured>$signature</Request>";

	$xml_response= mapleta_do_xml_post_request($url, $request, "Cookie: JSESSIONID=$mapleta_session");
	
	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_class_response', $status);
	$array_response= $out->list;

	return $array_response;
}

function mapleta_ws_get_all_classes($mapleta_session, $status) {
	global $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_GET_CLASSES;
	$signature = mapleta_ws_signature();
	
	$request= "<Request><classId>0</classId><featured>false</featured>$signature</Request>";
	
	$xml_response= mapleta_do_xml_post_request($url, $request, "Cookie: JSESSIONID=$mapleta_session");

	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_class_response', $status);
	$array_response= $out->list;

	return $array_response;
}

function mapleta_ws_create_class($mapleta_session, $parentClassId, $classId, $courseName, $courseId, $status) {
	global $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_CREATE_CLASS;

	$parentClassId= mapleta_xmlencode($parentClassId);
	$classId = mapleta_xmlencode($classId);
	$courseName = mapleta_xmlencode($courseName);
	$courseId = mapleta_xmlencode($courseId);
	$signature = mapleta_ws_signature();
	
	$request= 	"<Request> 
					<parentClassId>$parentClassId</parentClassId>
					<classId>$classId</classId>
					<courseName>$courseName</courseName>
					<courseId>$courseId</courseId>
					$signature
				</Request>";

	$xml_response= mapleta_do_xml_post_request($url, $request, "Cookie: JSESSIONID=$mapleta_session");

	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_create_class_response', $status);
	$array_response= $out->list;

	return $array_response;
}

function mapleta_ws_get_assignments($classId, $mapleta_session, $status) {
	global $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_GET_ASSIGNMENTS;

	$classId = mapleta_xmlencode($classId);
	$signature = mapleta_ws_signature();
	
	$request= "<Request><classId>$classId</classId><assignmentId>0</assignmentId>$signature</Request>";

	$xml_response= mapleta_do_xml_post_request($url, $request, "Cookie: JSESSIONID=$mapleta_session");

	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_assignment_response', $status);
	$array_response= $out->list;

	return $array_response;
}

function mapleta_ws_get_assignment($classId, $assignmentId, $mapleta_session, $status) {
	global $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.RES_WS_GET_ASSIGNMENTS;

	$classId = mapleta_xmlencode($classId);
	$signature = mapleta_ws_signature();
	
	$request= "<Request><classId>$classId</classId><assignmentId>$assignmentId</assignmentId>$signature</Request>";

	$xml_response= mapleta_do_xml_post_request($url, $request, "Cookie: JSESSIONID=$mapleta_session");

	$out = mapleta_get_response_from_xml($xml_response, 'mapleta_assignment_response', $status);
	$array_response= $out->list;

	return $array_response;
}

function mapleta_ws_launch($params) {
	global $CFG;
	$server=$CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context;
	$signature = mapleta_ws_signature_fields();
	
	echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>";
	echo "<html>";
		echo "<head>";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
		echo "<meta http-equiv='Content-Language' content='en' />";
		echo "<title>title</title>";
		echo "</head>";
		echo "<body onload='document.forms[0].submit();'>";
			echo "<form method='post' action='$server/ws/launcher'>";
				echo $signature;
				foreach ( $params as $name => $value ) {
	       			echo "<input type='hidden' name='$name' value='$value'>";
				}
			echo "</form>";
		echo "</body>";
	echo "</html>";
}

function mapleta_ws_send_success($message=null) {
	if ($message == null) {
		$message='OK';
	}
	echo "<Response><status><code>0</code><message>$message</message></status></Response>";
}

function mapleta_ws_send_empty($message) {
	echo "<Response><status><code>100</code><message>$message</message></status></Response>";
}

function mapleta_ws_send_error($message) {
	echo "<Response><status><code>1</code><message>$message</message></status></Response>";
}

function mapleta_ws_get_grades($classId, $mapleta_session, $status) {
	global $CFG;

	$url= $CFG->mapleta_protocol.'://'.$CFG->mapleta_server.':'.$CFG->mapleta_port.'/'.$CFG->mapleta_context.'/ws/grade';

	$classId = mapleta_xmlencode($classId);
	$signature = mapleta_ws_signature();
	
	$request= "<Request>" .
			"<classId>$classId</classId>" .
			"<userFilter></userFilter>" .
//			"<assignmentList>" .
//			"<id>4</id>" .
//			"</assignmentList>" .
			"<scoreType>1</scoreType>" .
			"$signature" .
			"</Request>";

	$xml_response= mapleta_do_xml_post_request($url, $request, "Cookie: JSESSIONID=$mapleta_session");

}


?>
