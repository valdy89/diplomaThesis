<?php

namespace mod_mapletadp\model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mapleta
 *
 * @author valusek
 */
class Mapleta extends Base {

    protected $model_connector;
    protected $model_mapledata;
    protected $user;

    public function __construct(\moodle_database $db, \stdClass $cfg) {
        GLOBAL $USER;

        parent::__construct($db, $cfg);
        $this->user = $USER;
        $this->model_connector = new Connector($db, $cfg);
        $this->model_mapledata = new MapleData($db, $cfg);
    }

    public function refreshAllData() {
        $classes = $this->model_connector->getClasses();
        $this->model_mapledata->setClasses($classes);

        if (count($classes) > 0 && count($classes['element']) > 0) {
            foreach ($classes['element'] as $class) {
                $assignments = $this->model_connector->getAssignments($class['id'], 0, $this->user);

                $this->model_mapledata->setAssignments($assignments, $class['id']);
            }
            return true;
        }
        return false;
    }

    public function startAssignmentForm($assignmentId, $courseId) {

        $param = $this->getAssignmentLauncherParams($assignmentId, $courseId);
        $action = $this->connectionBase . 'ws/launcher';
        $form = new \mod_mapletadp\view\StartAssignmentForm($param, $action);
        return $form->render();
    }

    private function getAssignmentLauncherParams($assignment, $courseId) {

        $array = $this->helper->getArray();
        $class = $this->model_mapledata->getClasses($assignment->classid);
        $array['signature'] = $this->helper->stringEncode($array['signature']);
        $array['wsFirstName'] = $this->helper->stringEncode($this->user->firstname);
        $array['wsMiddleName'] = '';
        $array['wsLastName'] = $this->helper->stringEncode($this->user->lastname);
        $array['wsUserLogin'] = $this->helper->stringEncode($this->user->username);
        $array['wsUserEmail'] = $this->helper->stringEncode($this->user->email);
        $array['wsStudentId'] = $this->helper->stringEncode($this->user->idnumber);
        $array['wsActionID'] = 'assignment';
        $array['wsUserRole'] = $this->helper->getRole($courseId, $this->user);
        $array['wsCourseId'] = $courseId;
        $array['wsClassId'] = $assignment->classid;
        $array['className'] = $class->name;
        $array['testName'] = $assignment->name;
        $array['testId'] = $assignment->mapleid;

        return $array;
    }
    
    public function getMonitors(){
        if(count($this->model_connector->monitors)){
            $return = array();
            foreach ($this->model_connector->monitors as $key => $value) {
                if($ret = $this->model_connector->monitor($value)){
                $return[$key] = $ret;
                }else{
                    $return[$key] = get_string('notmonitored','mod_mapleta');
                }
            }
            return $return;
        }
        return false;
    }
}
