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
 * Library of interface functions and constants for module mapletadp
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the mapletadp specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_mapletadp
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
global $CFG, $PAGE;

function local_mapleta_extend_navigation(global_navigation $navigation) {
    global $CFG, $PAGE;

    $navigation->add(get_string('menulink', 'local_mapleta'), $CFG->wwwroot . '/local/mapleta',navigation_node::TYPE_CUSTOM);

//Add children to nodeAwesome. Pretend we have a list "$myList" of links to add.
    //$nodeAwesome->add(, new moodle_url('/path/to/file/' . $myList[$i]), null, null, $myList[$i]);
//force the node open
    //$nodeAwesome->forceopen = true;    
}
