<?php
/*
 * Created on 12-Aug-09
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
	require_once("../../config.php");
	require_once("lib.php");
	global $COURSE, $CFG, $USER;

	require_login($COURSE->id);
		
	$params = array();
	
	foreach ( $_GET as $name => $value ) {
     	$params[$name]=mapleta_xmlencode($value);  
	}
	
	$firstname = mapleta_xmlencode($USER->firstname);
	$lastname = mapleta_xmlencode($USER->lastname);
	$username = mapleta_xmlencode($USER->username);
	$email = mapleta_xmlencode($USER->email);
	$idnumber = mapleta_xmlencode($USER->idnumber);
	$role = mapleta_xmlencode(mapleta_get_role($params["wsCourseId"]));
	
	$params['wsFirstName']=$firstname;
	$params['wsMiddleName']='';
	$params['wsLastName']=$lastname;
	$params['wsUserLogin']=$username;
	$params['wsUserEmail']=$email;
	$params['wsStudentId']=$idnumber;
	$params['wsUserRole']=$role;
	
	mapleta_ws_launch($params);
?>

