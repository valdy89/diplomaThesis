<?php

namespace mod_mapletadp\task;

class synchronize extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('synchronization', 'mod_mapletadp');
    }

    public function execute() {

        global $CFG, $DB, $USER;
        $mapletadp = new \mod_mapletadp\controller\Mapleta($DB, $CFG, $USER);
        $mapletadp->refreshAllData();
    }

}
