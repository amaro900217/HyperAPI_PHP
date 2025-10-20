<?php

// conf.php

return [
    'api_url_prefix' => '/api',
    'app_entrypoint' => __DIR__ . '/app.php',
    'public_path'    => __DIR__ . '/public',
    'log_file'       => '/dev/null', // ENABLE IT: __DIR__ . '/bin/workerman.server.log'
    'pid_file'       => '/dev/null', // ENABLE IT: __DIR__ . '/bin/workerman.server.pid'
    'workerman_server' => [
        'workers' => 4,
        'host' => '0.0.0.0',
        'port' => 8080,
    ],
    'static_cache_control' => 'public,max-age=3600', 
];
