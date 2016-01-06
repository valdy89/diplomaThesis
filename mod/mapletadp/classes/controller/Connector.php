<?php

namespace mod_mapletadp\controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Connector
 *
 * @author valusek
 */
class Connector extends Base {

    private $model;


    public function __construct(\moodle_database $db, \stdClass $cfg, \stdClass $user) {
        parent::__construct($db, $cfg, $user);
        $this->model = new \mod_mapletadp\model\Connector($db, $cfg);
    }

    public function createClass($params) {
        
    }

    public function disconnectMaple($params) {
        
    }

    public function getAssignments($classID, $assigmentID, $user) {
        $assigments = $this->model->getAssignments($classID, $assigmentID, $user);
        return $assigments;
    }

    public function getClasses($featured = false, $openForRegistration = false) {
        $classes = $this->model->getClasses(0, $featured, $openForRegistration);

        return $classes;
    }

    public function getClass($classID) {
        $classes = $this->model->getClasses($classID, false, false);

        return $classes;
    }

    public function getGrades($classID) {
        $grades = $this->model->getGrades($classID);
        return $grades;
    }

    public function pingServer($echo) {
        $return = $this->model->pingServer('Running');
        
        return $return;
    }

}
