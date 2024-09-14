<?php

namespace DatabaseManager\PixalsLibs\Queries;

use Closure;
use cooldogedev\libSQL\exception\SQLException;

final class QueriesManager {


    public function executeQuery(String $query, array $option, array $vars) : void {
        $running_query = new RunningQueries($query, $option, $vars);
        $running_query->execute();  
    }
    
    public function fetchQuery(String $query, array $options, array $vars, Closure $onSuccess, Closure $onFail) : void {
        $running_query = new RunningQueries($query, $options, $vars);
        $running_query->execute(
            onSuccess: fn(mixed $result) => $onSuccess(
                $result
            ),

            onFail: fn(SQLException $e) => $onFail(
                $e
            )
        );
    }

}