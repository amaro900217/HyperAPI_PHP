<?php

// public/api/index.php

$conf = require __DIR__ . '/../../src/Conf.php';
require __DIR__ . '/../../vendor/autoload.php';

use HyperAPI\ServerCli;
use HyperAPI\ServerWeb;

if (php_sapi_name() === 'cli') {
    (new ServerCli($conf))->run();
} else {
    (new ServerWeb($conf))->run();
}
