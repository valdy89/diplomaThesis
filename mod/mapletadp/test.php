<?php
global $DB,$CFG,$USER;
require_once('../../config.php');
$controller = new \mod_mapletadp\controller\Connector($DB,$CFG,$USER);
$model = new \mod_mapletadp\model\Connector($DB,$CFG);
$modelData = new \mod_mapletadp\controller\MapleData($DB,$CFG,$USER);
$mapletadp = new \mod_mapletadp\controller\Mapleta($DB,$CFG,$USER);


//$echo = $controller->pingServer("BBB");
/*
$resp2 = $controller->connectMaple($USER, \mod_mapletadp\controller\Base::MAPLE_ROLE_ADMIN);
var_dump($resp2);
$resp3 = $controller->getClasses($USER, \mod_mapletadp\controller\Base::MAPLE_ROLE_ADMIN,$resp3['status']['session']);
var_dump($resp3);
 */
//
//$model->getClasses(0);

//$classes = $controller->getClasses();
//
//
//$modelData->setClasses($classes);


//$mapletadp->refreshAllData();
$g = $controller->getGrades(1);
echo '<pre>';var_dump($g); echo '</pre>';