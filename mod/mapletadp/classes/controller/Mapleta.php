<?php

namespace mod_mapletadp\controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mapleta
 *
 * @author valusek
 */
class Mapleta extends Base {

    protected $model;

    public function __construct(\moodle_database $db, \stdClass $cfg, \stdClass $user) {
        parent::__construct($db, $cfg, $user);

        $this->model = new \mod_mapletadp\model\Mapleta($db, $cfg);
    }

    public function refreshAllData() {
        $this->model->refreshAllData();
    }

}
