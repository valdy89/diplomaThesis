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

    const MAPLE_ROLE_ADMIN = "ADMINISTRATOR";
    const MAPLE_ROLE_STUDENT = "STUDENT";
    const MAPLE_ROLE_PROCTOR = "PROCTOR";
    const MAPLE_ROLE_INSTRUCTOR = "INSTRUCTOR";

    public function __construct(\moodle_database $db, \stdClass $cfg, \stdClass $user) {
        parent::__construct($db, $cfg, $user);
        $this->model = new \mod_mapletadp\model\Connector($db, $cfg);
    }

    /*
      public function connectMaple($user, $role, $classID = -1) {
      $params = $this->model->getConnectParams($user, $role, $classID);
      if ($params !== false) {
      $response = $this->model->sendRequest('ws/connect', $params);
      $dataset = $this->model->getConnectResponse($response);
      if ($dataset !== false) {

      return $dataset;
      }
      }
      return false;
      }
     */

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

    public function getGrades($params) {
        
    }

    public function monitor($all = null, $tomcat = null, $db = null, $maple = null) {
        
    }

    public function pingServer($echo) {
        $params = $this->model->pingServerParams('Running');
        if ($params !== false) {
            $response = $this->model->sendRequest('ws/ping', $params);
            $dataset = $this->model->validatePingResponse($response);
            if ($dataset !== false) {
                return 'running';
            }
        }
        return 'down';
    }

}
