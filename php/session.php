<?php
class Session {
    private $username;
    private $password;

    function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    function get_user() {
        return $this->username;
    }
    function get_pwd() {
        return $this->pwd;
    }
}

?>