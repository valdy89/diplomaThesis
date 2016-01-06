<?php
require_once($CFG->dirroot.'/mod/mapletadp/lib.php');
require_once($CFG->dirroot.'/lib/adminlib.php');

$settings->add(new admin_setting_heading('heading', get_string('settingsheading', 'mapletadp'), get_string('settingsinfo', 'mapletadp')));

$options = array('http'=>'http','https'=>'https');

$settings->add(new admin_setting_configselect('mapletadp_protocol', get_string('protocol', 'mapletadp'), get_string('protocoldescription', 'mapletadp'),'http', $options));
$settings->add(new admin_setting_configtext('mapletadp_server', get_string('server', 'mapletadp'), get_string('serverdescription', 'mapletadp'),''));
$settings->add(new admin_setting_configtext('mapletadp_context', get_string('context', 'mapletadp'), get_string('contextdescription', 'mapletadp'),''));
$settings->add(new admin_setting_configpasswordunmask('mapletadp_secret', get_string('secret', 'mapletadp'), get_string('secretdescription', 'mapletadp'),''));
$settings->add(new admin_setting_configtext('mapletadp_timeout', get_string('timeout', 'mapletadp'), get_string('timeoutdescription', 'mapletadp'),'30'));
$settings->add(new admin_setting_configselect('mapletadp_showonlyexternal', get_string('showonlyexternal', 'mapletadp'), get_string('output_show_info_desc', 'local_inspis'), '1', array(
        '0' => get_string('no', 'mapletadp'),
        '1' => get_string('yes', 'mapletadp'))));
  
?>
