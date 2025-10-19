<?php

// Cargar la configuración y rutas desde app.php
$app = require_once __DIR__ . '/../app.php';

// Ejecutar el framework en modo tradicional
$app->run();