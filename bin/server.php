#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

$config = require __DIR__ . '/../conf.php';
$app = require __DIR__ . '/../app.php';

$apiWorker = new Worker("http://{$config['backend_server']['host']}:{$config['backend_server']['port']}");
$apiWorker->count = $config['backend_server']['workers'];
$apiWorker->name = 'backend';
$apiWorker->onMessage = function($connection, $request) use ($app){
    $_SERVER['REQUEST_URI'] = $request->path();
    $_SERVER['REQUEST_METHOD'] = $request->method();
    $_GET = $request->get() ?? [];
    $_POST = $request->post() ?? [];
    ob_start();
    $app->run();
    $content = ob_get_clean();
    $connection->send($content);
};

$staticWorker = new Worker("http://{$config['frontend_server']['host']}:{$config['frontend_server']['port']}");
$staticWorker->count = $config['frontend_server']['workers'];
$staticWorker->name = 'frontend';
$staticWorker->onMessage = function($connection, Request $request) use ($config) {
    $path = $request->path();
    $publicPath = $config['frontend_server']['public_path'];
    $normalizedPath = $path === '/' ? '' : $path;
    if ($normalizedPath === '' || is_dir($publicPath . $normalizedPath)) {
        $indexFile = $publicPath . $normalizedPath . '/index.html';
        if (is_file($indexFile)) {
            $connection->send((new Response())->withFile($indexFile));
            return;
        }
    }
    $file = $publicPath . $normalizedPath;
    if (is_file($file)) {
        $connection->send((new Response())->withFile($file));
    } else {
        $connection->send(new Response(404, [], '404 Not Found'));
    }
};

Worker::runAll();
 