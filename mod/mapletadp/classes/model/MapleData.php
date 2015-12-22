<?php

namespace mod_mapletadp\model;

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

    private $mapletadp_classes = 'mapletadp_classes';
    private $mapletadp_assignments = 'mapletadp_assignments';

    //put your code here

    public function __construct(\moodle_database $db, \stdClass $cfg) {
        parent::__construct($db, $cfg);
    }

    public function getClasses($id = false) {
        $conditions = array();
        if ($id && is_int($id)) {
            $conditions['mapleId'] = $id;
            $return = $this->db->get_record($this->mapletadp_classes, $conditions);
        } else {
            $return = $this->db->get_records($this->mapletadp_classes, $conditions);
        }
        return $return;
    }

    public function changeKey($array, $newKey = 'id') {

        if (count($array) > 0) {
            $new = array();
            foreach ($array as $value) {
                $new[$value[$newKey]] = $value;
            }
            $array = $new;
        }

        return $array;
    }

    //parametr public
    public function setClasses($classes) {

        if (count($classes) > 0 && count($classes['element']) > 0) {
            $savedClasses = $this->getClasses();

            $array = $this->changeKey(json_decode(json_encode($savedClasses), true), 'mapleid');
            $insert = array();
            $update = array();
            foreach ($classes['element'] as $class) {
                $object = new \stdClass();
                $object->mapleId = $class['id'];
                $object->name = $class['name'];
                $object->instructor = $class['instructor'];


                if (!array_key_exists($class['id'], $array)) {

                    $insert[] = $object;
                } else {
                    if ($array[$class['id']]['name'] !== $class['name'] || $array[$class['id']]['instructor'] !== $class['instructor']) {
                        $update[] = $object;
                        $object->id = $array[$class['id']]['id'];
                        $this->db->update_record($this->mapletadp_classes, $object);
                    }
                }
            }
            if ($this->debug) {
                var_dump($insert);
                echo '<br>';
                var_dump($update);
            }

            $this->db->insert_records($this->mapletadp_classes, $insert);
        }
    }

    public function getAssignments($classId = false, $id = false) {
        $conditions = array();
        if ($classId) {
            $conditions['classId'] = $classId;
        }
        if ($id) {
            $conditions['mapleId'] = $id;

            $return = $this->db->get_record($this->mapletadp_assignments, $conditions);
        } else {
            $return = $this->db->get_records($this->mapletadp_assignments, $conditions);
        }


        return $return;
    }

    //pridat parametr public
    public function setAssignments($assignments, $classId) {

        if (count($assignments) > 0 && count($assignments['element']) > 2) {
            $savedAssignments = $this->getAssignmets($classId);
            $array = $this->changeKey(json_decode(json_encode($savedAssignments), true), 'mapleid');


            $insert = array();
            //debug
            $update = array();


            foreach ($assignments['element'] as $assignment) {

                $object = json_decode(json_encode($assignment), FALSE);
                $object->mapleId = $object->id;
                if (isset($object->start)) {
                    $object->start = (int) ($object->start / 1000);
                }
                if (isset($object->end)) {
                    $object->end = (int) ($object->end / 1000);
                }
                $object->policy = json_encode($object->policy);
                if (!array_key_exists($assignment['id'], $array)) {
                    $insert[] = $object;
                } else {
                    $update[] = $object;
                    $this->db->update_record($this->mapletadp_assignments, $object);
                }
            }
            if ($this->debug) {
                var_dump($insert);
                var_dump($update);
            }

            $this->db->insert_records($this->mapletadp_assignments, $insert);
        }
    }

}
