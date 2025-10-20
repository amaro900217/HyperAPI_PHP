#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

$conf = require __DIR__ . '/../conf.php';
$app = require $conf['app_entrypoint'];

Worker::$logFile = $conf['log_file'];
Worker::$pidFile = $conf['pid_file'];

$apiWorker = new Worker("http://{$conf['backend_server']['host']}:{$conf['backend_server']['port']}");
$apiWorker->count = $conf['backend_server']['workers'];
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

$staticWorker = new Worker("http://{$conf['frontend_server']['host']}:{$conf['frontend_server']['port']}");
$staticWorker->count = $conf['frontend_server']['workers'];
$staticWorker->name = 'frontend';
$staticWorker->onMessage = function($connection, Request $request) use ($conf) {
    $path = $request->path();
    $publicPath = $conf['public_path'];
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
 
