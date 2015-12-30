<?php

global $PAGE, $OUTPUT, $DB, $CFG, $USER;

require_once('../../config.php');
require_login();
$synchro = optional_param('synchro', false, PARAM_BOOL);
$context = context_system::instance();
$PAGE->set_context($context);
$base_url = new moodle_url('/local/mapleta/index.php');
$PAGE->set_url($base_url);
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('heading', 'local_mapleta'));
$url = new moodle_url('local/mapleta/monitor.php');
echo "<a href='" . $CFG->wwwroot . "/local/mapleta/monitor.php'>" . get_string('monitor', 'local_mapleta') . "</a><br/>";
echo "<a href='" . $CFG->wwwroot . "/local/mapleta/index.php?synchro=true'>" . get_string('synchro', 'local_mapleta') . "</a><br/>";
echo "<a href='" . $CFG->wwwroot . "/admin/cron.php'>" . get_string('cronlink', 'local_mapleta') . "</a><br/>";

if ($synchro) {
    echo '<div id="synchro">';
    $mapletadp = new \mod_mapletadp\controller\Mapleta($DB, $CFG, $USER);
    if($mapletadp->refreshAllData()){
        echo "<h4>".  get_string('synchrodone','local_mapleta')."</h4>";
    }else{
        echo "<h4>".  get_string('synchrofail','local_mapleta')."</h4>";
    }
    echo '</div>';
}

echo $OUTPUT->footer();
exit();
