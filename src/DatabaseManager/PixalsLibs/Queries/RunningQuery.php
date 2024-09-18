<?php



namespace DatabaseManager\PixalsLibs\Queries;

use cooldogedev\libSQL\query\MySQLQuery;
use InvalidArgumentException;
use mysqli;

class RunningQueries extends MySQLQuery {

    private String $query;
    private String $options;
    
    public function __construct(String $query, array $options, array $vars)
    {
        if($options["type"] == "execute") {
            if($vars == null) {
                new InvalidArgumentException("The Variable of The Execution Queries Should Not Be Null");
            } else {
                $this->query = serialize(str_replace(array_keys($vars), $vars, $query));
                $this->options = serialize($options);
            }

        } else if($options["type"] == "fetch") {
            if($vars == null) {
                new InvalidArgumentException("The Varaibles of Fetching Queries Should Not Be Null");
            } else {
                $this->query = serialize(str_replace(array_keys($vars), $vars, $query));
                $this->options = serialize($options);
            }
        }
    }

    public function onRun(mysqli $connection): void
    {
        $options = unserialize($this->options);
        if($options["type"] == "execute") {
            $query = unserialize($this->query);
            $statement = $connection->prepare($query);
            $statement->execute(); 
        } else if($options["type"] == "fetch") {
            $query = unserialize($this->query);
            $statement = $connection->prepare($query);
            $statement->execute();
            $result = mysqli_stmt_get_result($statement);
            $this->setResult($result->fetch_all(MYSQLI_ASSOC));
        }
    }


}