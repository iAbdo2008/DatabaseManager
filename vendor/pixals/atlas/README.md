# Atlas

Atlas is a high-performance multithreaded database query framework for PocketMine-MP, Built for Performance-First Servers Like Pixals.  
Built to solve the latency and blocking issues caused by synchronous queries on the main thread, Atlas offloads query execution to dedicated worker threads while ensuring clean callback handling on the main thread.

---

## ⚡ Key Features

- **Multithreaded Query System** using PocketMine’s threading infrastructure
- **Non-Blocking Result Handling** with clean success callbacks
- **Supports Generators** and promise-style `Await::promise` integration
- **Scalable for Active Servers** — designed to handle high concurrency with minimal lag

---

## 🛠️ Usage Example


- To Get Data From The Query:
```php
Await::f2c(function(){
  $query = new TestQuery();
  $result = yield from Await::promise(fn($accept) => (new QueriesManager)->executeQuery($query, $accept));
  var_dump($result);
});
```


- Query Example:
```php
<?php


namespace Test;

use AtlasDB\PixalsLibs\result\DeferredResult;
use AtlasDB\PixalsLibs\threads\AtlasQuery;
use mysqli;

class TestQuery extends AtlasQuery {

    public function __construct()
    {

    }

    public function doQuery(mysqli $connection): void
    {
        $query = $connection->query("SELECT * FROM data;");
        $this->setResult($query->fetch_assoc());        
    }
}
```
---

## Created By:
- Innovation Wing of Pixals Network , Under Direct Supervision of oPinqzz, Innovation Wing Leader.

## License
```License: MIT with Attribution Requirement

You are free to use, modify, and distribute this project — commercially or non-commercially — as long as credit is given to the original author. Contributions are welcome.
```

## Pixals Network 2025 - Innovation Wing.

