<?php



namespace DatabaseManager\PixalsLibs\Queries;

use AtlasDB\PixalsLibs\result\DeferredResult;
use AtlasDB\PixalsLibs\threads\AtlasQuery;
use InvalidArgumentException;
use mysqli;
use mysqli_stmt;

class RunningQueries extends AtlasQuery {

    private String $query;
    private String $options;
    private string $vars;
    
    public function __construct(String $query, array $options, array $vars)
    {
        if($options["type"] == "execute") {
            $this->query = serialize($query);
            $this->options = serialize($options);
            $this->vars = serialize($vars);

        } else if($options["type"] == "fetch") {
            if($vars == null) {
                throw new InvalidArgumentException("The Varaibles of Fetching Queries Should Not Be Null");
            } else {
                $this->query = serialize($query);
                $this->options = serialize($options);
                $this->vars = serialize($vars);
            }
        }

    }

    public function doQuery(mysqli $connection): void
    {
        $options = unserialize($this->options);
        if($options["type"] == "execute") {
            $query = unserialize($this->query);
            $statement = $connection->prepare($query);
            $types = "";
            $vars = unserialize($this->vars);
            $values = [];
            foreach($vars as $key => $var) {
                $types .= $this->getBindType($var);
                $values[] = &$vars[$key];
            }
            $statement->bind_param($types, ...$values);
            $statement->execute();
        } else if($options["type"] == "fetch") {
            $query = unserialize($this->query);
            $statement = $connection->prepare($query);
            $types = "";
            $vars = unserialize($this->vars);
            $values = [];
            foreach($vars as $key => $var) {
                $types .= $this->getBindType($var);
                $values[] = &$vars[$key];
            }
            $statement->bind_param($types, ...$values);
            $statement->execute();
            $result = mysqli_stmt_get_result($statement);
            $this->setResult($result->fetch_all(MYSQLI_ASSOC));
        }
    }

    private function getBindType(mixed $value): string {
        return match (gettype($value)) {
            "boolean", "integer" => "i",
            "double"             => "d", // float
            "string"             => "s",
            default              => "b", // blob or unknown
        };
    }


}