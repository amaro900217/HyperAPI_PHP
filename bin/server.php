#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Workerman\Worker;

// Cargar la configuración y rutas desde app.php
$app = require __DIR__ . '/../app.php';

$port = $argv[1] ?? 8080;

$worker = new Worker("http://0.0.0.0:$port");
$worker->count = 4;

$worker->onMessage = function($connection, $request) use ($app){
    $_SERVER['REQUEST_URI'] = $request->path();
    $_SERVER['REQUEST_METHOD'] = $request->method();
    $_GET = $request->get() ?? [];
    $_POST = $request->post() ?? [];
    ob_start();
    $app->run();
    $content = ob_get_clean();
    $connection->send($content);
};

Worker::runAll();

