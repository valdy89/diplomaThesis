<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author valusek
 */
interface Maple {

    public function pingServer($params);

    public function connectMaple($params);

    public function disconnectMaple($params);

    public function getClasses($params);

    public function createClass($params);

    public function getAssignments($params);

    public function getGrades($params);

    public function monitor($all = null, $tomcat = null, $db = null, $maple = null);
}
