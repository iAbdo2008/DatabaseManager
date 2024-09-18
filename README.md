# DatabaseManager
Easy Way to Execute and Fetch Queries Using LibSQL By cooldogdev


# API Implementation
- Installation Using Composer 
```composer require opinqzz/databasemanager dev-main```


```php
$database_manager = new DatabaseManager;
$database_manager->connectDB($ip, $username, $password, $database, $port, $this);
```
For Intialization in ```php onEnable() : void {}```

- For Executing a Query:
  ```php
     $database_manager->getQueriesManager()->executeQuery(
            "INSERT INTO MeowTest VALUES ('gcape_name', ':cape_test');",
            [
                "type" => "execute"
            ],
            [
                "gcape_name" => "meow2",
                ":cape_test" => "mewo32"
            ]
        );
  ```

- For Fetching a Query ( Using Await Generators By SoFE ):
  ```php
     Await::f2c(function() use ($database_manager) {
            $result = yield from Await::promise(fn($accept, $refuse) => $database_manager->getQueriesManager()->fetchQuery("SELECT * FROM MeowTesting WHERE player_name = 'pname'", ["type" => "fetch"], ["pname" => "oPinqzz"], $accept, $refuse));
            echo $result["player_xuid"];
        });  
  ```

Leave A Star !

All Copyrights For oPinqzz / You cannot Re sell this Library
