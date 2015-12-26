<?php

namespace mod_mapletadp\helper;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_mapletadp helper
 *
 * @package    mod_mapletadp
 * @copyright  2015 Milan Valusek <your@email.adress>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MapletaHelper {
    
    public function getTimestamp() {
        return time() * 1000;
    }

    public function getSignature($time) {
        global $CFG;
        $password = 'hvhebwtu'; //TODO vlozit do nastaveni moodlu
        $signature = base64_encode(md5($time . $password, true));
        return $signature;
    }

    public function getArray() {
        $time = $this->getTimestamp();
        $signature = $this->getSignature($time);
        $array = array('signature' => $signature, 'timestamp' => $time);
        return $array;
    }

    function stringEncode($string) {
        return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
    }

    static function getConnectionBase() {
        global $CFG;
        $base = $CFG->mapletadp_protocol . '://' . $CFG->mapletadp_server . ($CFG->mapletadp_port > 0 ? ':' . $CFG->mapletadp_server : '') . '/' . $CFG->mapletadp_context . '/';
        return $base;
    }

    function getRole($courseId, $user) {
        if (has_capability('mod/mapletadp:admin', \context_course::instance($courseId), $user->id)) {
            return \mod_mapletadp\model\Connector::MAPLE_ROLE_ADMIN;
        }
        if (has_capability('mod/mapletadp:instructor', \context_course::instance($courseId), $user->id)) {
            return \mod_mapletadp\model\Connector::MAPLE_ROLE_INSTRUCTOR;
        }
        if (has_capability('mod/mapletadp:proctor', \context_course::instance($courseId), $user->id)) {
            return \mod_mapletadp\model\Connector::MAPLE_ROLE_PROCTOR;
        }
        if (has_capability('mod/mapletadp:student', \context_course::instance($courseId), $user->id)) {
            return \mod_mapletadp\model\Connector::MAPLE_ROLE_STUDENT;
        }
        return false;
    }

}
