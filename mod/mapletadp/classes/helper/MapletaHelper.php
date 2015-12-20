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

    public function getTimestamp(){
        return time()*1000;
    }
    
    public function getSignature($time) {
        global $CFG;
        $password = 'hvhebwtu'; //TODO vlozit do nastaveni moodlu
        $signature = base64_encode(md5($time . $password, true));
        return $signature;
    }
    
    public function getArray(){
        $time = $this->getTimestamp();
        $signature = $this->getSignature($time);
        $array = array('signature'=>$signature,'timestamp'=>$time);
        return $array;
    }
    
   

}
