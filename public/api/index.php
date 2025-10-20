<?php

// public/api/index.php

$conf = require __DIR__ . '/../../conf.php';

if (php_sapi_name() === 'cli') {
    $binEntry = __DIR__ . '/../../bin/cli.php';
} else {
    $binEntry = __DIR__ . '/../../bin/web.php';
}

if (!file_exists($binEntry)) {
    http_response_code(500);
    exit('Entry point not found.');
}

require $binEntry;
