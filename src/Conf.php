<?php

// src/Conf.php

$basePath = dirname(__DIR__, 1);

return [
    'api_url_prefix'   => '/api',
    'app_entrypoint'   => realpath($basePath . '/app/main.php'),
    'public_path'      => realpath($basePath . '/public'),
    'log_file'         => sys_get_temp_dir() . '/workerman.log',
    'pid_file'         => sys_get_temp_dir() . '/workerman.pid',
    'workerman_server' => [
        'workers' => 4,
        'host'    => '0.0.0.0',
        'port'    => 8080,
    ],
    'static_cache_control' => 'public,max-age=3600',
];

