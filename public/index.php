<?php

// Cargar la configuración y rutas a usar desde conf.php
$conf = require __DIR__ . '/../conf.php';
$app  = require_once $conf['app_entrypoint'];

// Ejecutar el framework en modo tradicional
$app->run();
