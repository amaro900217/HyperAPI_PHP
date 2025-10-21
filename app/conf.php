<?php

// app/conf.php

return [
    'api_url_prefix' => '/api',
    'app_entrypoint' => realpath(__DIR__ . '/main.php'),
    'public_path'    => realpath(__DIR__ . '/../public'),
    'log_file'       => sys_get_temp_dir() . '/workerman.log',
    'pid_file'       => sys_get_temp_dir() . '/workerman.pid',
    'workerman_server' => [
        'workers' => 4,
        'host' => '0.0.0.0',
        'port' => 8080,
    ],
    'static_cache_control' => 'public,max-age=3600', 
];
