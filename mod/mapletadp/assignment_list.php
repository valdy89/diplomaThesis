<?php
/**
 * obsluhuje ajax pozadavky filtru s cilovymi skupinami
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_login();
global $DB, $USER, $CFG;
$controller=new \mod_mapletadp\controller\MapleData($DB, $CFG, $USER);
if(!empty($_GET)){
	// vyfiltrovani uzivatele
	if(!empty($_GET['classID'])){
		$filtered_data=$controller->getAssignmentsForForm($_GET['classID']);
		echo json_encode($filtered_data);
	}
	else{
		echo json_encode(array('none'));
	}
}