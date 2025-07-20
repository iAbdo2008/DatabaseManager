<?php

namespace DatabaseManager\PixalsLibs\Queries;

use AtlasDB\PixalsLibs\queries\QueriesManager as AtlasQManager;
use AtlasDB\PixalsLibs\result\DeferredResult;
use Closure;
use cooldogedev\libSQL\exception\SQLException;

final class QueriesManager {


    public function executeQuery(String $query, array $option, array $vars) : void {
        $running_query = new RunningQueries($query, $option, $vars);
        (new AtlasQManager)->executeQuery($running_query);  
    }
    
    public function fetchQuery(String $query, array $options, array $vars, Closure $onSuccess, Closure $onFail = null) : void {
        $running_query = new RunningQueries($query, $options, $vars);
        (new AtlasQManager)->executeQuery($running_query, $onSuccess, $onFail);  
    }

}
