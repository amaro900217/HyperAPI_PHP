<?php
return [
    'app_entrypoint' => __DIR__ . '/app.php',
    'public_path'    => __DIR__ . '/public',
    'log_file'       => '/dev/null', // ENABLE IT: __DIR__ . '/bin/workerman.server.log'
    'pid_file'       => '/dev/null', // ENABLE IT: __DIR__ . '/bin/workerman.server.pid'
    'backend_server' => [
        'workers' => 2,
        'host' => '0.0.0.0',
        'port' => 8080,
    ],
    'frontend_server' => [
        'workers' => 1,
        'host' => '0.0.0.0',
        'port' => 8081,
    ],
];
 
