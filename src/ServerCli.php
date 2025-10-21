<?php

// src/ServerCli.php

namespace HyperAPI;

use Workerman\Worker;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class ServerCli {

    protected array $conf;
    protected mixed $app;

    public function __construct(array $conf) {
        $this->conf = $conf;
        $this->app  = require $conf['app_entrypoint'];
    }

    public function run(): void {
    
        if (php_sapi_name() !== 'cli') {
            http_response_code(403);
            exit('Forbidden');
        }

        Worker::$logFile = $this->conf['log_file'];
        Worker::$pidFile = $this->conf['pid_file'];

        $host = $this->conf['workerman_server']['host'];
        $port = $this->conf['workerman_server']['port'];
        $worker = new Worker("http://{$host}:{$port}");
        $worker->count = $this->conf['workerman_server']['workers'];
        $worker->name = 'hyperapi';
        $worker->onMessage = function ($connection, Request $request) {
            $this->handleRequest($connection, $request);
        };

        Worker::runAll();
    }

    protected function handleRequest($connection, Request $request): void {
        
        $path = $request->path();
        $publicPath = realpath($this->conf['public_path']);

        if (str_starts_with($path, $this->conf['api_url_prefix'])) {
            $_SERVER['REQUEST_URI'] = $path;
            $_SERVER['REQUEST_METHOD'] = $request->method();
            $_GET = $request->get() ?? [];
            $_POST = $request->post() ?? [];

            try {
                ob_start();
                $this->app->run();
                $connection->send(ob_get_clean());
            } catch (\Throwable $e) {
                $connection->send(new Response(500, [], "Internal Server Error\n" . $e->getMessage()));
                error_log($e->getMessage());
            }
            return;
        }

        //$normalizedPath = ($path === '/' ? '/index.html' : $path);
        //$file = realpath($publicPath . $normalizedPath);
        
        $normalizedPath = ($path === '/' ? 'index.html' : ltrim($path, '/'));
        $file = realpath(rtrim($publicPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $normalizedPath);

        if ($file && str_starts_with($file, $publicPath)) {
            $connection->send(
                (new Response())
                    ->withFile($file)
                    ->withHeader('Cache-Control', $this->conf['static_cache_control'])
            );
        } else {
            $connection->send(new Response(404, [], '404 Not Found'));
        }
    }
} 

