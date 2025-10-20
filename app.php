<?php

// app.php

require_once __DIR__ . '/vendor/autoload.php';

use HyperAPI\Kernel;
use HyperAPI\Request;
use HyperAPI\Response;

$app = new Kernel();

// -----------------------------
// RUTAS (ejemplos simples)
// -----------------------------

$app->get('/api', function(Request $req, Response $res) {
    return $res->html('<h3>Hello World!! (Backend)</h3>');
});

$app->get('/api/user/{id}', function(Request $req, Response $res) {
    $id = $req->param('id');  // Obtener parámetro de la URL
    return $res->json(['user_id' => $id, 'message' => 'Usuario encontrado']);
});

$app->post('/api/tasks', function(Request $req, Response $res) {
    $title = $req->input('title');  // Obtener datos del formulario o JSON
    return $res->json(['success' => true, 'task' => $title]);
});

$app->get('/api/not-found', function(Request $req, Response $res) {
    return $res->status(404)->html('<h1>404 - Página no encontrada</h1>');
});

// -----------------------------
// MIDDLEWARE (ejemplos simples)
// -----------------------------

$app->before(function() {
    header('Access-Control-Allow-Origin: *');  // Permitir CORS
    header('X-Powered-By: HyperAPI');  // Header custom
});

$app->after(function(Request $req, Response $res) {
    file_put_contents('log.txt', $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);
});

// -----------------------------
// RESPUESTAS VARIADAS (HTML, JSON, etc.)
// -----------------------------
// Ya viste ejemplos arriba, pero aquí más opciones:
// - $res->html($html); para texto/HTML
// - $res->json($data); para JSON
// - $res->status(404); para errores
// - $res->header('Custom', 'Value'); para headers adicionales

// -----------------------------
// MIDDLEWARE (ejecutan antes/durante la request)
// -----------------------------

// -----------------------------
// ADDONS (si tienes en carpeta addons/)
// -----------------------------
// Ejemplo: $app->useAddon('mi_addon');  // Carga addons/mi_addon.php si existe

// -----------------------------
// NOTAS PARA EL DESARROLLADOR:
// - Puedes agregar más rutas aquí o en archivos separados.
// - Para arquitecturas complejas (MVC), crea carpetas como app/Controllers/ y carga aquí.
// - Prueba con: php -S localhost:8000 public/index.php o php bin/server.php 8080
// -----------------------------

// Retornar la instancia para que los puntos de entrada la usen
return $app;
