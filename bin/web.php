<?php

// bin/web.php

$conf = require __DIR__ . '/../conf.php';
$app  = require_once $conf['app_entrypoint'];
$app->run();
