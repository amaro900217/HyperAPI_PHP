<?php

// src/Response.php 

namespace HyperAPI;

class Response {
    private int $status = 200;
    public function status(int $code): self { $this->status=$code; http_response_code($code); return $this; }
    public function header(string $k,string $v): self { header("$k: $v"); return $this; }
    public function html(string $html): void { header('Content-Type: text/html; charset=utf-8'); echo $html; }
    public function json($data,int $status=200): void { $this->status($status); header('Content-Type: application/json'); echo json_encode($data, JSON_UNESCAPED_UNICODE); }
}

