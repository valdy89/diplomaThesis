<?php
global $DB,$CFG,$USER;
require_once('../../config.php');
$controller = new \mod_mapleta\controller\Connector($DB,$CFG,$USER);

$controller->pingServer(array('value'=>'bbbb'));
