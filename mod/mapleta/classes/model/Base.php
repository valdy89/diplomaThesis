<?php

namespace mod_mapleta\model;

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

    const MAPLE_ROLE_ADMIN = "ADMINISTRATOR";
    const MAPLE_ROLE_STUDENT = "STUDENT";
    const MAPLE_ROLE_PROCTOR = "PROCTOR";
    const MAPLE_ROLE_INSTRUCTOR = "INSTRUCTOR";

    public function __construct(\moodle_database $db, \stdClass $cfg) {
        $this->db = $db;
        $this->cfg = $cfg;
    }

}
