<?php
namespace HyperAPI;

class HyperAPI {
    private array $routes=[];
    private array $before=[];
    private array $after=[];
    private array $addons=[];

    public function get(string $pattern, callable $handler) { $this->map('GET',$pattern,$handler); }
    public function post(string $pattern, callable $handler) { $this->map('POST',$pattern,$handler); }
    private function map(string $method,string $pattern, callable $handler) {
        $pattern = preg_replace('/\\{(\\w+)\\}/','(?P<$1>[^/]+)',$pattern);
        $this->routes[] = [$method,"#^$pattern$#",$handler];
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
        foreach($this->before as $fn) $fn();
        foreach($this->routes as [$m,$regex,$handler]) {
            if($m===$method && preg_match($regex,$uri,$matches)) {
                $params=[];
                foreach($matches as $k=>$v) if(!is_int($k)) $params[$k]=$v;
                $req = new Request($params);
                $res = new Response();
                $body = $handler($req,$res);
                foreach($this->after as $fn) $fn($req,$res);
                if(is_string($body)) $res->html($body);
                else if(is_array($body)) $res->json($body);
                return;
            }
        }
        http_response_code(404);
        echo "<div class='text-red-500'>404 - Not Found</div>";
    }
}

