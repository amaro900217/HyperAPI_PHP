<?php
return [
    'backend_server' => [
        'workers' => 4,
        'host' => '0.0.0.0',
        'port' => 8080,
    ],
    'frontend_server' => [
        'workers' => 2,
        'host' => '0.0.0.0',
        'port' => 8081,
        'public_path' => __DIR__ . '/public',
    ],
];

