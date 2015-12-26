<?php

namespace mod_mapletadp\model;

/**
 * Description of Connector
 *
 * @author valusek
 */
class Connector extends Base {

    
    private $mapleApi;
    private $mapletadp_session = 'mapletadp_session';

    const MAPLE_ROLE_ADMIN = "ADMINISTRATOR";
    const MAPLE_ROLE_STUDENT = "STUDENT";
    const MAPLE_ROLE_PROCTOR = "PROCTOR";
    const MAPLE_ROLE_INSTRUCTOR = "INSTRUCTOR";

    public function __construct(\moodle_database $db, \stdClass $cfg) {
        parent::__construct($db, $cfg);
        
        $this->mapleApi = new \mod_mapletadp\MapleAPI();
    }

    //public function

    public function getClasses($classID = 0, $featured = false, $openForRegistration = false) {
        GLOBAL $USER;
        $params = $this->getClassesParams($classID, $featured, $openForRegistration);
        $session = $this->getSessionID($USER, Connector::MAPLE_ROLE_ADMIN);

        $response = $this->sendRequest('ws/class', $params, $session);
        $dataset = $this->getResponse($response);

        if ($dataset !== false) {

            return $dataset;
        }

        return false;
    }

    public function getAssignments($classID, $assigmentID = 0, $user) {
        $params = $this->getAssignmentsParams($classID, $assigmentID);
        if ($params !== false) {
            $session = $this->getSessionID($user, Connector::MAPLE_ROLE_ADMIN, $classID);

            $response = $this->sendRequest('ws/assignment', $params, $session);
            $dataset = $this->getResponse($response);
            if ($dataset !== false) {

                return $dataset;
            }
        }
        return false;
    }

    //protected functions


    protected function sendRequest($url, $request, $cookie = false) {
        $response = $this->mapleApi->call($url, $request, $cookie);

        return $response;
    }

    protected function checkResponse($response) {

        if ($response['code'] == 0) {
            return $response;
        } elseif ($response['code'] == 100) {
            return null;
        }
        return false;
    }

    protected function connectMaple($user, $role, $classID) {
        $params = $this->getConnectParams($user, $role, $classID);

        if ($params !== false) {
            $response = $this->sendRequest('ws/connect', $params);

            $session = $this->getConnectResponse($response['status']);

            return $session;
        }
        return false;
    }

    protected function getSessionID($user, $role, $classID = -1) {
        $session = $this->db->get_record_sql("SELECT * FROM {" . $this->mapletadp_session . "} WHERE userid = ? AND role = ? AND class = ? ORDER BY id DESC LIMIT 1", array($user->id, $role, $classID));
        $expirationLimit = 30 * 60;
        if ($this->debug) {
            //    var_dump($session);
            //   var_dump($session && $session->timestamp > time() - $expirationLimit && $session->class == $classID && $session->role == $role && strlen($session->session)>0);
        }
        if ($session && $session->timestamp > time() - $expirationLimit && $session->class == $classID && $session->role == $role && strlen($session->session) > 0) {
            $session->timestamp = time();
            $this->db->update_record($this->mapletadp_session, $session);
        } else {
            $ret = $this->db->execute("DELETE FROM {" . $this->mapletadp_session . "} WHERE userid = ? AND role = ? AND class = ?", array($user->id, $role, $classID));

            $session = new \stdClass();
            $session->timestamp = time();
            $session->userid = $user->id;
            $session->class = $classID;
            $session->role = $role;
            $session->session = $this->connectMaple($user, $role, $classID);

            if ($session->session) {

                $this->db->insert_record($this->mapletadp_session, $session);
            }
        }

        return $session->session;
    }

    //params functions
    public function getConnectParams($user, $role, $classID) {
        $array = $this->helper->getArray();
        $array['classId'] = $classID;
        $array['userRole'] = $role;

        if (isset($user->id) && $user->id > 0) {
            $array['firstName'] = $user->firstname;
            $array['lastName'] = $user->lastname;
            $array['userLogin'] = $user->username;
            $array['userEmail'] = $user->email;
            return $array;
        }
        return false;
    }

    public function getAssignmentsParams($classID, $assigmentID = 0) {
        $array = $this->helper->getArray();
        $array['classId'] = $classID;
        $array['assignmentId'] = $assigmentID;

        return $array;
    }

    public function getClassesParams($classID, $featured, $openForRegistration) {
        $array = $this->helper->getArray();
        $array['classId'] = $classID;
        $array['featured'] = $featured;
        $array['featured'] = $openForRegistration;

        return $array;
    }

    //response functions
    public function getConnectResponse($response) {


        $return = $this->checkResponse($response);

        if ($return !== false) {
            return $return['session'];
        }
        return false;
    }

    public function getResponse($response) {

        $return = $this->checkResponse($response['status']);

        if ($return === false) {
            return false;
        } elseif ($return === null) {
            return null;
        }
        return $response['list'];
    }

    public function pingServer($echo) {
        $params = $this->pingServerParams('Running');
        if ($params !== false) {
            $response = $this->sendRequest('ws/ping', $params);
            $dataset = $this->validatePingResponse($response);
            if ($dataset !== false) {
                return 'running';
            }
        }
        return 'down';
    }

    public function pingServerParams($echo) {
        $array = $this->helper->getArray();
        $array['value'] = $echo;

        return $array;
    }

    public function validatePingResponse($response) {
        if (strlen($response['element']['value'])) {
            return true;
        }
        return false;
    }

}
