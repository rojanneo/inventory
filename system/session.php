<?php

require_once('system/connection.php');

class Session {

    private static $instance = NULL;
    public static $connection;

    private function __construct() {
        $this->connection = Connection::GetInstance();
    }

    public function GetInstance() {

        if (!self::$instance) {

            self::$instance = new Session();
        }
        return self::$instance;
    }

    public static function createNewSession($member) {
        $sessionstore = "INSERT INTO  user_session (user_id,token_id) values ('" . $member[0]['mid'] . "','" . $member[0]['memail'] . "')";
        $sessdata = self::GetInstance()->connection->InsertQuery($sessionstore);
        //need to check if the data is inserted into the session table and then only apple the condition to initialze the sessionvariable value

        self::addSessionId($member[0]['memail']);
        return true;
    }

    public static function session_start($type) {
        session_start();
    }

    // CREATE SESSION
    public static function create_session($token_id) {
        $_SESSION['token_id'] = $token_id;
    }

    public static function getCurrentSession() {
        self::GetInstance();
        $token = self::getSessionId();
        $sessionv = "Select * from user_session where token_id='" . $token . "'";
        $ses_cur_val = self::GetInstance()->connection->Query($sessionv);

        //$ses_cur_val=$this->connection->Query($sessionv);
        if ($ses_cur_val)
            return $ses_cur_val[0];
        else
            return false;
    }

    public static function getSessionId() {
        if (isset($_SESSION['token_id']))
            return $_SESSION['token_id'];
        else
            return false;
    }

    public static function addSessionId($token) {
        $_SESSION['token_id'] = $token;
    }

    public static function session_close() {
        $currentsession = $_SESSION['token_id'];
        $sessiondelete = "DELETE FROM  user_session  WHERE token_id='" . $currentsession . "'";
        $sessdeldata = self::GetInstance()->connection->DeleteQuery($sessiondelete);
        unset($_SESSION['token_id']);
        return $sessdeldata;
    }

    public static function isLoggedIn() {
        $token = self::getSessionId();
        $session = self::getCurrentSession($token);
        if ($session) {
            return true;
        } else {
            return false;
        }
    }

    public static function addSessionVariable($key, $value) {
        if (isset($key) and isset($value)) {
            $_SESSION[$key] = $value;
        }
    }

    public function getSessionVariable($key) {
        if (isset($key) and isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    public static function addErrorMessage($msg) {
        if (!isset($_SESSION['error']))
            $_SESSION['error'] = array();
        array_push($_SESSION['error'], $msg);
    }

    public static function showErrorMessages() {
        if (isset($_SESSION['error'])) {
            include 'views/default/error-messages.phtml';
        }
    }

    public static function addSuccessMessage($msg) {
        if (!isset($_SESSION['success']))
            $_SESSION['success'] = array();
        array_push($_SESSION['success'], $msg);
    }

    public static function showSuccessMessages() {
        if (isset($_SESSION['success'])) {
            include 'views/default/success-messages.phtml';
        }
    }

}

?>