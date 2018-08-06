<?php

// auth class
class AuthClass {
    private $_login = "admin"; //Set login
    private $_password = "password"; //Set password

    //Check for active auth session
    public function isAuth() {
        if (isset($_SESSION["is_auth"])) {
            return $_SESSION["is_auth"];
        }
        else return false;
    }

    // User Auth
    public function auth($login, $password) {
        if ($login == $this->_login && $password == $this->_password) {
            $_SESSION["is_auth"] = true;
            $_SESSION["login"] = $login;
            return true;
        }
        else { // if login or pass incorrect
            $_SESSION["is_auth"] = false;
            return false;
        }
    }

    // Store login name
    public function getLogin() {
        if ($this->isAuth()) {
            return $_SESSION["login"];
        }
    }

    public function out() {
        $_SESSION = array();
        session_destroy(); //drop session
    }
}

?>
