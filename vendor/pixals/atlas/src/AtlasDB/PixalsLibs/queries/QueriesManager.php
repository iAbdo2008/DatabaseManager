<?php


namespace AtlasDB\PixalsLibs\queries;

use AtlasDB\PixalsLibs\managers\WorkersManager;
use AtlasDB\PixalsLibs\result\DeferredResult;
use AtlasDB\PixalsLibs\threads\AtlasQuery;
use Closure;
use Generator;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class QueriesManager {

    private static $queries = [];

    public function executeQuery(AtlasQuery $atlasQuery, ?Closure $onSuccess = null, ?Closure $onError = null) : void {
        $queue = WorkersManager::getQueue();
        $queue[] = $atlasQuery; 
        self::$queries[spl_object_hash($atlasQuery)] = [$atlasQuery, $onSuccess, $onError];
    }

    public function completionHandlerEnable(PluginBase $plugin) : void {
        $plugin->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() {
            foreach(self::$queries as $id => [$atlasQuery, $onSuccess, $onError]){
                if($atlasQuery->getResult() !== null && $onSuccess !== null) {
                    ($onSuccess)($atlasQuery->getResult());
                }
                if($atlasQuery->getError() !== null && $onError !== null) {
                    ($onError)($atlasQuery->getError());
                }

                unset(self::$queries[$id]);
            }
        }), 1);
    }


}