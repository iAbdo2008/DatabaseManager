<?php



namespace DatabaseManager\PixalsLibs\Queries;

use AtlasDB\PixalsLibs\result\DeferredResult;
use AtlasDB\PixalsLibs\threads\AtlasQuery;
use InvalidArgumentException;
use mysqli;
use mysqli_stmt;

class Query extends AtlasQuery {

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
        $vars = unserialize($this->vars);
        $query = unserialize($this->query);

        $statement = $connection->prepare($query);
        $statement->bind_param($this->getTypes($vars), ...$this->getValues($vars));
        $statement->execute();

        if($options["type"] == "fetch") {
            $result = mysqli_stmt_get_result($statement);
            $this->setResult($result->fetch_all(MYSQLI_ASSOC));
        }
    }

    private function getValues(array $vars) : array {
        $values = [];
        foreach($vars as $key => $var) {
            $values[] = $vars[$key];
        }

        return $values;
    }

    private function getTypes(array $vars) : string {
        $types = "";
        foreach($vars as $key => $var) {
            $type = match(gettype($var)) {
                "string" => "s",
                "integer", "boolean" => "i",
                "double" => "d",
                default => "b"
            };
            $types .= $type;
        }

        return $types;
    }


}
