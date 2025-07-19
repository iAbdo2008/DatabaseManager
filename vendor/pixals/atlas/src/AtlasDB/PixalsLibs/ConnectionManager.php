<?php

namespace AtlasDB\PixalsLibs;

use AtlasDB\PixalsLibs\managers\WorkersManager;
use AtlasDB\PixalsLibs\queries\QueriesManager;
use AtlasDB\PixalsLibs\threads\AtlasQuery;
use AtlasDB\PixalsLibs\threads\AtlasWorker;
use Closure;
use pocketmine\plugin\PluginBase;
use pmmp\thread\Pool;
use pocketmine\scheduler\ClosureTask;

final class ConnectionManager {

    private static array $connections = [];
    protected static array $config = [];

    public function createConnection(PluginBase $plugin, array $config) {
        self::$config = $config;
        self::$connections[self::$config["ip"]] = new Connection($config["ip"], $config["port"], $config["username"], $config["password"], $config["database"]);
        WorkersManager::init(); 
        (new QueriesManager)->completionHandlerEnable($plugin);

    }

    public function getConnection() : Connection {
        return self::$connections[self::$config["ip"]];
    }




}