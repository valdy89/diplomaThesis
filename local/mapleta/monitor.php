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

echo $OUTPUT->heading(get_string('monitorheader', 'local_mapleta'));
$mapletadp = new \mod_mapletadp\controller\Mapleta($DB, $CFG, $USER);

$monitors = $mapletadp->getMonitors();
if(count($monitors) && $monitors){
    foreach($monitors as $key => $value){
        echo "<div>".  get_string($key,'local_mapleta').": <b>".$value."</b></div>";
    }
}
echo $OUTPUT->footer();
exit();
