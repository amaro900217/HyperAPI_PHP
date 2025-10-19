<?php
namespace HyperAPI;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class Core {
    private array $before=[];
    private array $after=[];
    private array $addons=[];
    private $dispatcher;

    public function __construct() {
        $this->routes = [];  // Inicializar rutas aquí
        $this->dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
            // Routes will be added here when map() is called
        });
    }

    public function get(string $pattern, callable $handler) { $this->map('GET',$pattern,$handler); }
    public function post(string $pattern, callable $handler) { $this->map('POST',$pattern,$handler); }

    private function map(string $method,string $pattern, callable $handler) {
        // Acumular rutas en lugar de reconstruir dispatcher inmediatamente
        $this->routes[] = [$method, $pattern, $handler];
    }

    public function before(callable $fn) { $this->before[]=$fn; }
    public function after(callable $fn) { $this->after[]=$fn; }
    public function useAddon(string $name) {
        $file = __DIR__.'/../addons/'.$name.'.php';
        if(file_exists($file)) $this->addons[$name]=require $file;
    }

    public function run() {
        $uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Construir dispatcher con todas las rutas acumuladas
        $this->dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
            foreach($this->routes as [$m, $p, $h]) {
                $fp = preg_replace('/\\{(\\w+)\\}/','{$1}', $p);
                $r->addRoute($m, $fp, $h);
            }
        });

        foreach($this->before as $fn) $fn();

        $routeInfo = $this->dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $params = $routeInfo[2];

                $req = new Request($params);
                $res = new Response();
                $body = $handler($req,$res);

                foreach($this->after as $fn) $fn($req,$res);

                if(is_string($body)) $res->html($body);
                else if(is_array($body)) $res->json($body);
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                http_response_code(405);
                header('Allow: ' . implode(', ', $allowedMethods));
                echo "<div class='text-red-500'>405 - Method Not Allowed</div>";
                break;

            case Dispatcher::NOT_FOUND:
            default:
                http_response_code(404);
                echo "<div class='text-red-500'>404 - Not Found</div>";
                break;
        }
    }
}

