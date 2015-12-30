<?php

global $PAGE, $OUTPUT, $DB, $CFG, $USER;

require_once('../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$base_url = new moodle_url('/local/mapleta/index.php');
$PAGE->set_url($base_url);
$PAGE->set_pagelayout('standard');
$controller = new \mod_mapletadp\controller\Connector($DB, $CFG, $USER);

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('monitor','local_mapleta'));

echo $OUTPUT->footer();
exit();
