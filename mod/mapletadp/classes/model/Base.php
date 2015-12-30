<?php

namespace mod_mapletadp\model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author valusek
 */
class Base {

    protected $db;
    protected $cfg;
    protected $connectionBase;
    protected $helper;
  
    public $debug = false;

    public function __construct(\moodle_database $db, \stdClass $cfg) {
        $this->db = $db;
        $this->cfg = $cfg;
        $this->connectionBase = \mod_mapletadp\helper\MapletaHelper::getConnectionBase();
        $this->helper = new \mod_mapletadp\helper\MapletaHelper();
    }

}
