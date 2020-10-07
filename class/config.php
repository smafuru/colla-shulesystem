<?php
class dbConfig {
    protected $serverName;
    protected $userName;
    protected $password;
    protected $dbName;
    function dbConfig() {
        $this -> serverName = 'localhost';
        $this -> userName = 'root';
        $this -> password = 'miguunyoka';
        $this -> dbName = 'webdamn_demos';
    }
}
?>