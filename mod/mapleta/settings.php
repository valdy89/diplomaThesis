<?php
require_once($CFG->dirroot.'/mod/mapleta/lib.php');
require_once($CFG->dirroot.'/lib/adminlib.php');

$settings->add(new admin_setting_heading('heading', get_string('settingsheading', 'mapleta'), get_string('settingsinfo', 'mapleta')));

$options = array('http'=>'http','https'=>'https');

$settings->add(new admin_setting_configselect('mapleta_protocol', get_string('protocol', 'mapleta'), get_string('protocoldescription', 'mapleta'),'http', $options));
$settings->add(new admin_setting_configtext('mapleta_server', get_string('server', 'mapleta'), get_string('serverdescription', 'mapleta'),''));
$settings->add(new admin_setting_configtext('mapleta_port', get_string('port', 'mapleta'), get_string('portdescription', 'mapleta'),''));
$settings->add(new admin_setting_configtext('mapleta_context', get_string('context', 'mapleta'), get_string('contextdescription', 'mapleta'),''));
$settings->add(new admin_setting_configtext('mapleta_secret', get_string('secret', 'mapleta'), get_string('secretdescription', 'mapleta'),''));

?>
