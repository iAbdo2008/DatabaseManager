<?php


namespace AtlasDB\PixalsLibs\threads;

use AtlasDB\PixalsLibs\ConnectionManager;
use AtlasDB\PixalsLibs\queries\QueriesManager;
use AtlasDB\PixalsLibs\result\DeferredResult;

use Closure;
use mysqli;
use pocketmine\scheduler\AsyncTask;
use pmmp\thread\ThreadSafe;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;
use Throwable;

class AtlasQuery extends ThreadSafe {

    private $result = null;
    private $error;
    private NonThreadSafeValue $deferred;

    public function __construct(DeferredResult $dr)
    {
        $this->deferred = new NonThreadSafeValue($dr);
    }


    public function doQuery(mysqli $connection) : void {
        
    }


    public function setResult(mixed $var) : void {
        $this->result = serialize($var);
    }
    
    public function getResult() : mixed {
        return $this->result !== null ? unserialize($this->result) : null;
    }


    public function setError(?NonThreadSafeValue $e) : void {
        $this->error = $e;
    }

    public function getError() : ?String {
        return $this->error->deserialize();
    }

    public function getDeferred() : DeferredResult {
        return $this->deferred->deserialize();
    }

}