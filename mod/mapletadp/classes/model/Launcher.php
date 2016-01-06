<?php

namespace mod_mapletadp\model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 

*/class Launcher extends Base {

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
        $array['wsExternalData'] = $this->helper->stringEncode($this->user->id);
        $array['className'] = $class->name;
        $array['testName'] = $assignment->name;
        $array['testId'] = $assignment->mapleid;

        return $array;
    }
}