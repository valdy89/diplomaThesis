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
 * The main mapletadp configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_mapletadp
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Module instance settings form
 *
 * @package    mod_mapletadp
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_mapletadp_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $DB, $USER, $PAGE;
        $PAGE->requires->js('/mod/mapletadp/js/jquery-2.1.4.js');
        $PAGE->requires->js('/mod/mapletadp/js/dependency_assignments.js');

        $mform = $this->_form;


        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('mapletadpname', 'mapletadp'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'mapletadpname', 'mapletadp');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding the rest of mapletadp settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.
        $controllerData = new \mod_mapletadp\controller\MapleData($DB, $CFG, $USER);
        $classesListDB = $controllerData->getClassesForForm();
        $classesListZero = array(0 => get_string('choose', 'mapletadp'));
        $classes = array_merge($classesListZero, $classesListDB);
        $select = $mform->addElement('select', 'classId', get_string('className', 'mapletadp'), $classes);
        $mform->addRule('classId', null, 'required', null, 'client');
        $mform->addRule('classId', get_string('selectClass', 'mapletadp'), 'nonzero', null, 'client');
        if (isset($this->current->classid)) {
            $select->setSelected($this->current->classid);
        }
        $assignments = $controllerData->getAllAssignmentsForForm();
        $select2 = $mform->addElement('select', 'assignmentId', get_string('assignmentName', 'mapletadp'), $assignments);
        $mform->addRule('assignmentId', null, 'required', null, 'client');
        $mform->addRule('assignmentId', get_string('selectAssignment', 'mapletadp'), 'nonzero', null, 'client');
        if (isset($this->current->assignmentid)) {
            $select2->setSelected($this->current->assignmentid);
        }
        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }

}
