# JSON-RPC Log Extension

This is a logger extension for the [php-json-rpc](https://github.com/datto/php-json-rpc) library. It provides a simple logging mechanism to log incoming JSON-RPC requests and the corresponding responses to a logger.

Examples
--------
To use the logged server, simply create a logger with a corresponding handler and pass it to a `Logger\Server` instance. In this example, we'll use Monolog's `SyslogHandler` and the `Simple\Evaluator` (see [php-json-rpc-simple](https://github.com/datto/php-json-rpc-simple)). This will log all requests and the responses to the system's syslog, typically found at `/var/log/syslog`:

```php
<?php

use Datto\JsonRpc\Logged;
use Datto\JsonRpc\Simple;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;

$server = new Logged\Server(
    new Simple\Evaluator(),
    new Logger('API', array(new SyslogHandler('datto.api')))
);

$server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": [6, 2], "id": 1}');
```

Requirements
------------
* PHP >= 5.3

Installation
------------
```javascript
"require": {
  "datto/json-rpc-log": "~4.0"
}
```

License
-------
This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html).

Author
------
Written by [Philipp C. Heckel](https://github.com/binwiederhier).

