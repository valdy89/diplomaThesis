<?php

namespace mod_mapletadp\controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MapleAssignment
 *
 * @author valusek
 */
class MapleData extends Base {

    private $model;

    public function __construct(\moodle_database $db, \stdClass $cfg, \stdClass $user) {
        parent::__construct($db, $cfg, $user);
        $this->model = new \mod_mapletadp\model\MapleData($db, $cfg);
        $this->connector = new \mod_mapletadp\model\Connector($db, $cfg);
    }

    public function getClasses($id = false) {
        $classes = $this->model->getClasses($id);
        return $classes;
    }

    public function setClasses($classes) {
        if ($classes) {
            $return = $this->model->setClasses($classes);
        }
    }

    public function getAssignments($classId, $id = false) {
        if ($classId) {
            return $this->model->getAssignments($classId, $id);
        }
        return false;
    }

    public function setAssignments($assignments, $class) {
        if ($assignments && $class > 0) {
            $return = $this->model->setAssignments($assignments);
        }
    }
    
    public function getClassesForForm(){
        $classes = $this->getClasses();

        $return  = array();
        if(count($classes) >0){
            foreach ($classes as $key => $value) {
                $return[$value->mapleid] = $value->name . ' ('. $value->instructor. ')'; 
            }
            
        }
        return $return;
    }
    public function getAssignmentsForForm($classId){
        $assignments = $this->getAssignments($classId);
        $return  = array();
        if(count($assignments) >0){
            foreach ($assignments as $key => $value) {
                $return[$value->mapleid] = $value->name; 
            }
            
        }
        return $return;
    }
    
    public function getAllAssignmentsForForm(){
        $assignments = $this->model->getAssignments();
        $return  = array();
        
        if(count($assignments) >0){
            foreach ($assignments as $key => $value) {
                $return[$value->mapleid] = $value->name; 
            }
            
        }
        return $return;
    }


}
