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
    private $mapleta_session = 'mapleta_session';

    public function __construct(\moodle_database $db, \stdClass $cfg) {
        parent::__construct($db, $cfg);
        $this->helper = new \mod_mapleta\helper\MapletaHelper();
        $this->mapleApi = new \mod_mapleta\MapleAPI();
    }

    //public function

    public function getClasses($classID = 0, $featured = false, $openForRegistration = false) {
        GLOBAL $USER;
        $params = $this->getClassesParams($classID, $featured, $openForRegistration);
        $session = $this->getSessionID($USER, Base::MAPLE_ROLE_ADMIN);
        $response = $this->sendRequest('ws/class', $params, $session);
        $dataset = $this->getClassesResponse($response);

        if ($dataset !== false) {

            return $dataset;
        }

        return false;
    }

    public function getAssignments($classID, $assigmentID, $user) {
        $params = $this->getAssignmentsParams($classID, $assigmentID, $user);
        if ($params !== false) {
            $session = $this->getSessionID($user, Base::MAPLE_ROLE_INSTRUCTOR, $classID);

            $response = $this->sendRequest('ws/assignment', $params, $session);
            $dataset = $this->getAssignmentsResponse($response);
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
            return true;
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

            if ($session !== false) {

                return $session;
            }
        }
        return false;
    }

    protected function getSessionID($user, $role, $classID = -1) {
        $session = $this->db->get_record_sql("SELECT * FROM {" . $this->mapleta_session . "} WHERE userid = ? AND role = ? AND class = ?", array($user->id, $role, $classID));
        $expirationLimit = 30 * 60;


        if ($session && $session->expirationtime > time() - $expirationLimit && $session->class == $classID && $session->role == $role) {
            $session->expirationtime = time();
            $this->db->update_record($this->mapleta_session, $session);
        } else {
            $session = $this->db->execute("DELETE FROM {" . $this->mapleta_session . "} WHERE userid = ? AND role = ? AND class = ?", array($user->id, $role, $classID));
            $session = new \stdClass();
            $session->expirationtime = time();
            $session->userid = $user->id;
            $session->class = $classID;
            $session->role = $role;
            $session->session = $this->connectMaple($user, $role, $classID);

            if ($session->session) {

                $this->db->insert_record($this->mapleta_session, $session);
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

        if ($this->checkResponse($response)) {
            return $response['session'];
        }
        return false;
    }

    public function getAssignmentsResponse($response) {
        var_dump($response);
    }

    public function getClassesResponse($response) {
        var_dump($response);
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
