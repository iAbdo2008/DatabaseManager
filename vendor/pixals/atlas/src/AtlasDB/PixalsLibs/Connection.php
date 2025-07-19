<?php

namespace AtlasDB\PixalsLibs;

use mysqli;

class Connection {

    private $ip = "";
    private $port = 3306;
    private $user_name = "";
    private $password = "";
    private $database = "";

    private ?mysqli $connection = null;
    
    public function __construct(String $ip, int $port, String $user_name, String $password, String $database)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->user_name = $user_name;
        $this->password = $password;
        $this->database = $database;
        // $this->connection = new mysqli($ip, $user_name, $password, $database, $port);
    }

    public function getIP() : String {
        return $this->ip;
    }

    public function getPort() : String {
        return $this->port;
    }

    
    public function getUsername() : String {
        return $this->user_name;
    }

    
    public function getPassword() : String {
        return $this->password;
    }

    public function getDatabase() : String {
        return $this->database;
    }

    public function getConnection() : mysqli {
        return $this->connection;
    }

}