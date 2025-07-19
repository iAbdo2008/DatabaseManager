<?php


namespace DatabaseManager\PixalsLibs;

use AtlasDB\PixalsLibs\Connection;
use AtlasDB\PixalsLibs\ConnectionManager;
use DatabaseManager\PixalsLibs\Queries\QueriesManager;
use pocketmine\plugin\PluginBase;

final class DatabaseManager {


    public function connectDB(String $ip, String $username, String $password, String $db_name, int $port, PluginBase $plugin) : void {
        $connection_manager = new ConnectionManager;
        $connection_manager->createConnection($plugin, [
            "ip" => $ip,
            "username" => $username,
            "password" => $password,
            "database" => $db_name,
            "port" => $port
        ]);
    }


    public function getQueriesManager() : QueriesManager {
        return new QueriesManager;
    }



}
