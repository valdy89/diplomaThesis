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
 * English strings for mapletadp
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_mapletadp
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Maple T.A.';
$string['modulenameplural'] = 'Maple T.A.s';
$string['modulename_help'] = 'Proprietary Maple T.A. connector based on the webservices, designed for Diploma thesis.';
$string['mapletadpfieldset'] = 'Custom example fieldset';
$string['mapletadpname'] = 'mapletadp name';
$string['mapletadpname_help'] = 'This is the content of the help tooltip associated with the mapletadpname field. Markdown syntax is supported.';
$string['mapletadp'] = 'mapletadp';
$string['pluginadministration'] = 'Mapleta administration';
$string['pluginname'] = 'Maple T.A.';

$string['assignmentName'] = 'Assignment name';
$string['className'] = 'Class name';
$string['choose'] = ' -- Select class --';
$string['selectAssignment'] = 'You have to select assignment';
$string['selectClass'] = 'You have to select class';


//configuration

$string['protocol'] = 'Protocol';
$string['protocoldescription'] = 'The network protocol (http or https) you type in the address bar of the browser to access your Maple T.A. server.';
$string['server'] = 'Server Name';
$string['serverdescription'] = 'The server name you type in the address bar of the browser to access your Maple T.A. server. Include the port number if needed.';
$string['context'] = 'Context';
$string['contextdescription'] = 'The application name you type in the address bar of the browser after the server and port number to access your Maple T.A. server.';
$string['secret'] = 'Shared Password';
$string['secretdescription'] = 'A password shared by Moodle and Maple T.A. that will be used to encrypt Moodle <--> Maple T.A. communications.';
$string['timeout'] = 'SessionID timeout';
$string['timeoutdescription'] = 'Parametr sets time (minutes; max. 200) how long will be Session ID stored in Moodle.';

$string['availableassignments'] = 'Available Maple T.A.';
$string['settingsheading'] = 'Maple T.A. connection setup';
$string['settingsinfo'] = 'Please setup following setting for Maple T.A. connection.';

$string['synchronization'] = 'List synchronization';
$string['startassignment'] = 'Start assignment';
$string['waitplease'] = 'Wait please - assignment will be loaded in a second.';

$string['notmonitored'] = 'WS was not called.';

$string['mode'] = 'Assignment type';
$string['mode0'] = 'Proctore test';
$string['mode1'] = 'Homework or quiz';
$string['mode2'] = 'Practice';
$string['mode3'] = 'Mastery assignment';
$string['mode4'] = 'Study session';

$string['totalpoints'] = 'Total Points';
$string['passingscore'] = 'Passing score';
$string['start'] = 'Start';
$string['end'] = 'End';
$string['timelimit'] = 'Timelimit (minutes)';
$string['notsetvalue'] = 'Not set';


$string['no'] = 'No';
$string['yes'] = 'Yes';
$string['showonlyexternal'] = 'Only Moodle users';
$string['showonlyexternaldescription'] = 'Setting will show grades in gradebook of all users and grades from Maple T.A. (choice Yes) or only Moodle users(choice No).';

