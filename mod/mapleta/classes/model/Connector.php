<?php

namespace mod_mapleta\model;

/**
 * Description of Connector
 *
 * @author valusek
 */
class Connector extends Base {

    private $helper;
    private $mapleApi;

    public function __construct(\moodle_database $db, \stdClass $cfg) {
        parent::__construct($db, $cfg);
        $this->helper = new \mod_mapleta\helper\MapletaHelper();
        $this->mapleApi = new \mod_mapleta\MapleAPI();
        }
    
        

    public function sendRequest($url, $request) {
        $request['timestamp'] = \mod_mapleta\helper\MapletaHelper::getTimestamp();
        $request['signature'] = \mod_mapleta\helper\MapletaHelper::getSignature($request["timestamp"]);
        $response = $this->mapleApi->call($url, $request);
        return $response;
    }

}
