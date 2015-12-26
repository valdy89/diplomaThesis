<?php

namespace mod_mapletadp\task;

/**
 * Cron task - spousti synchronizaci ciselniku
 */
class synchronize extends \core\task\scheduled_task {

    /**
     * Vrati jmeno
     * 
     * @return string Jmeno
     */
    public function get_name() {
        // Shown in admin screens
        return get_string('synchronization', 'mod_mapletadp');
    }

    /**
     * Provedeni prikazu
     * 
     * @global object $CFG
     * @global object $DB
     * @return void
     */
    public function execute() {
        //pres vsechny kurzy
        global $CFG, $DB, $USER;
        $mapletadp = new \mod_mapletadp\controller\Mapleta($DB, $CFG, $USER);
        $mapletadp->refreshAllData();
    }

}
