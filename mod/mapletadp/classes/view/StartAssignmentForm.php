<?php

namespace mod_mapletadp\view;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); ///  It must be included from a Moodle page
}

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Formular pro odeslani notifikaci
 *
 * @author Valusek
 */
class StartAssignmentForm extends \moodleform {
    /* ======================================================================== */
    /* magic methods */
    /* ======================================================================== */

    /**
     * Konstruktor
     * 
     * @param mixed $customdata [optional] default null
     * 
     * @param mixed $action atribut akce formulare. [optional] default null
     * 
     * @param string $method [optional] default null
     *               
     * @param string $target [optional] default empty string
     * @param mixed $attributes [optional] default null
     * @param bool $editable [optional] default true
     */
    public function __construct($customdata = null, $action = null, $method = 'post', $target = '', $attributes = null, $editable = true) {
        parent::moodleform($action, $customdata, $method, $target, $attributes, $editable);
    }

    /* ======================================================================== */
    /* public methods */
    /* ======================================================================== */

    /**
     * Definice formulare
     * 
     * @global type $DB
     * @global type $CFG
     * @global type $USER
     * @global type $PAGE
     */
    public function definition() {
        global $DB, $CFG, $USER, $PAGE;

        $mform = & $this->_form;
        $id = required_param('id', PARAM_INT);
        $mform->setType('id', PARAM_INT);
        if (count($this->_customdata) > 0) {
            foreach ($this->_customdata as $key => $value) {
                $mform->addElement('hidden', $key, $value);
                $mform->setType($key, PARAM_RAW);
            }
        }

        $mform->addElement('hidden', 'action', 'start_assignment');
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('action', PARAM_RAW);
  }

    /* ======================================================================== */
    /* protected methods */
    /* ======================================================================== */


    /* ======================================================================== */
    /* private methods */
    /* ======================================================================== */
}
