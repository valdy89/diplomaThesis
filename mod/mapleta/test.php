<?php
global $DB,$CFG,$USER;
require_once('../../config.php');
$controller = new \mod_mapleta\controller\Connector($DB,$CFG,$USER);
$model = new \mod_mapleta\model\Connector($DB,$CFG);
/*
$echo = $controller->pingServer("BBB");

$resp2 = $controller->connectMaple($USER, \mod_mapleta\controller\Base::MAPLE_ROLE_ADMIN);
var_dump($resp2);
$resp3 = $controller->getClasses($USER, \mod_mapleta\controller\Base::MAPLE_ROLE_ADMIN,$resp3['status']['session']);
var_dump($resp3);
 */
//
//$model->getClasses(0);

$model->getAssignments(6,200, $USER);