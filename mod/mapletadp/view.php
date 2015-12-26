<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of mapletadp
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_mapletadp
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Replace mapletadp with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$id = required_param('id', PARAM_INT); // Course_module ID, or
$n = optional_param('n', 0, PARAM_INT);  // ... mapletadp instance ID - it should be named as the first character of the module.
$run = optional_param('run', false, PARAM_BOOL);
if ($id) {
    $cm = get_coursemodule_from_id('mapletadp', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $mapletadp = $DB->get_record('mapletadp', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $mapletadp = $DB->get_record('mapletadp', array('id' => $n), '*', MUST_EXIST);

    $course = $DB->get_record('course', array('id' => $mapletadp->course), '*', MUST_EXIST);

    $cm = get_coursemodule_from_instance('mapletadp', $mapletadp->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}



require_login($course, true, $cm);

// Print the page header.

$PAGE->set_url('/mod/mapletadp/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($mapletadp->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->requires->js('/mod/mapletadp/js/jquery-2.1.4.js');

$showform = false;
if ($run) {
    $PAGE->requires->js('/mod/mapletadp/js/start_assignment.js');
}
/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('mapletadp-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($mapletadp->intro) {
    echo $OUTPUT->box(format_module_intro('mapletadp', $mapletadp, $cm->id), 'generalbox mod_introbox', 'mapletadpintro');
}

if (isset($mapletadp)) {
    $controller = new mod_mapletadp\controller\Mapleta($DB, $CFG, $USER);
    $controllerData = new mod_mapletadp\controller\MapleData($DB, $CFG, $USER);
    $assignment = $controllerData->getAssignments($mapletadp->classid, $mapletadp->assignmentid);
    //var_dump($assignment);
// Replace the following lines with you own code.
    echo $OUTPUT->heading($assignment->name);

    $mapletaModel = new mod_mapletadp\model\Mapleta($DB, $CFG);
    echo '<a href="#" id="startLink">aaaa</a>';
    $PAGE->requires->js('/mod/mapletadp/js/create_assignment_popup.js');
    echo $mapletaModel->startAssignmentForm($assignment, $course->id);
}

// Finish the page.
echo $OUTPUT->footer();
