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
    echo "<table  cellspacing='0' class='flexible generaltable generalbox'>";
    foreach($monitors as $key => $value){
        echo "<tr><td>".  get_string($key,'local_mapleta')."</td><td><b>".$value."</b></td></tr>";
    }
    echo "</table>";
}
echo $OUTPUT->footer();
exit();
