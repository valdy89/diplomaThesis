<?php

namespace mod_mapleta\controller;

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
        $this->model = new \mod_mapleta\model\Connector($db, $cfg);
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

    public function getClasses($classID, $featured = false, $openForRegistration = false) {
        $classes = $this->model->getClasses($classID, $featured, $openForRegistration);

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
