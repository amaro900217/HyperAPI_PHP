<?php

// src/Request.php

namespace HyperAPI;

class Request {
    private array $params = [];
    public function __construct(array $params=[]) { $this->params = $params; }
    public function input(string $key, $default=null) {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    public function json(): array {
        $body = file_get_contents('php://input');
        return $body ? json_decode($body,true) ?? [] : [];
    }
    public function params(): array { return $this->params; }
    public function param(string $key, $default=null) { return $this->params[$key] ?? $default; }
}

