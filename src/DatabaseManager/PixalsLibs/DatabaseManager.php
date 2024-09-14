<?php


namespace DatabaseManager\PixalsLibs;


use cooldogedev\libSQL\ConnectionPool;
use DatabaseManager\PixalsLibs\Queries\QueriesManager;
use pocketmine\plugin\PluginBase;

final class DatabaseManager {

    private $pool;

    public function connectDB(String $ip, String $username, String $password, String $db_name, int $port, PluginBase $plugin) : void {
        $this->pool = new ConnectionPool(
            $plugin,
            [
                "provider" => "mysql",
                "threads" => 2,
                "mysql" => [
                    $ip,
                    $username,
                    $password,
                    $db_name,
                    $port
                ]
            ]
        );
    }


    public function getQueriesManager() : QueriesManager {
        return new QueriesManager;
    }



}
