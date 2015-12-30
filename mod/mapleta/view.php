<?php

// $Id: view.php,v 1.4 2006/08/28 16:41:20 mark-nielsen Exp $
/**
 * This page prints a particular instance of mapleta
 *
 * @author
 * @version $Id: view.php,v 1.4 2006/08/28 16:41:20 mark-nielsen Exp $
 * @package mapleta
 * */
/// (Replace mapleta with the name of your module)
//	require_once('../../course/lib.php');
//
require_once("../../config.php");
require_once("lib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$n = optional_param('n', 0, PARAM_INT);  // mapleta ID
$mapleta = null;

$url = new moodle_url('/mapleta/view.php', array('id' => $id, 'n' => $n));
$PAGE->set_url($url);


if ($id) {
    $cm = get_coursemodule_from_id('mapleta', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $mapletadp = $DB->get_record('mapleta', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $mapletadp = $DB->get_record('mapleta', array('id' => $n), '*', MUST_EXIST);

    $course = $DB->get_record('course', array('id' => $mapletadp->course), '*', MUST_EXIST);

    $cm = get_coursemodule_from_instance('mapleta', $mapletadp->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

$errorURL = "$CFG->wwwroot/course/view.php?id=$course->id";

require_login($course, true, $cm);


/// Print the page header

if ($course->category) {
    $navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
} else {
    $navigation = '';
}

$strmapletas = get_string("modulenameplural", "mapleta");
$strmapleta = get_string("modulename", "mapleta");



/// Print the main part of the page



if (!$course_mapping = mapleta_get_record('mapleta_course_map', 'courseid', $course->id)) {
    print_error('course_not_mapped', 'mapleta', $errorURL);
}

if (!mapleta_is_student($course->id)) {
    print_error('not_authorized_for_action', 'mapleta', $errorURL);
}


$connect = mapleta_ws_connect_to_class($course->id, $course_mapping->classid);

if ($connect == null) {
    print_error('error_connecting_to_ta', 'mapleta', $errorURL);
} else if ($connect->code == 1) {
    print_error($connect->message, 'mapleta', $errorURL);
}

$status = new mapleta_status_response();
$mta_assignments = mapleta_ws_get_assignment($course_mapping->classid, $mapleta->assignmentid, $connect->session, $status);
var_dump($mta_assignments);
mapleta_ws_disconnect($connect->session, $connect);

if ($status->code == 1) {
    print_error($status->message, 'mapleta', $errorURL);
} else if ($status->code == 100) {
    print_error('no_assignments_in_class', 'mapleta', $errorURL);
}

$the_assignment = $mta_assignments[0];

if ($mapleta->name != $the_assignment->name) {
    $name_changed = true;
    $old_name = $mapleta->name;
} else {
    $name_changed = false;
}

$mapleta->instance = $mapleta->id;
$mapleta->name = $the_assignment->name;
$mapleta->assignmentmode = $the_assignment->mode;
$mapleta->modedescription = $the_assignment->modeDescription;
$mapleta->passingscore = $the_assignment->passingScore;
$mapleta->totalpoints = $the_assignment->totalPoints;
$mapleta->timelimit = $the_assignment->timeLimit;
$mapleta->starttime = $the_assignment->start;
$mapleta->endtime = $the_assignment->end;
$mapleta->policy = $the_assignment->policy;

mapleta_update_instance($mapleta);

//	if ($name_changed) {
rebuild_course_cache($course->id);
//	} 
echo $OUTPUT->header();
echo "<br/><h2 class='main help'><img src='$CFG->wwwroot/mod/mapleta/icon.gif' alt=''/>$mapleta->name</h2>";

$params = "wsExternalData=" . $mapleta->id .
        "&wsActionID=assignment" .
        "&wsClassId=" . $course_mapping->classid .
        "&wsCourseId=" . $course_mapping->courseid .
        "&className=" . mapleta_urlencode($course_mapping->classname) .
        "&testName=" . mapleta_urlencode($mapleta->name) .
        "&testId=" . $mapleta->assignmentid;

$command = "window.open('$CFG->wwwroot/mod/mapleta/launcher_form.php?$params', 'Assignment', 'menubar=0,location=0,scrollbars,status,resizable,width=1024,height=800');";
$button = "<input type='submit' value='Start the Assignment' onclick=\"$command\"/>";

print ("<div style='text-align: center'>");
if ($name_changed) {
    print ("<span class='mapleta-config'><strong>Note: The assignment has been recently renamed from '" . $old_name . "' to '" . $the_assignment->name . "'.</strong></span><br/><br/>");
}
print ("<table class='mapleta-config generaltable boxaligncenter' cellpadding='5'>");
print ("<tr>");
print ("<td align='left' class='cell c0'><strong>Type</strong></td><td align='center' class='cell c0'>$mapleta->modedescription</td>");
print ("</tr>");
if ($mapleta->passingscore > 0) {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Passing Score</strong></td><td align='center' class='cell c0'>$mapleta->passingscore</td>");
    print ("</tr>");
} else {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Passing Score</strong></td><td align='center' class='cell c0'>Not pass/fail</td>");
    print ("</tr>");
}
print ("<tr>");
print ("<td align='left' class='cell c0'><strong>Total Points</strong></td><td align='center' class='cell c0'>$mapleta->totalpoints</td>");
print ("</tr>");
if ($mapleta->timelimit <= 0) {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Time permitted</strong></td><td align='center' class='cell c0'>No time limit</td>");
    print ("</tr>");
} else {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Time permitted</strong></td><td align='center' class='cell c0'>Time limit is $mapleta->timelimit minutes</td>");
    print ("</tr>");
}
if ($mapleta->starttime == 0 && $mapleta->endtime == 0) {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Availability</strong></td><td align='center' class='cell c0'>Unlimited</td>");
    print ("</tr>");
}
if ($mapleta->starttime > 0) {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Available After</strong></td><td align='center' class='cell c0'>" . date("d/m/y h:i A", $mapleta->starttime / 1000) . "</td>");
    print ("</tr>");
}
if ($mapleta->endtime > 0) {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Available Before</strong></td><td align='center' class='cell c0'>" . date("d/m/y h:i A", $mapleta->endtime / 1000) . "</td>");
    print ("</tr>");
}
if ($mapleta->policy != "") {
    print ("<tr>");
    print ("<td align='left' class='cell c0'><strong>Restrictions</strong></td><td align='center' class='cell c0'><a href=\"javascript:{alert('" . "A student may only take this test if he/she:\\n\\n" . mapleta_urlencode($mapleta->policy) . "')}\">Click here to see the restrictions</a></td>");
    print ("</tr>");
}
print ("</table></div>");

print ("<div style='text-align: center'>");
print ("<table cellpadding='2' class='mapleta-config' width='100%'>");
print ("<tr>");
print ("<td colspan='2' align='center'>&nbsp;</td>");
print ("</tr>");
print ("<tr>");
print ("<td colspan='2' align='center'>$button</td>");
print ("</tr>");
print ("</table></div>");

echo $OUTPUT->footer();
?>
