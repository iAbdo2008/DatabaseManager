<?php

namespace AtlasDB\PixalsLibs\managers;

use AtlasDB\PixalsLibs\ConnectionManager;
use AtlasDB\PixalsLibs\threads\AtlasWorker;
use pmmp\thread\ThreadSafeArray;

final class WorkersManager {

    private static ?AtlasWorker $worker = null;
    private static ?ThreadSafeArray $queue = null;

    public static function init() : void {
        if(self::$queue == null) {
            self::$queue = new ThreadSafeArray();
            self::$worker = new AtlasWorker((new ConnectionManager)->getConnection(), self::$queue);
            self::$worker->start();
        }
    }

    public static function getQueue() : ThreadSafeArray {
        return self::$queue;
    }




}