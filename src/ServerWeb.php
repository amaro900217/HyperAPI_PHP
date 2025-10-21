<?php

// src/ServerWeb.php

namespace HyperAPI;

class ServerWeb {

    protected array $conf;
    protected mixed $app;
    
    public function __construct(array $conf) {
        $this->conf = $conf;
        $this->app  = require $conf['app_entrypoint'];
    }

    public function run(): void {
        $this->app->run();
    }
} 


