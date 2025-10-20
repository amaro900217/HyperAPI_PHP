<?php

// bin/cli.php

php_sapi_name() !== 'cli' ? exit(http_response_code(403) ?: 'Forbidden') : null;

require __DIR__ . '/../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

$conf = require __DIR__ . '/../conf.php';
$app = require $conf['app_entrypoint'];

Worker::$logFile = $conf['log_file'];
Worker::$pidFile = $conf['pid_file'];

$host = $conf['workerman_server']['host'];
$port = $conf['workerman_server']['port'];

$worker = new Worker("http://{$host}:{$port}");
$worker->count = $conf['workerman_server']['workers'];
$worker->name = 'hyperapi';

$worker->onMessage = function($connection, Request $request) use ($app, $conf) {
    $path = $request->path();
    $publicPath = realpath($conf['public_path']);
    if (str_starts_with($path, $conf['api_url_prefix'])) {
        $_SERVER['REQUEST_URI'] = $path;
        $_SERVER['REQUEST_METHOD'] = $request->method();
        $_GET = $request->get() ?? [];
        $_POST = $request->post() ?? [];
        try {
            ob_start();
            $app->run();
            $connection->send(ob_get_clean());
        } catch (\Throwable $e) {
            $connection->send(
                new Response(500, [], "Internal Server Error\n" . $e->getMessage())
            );
            error_log($e->getMessage());
        }
        return;
    }
    $normalizedPath = ($path === '/' ? '/index.html' : $path);
    $file = realpath($publicPath . $normalizedPath);
    if ($file && str_starts_with($file, $publicPath)) {
        $connection->send(
            (new Response())->withFile($file)->withHeader('Cache-Control', $conf['static_cache_control'])
        );
    } else {
        $connection->send(new Response(404, [], '404 Not Found'));
    }
};
Worker::runAll();

