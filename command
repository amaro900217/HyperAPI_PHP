#!/usr/bin/env php
<?php

$options = [
    'help' => false,
    'serve-web' => false,
    'serve-cli' => false,
    'benchmark' => null,
];

// Parsear argumentos simples
for ($i = 1; $i < $argc; $i++) {
    $arg = $argv[$i];
    switch ($arg) {
        case '--help':
            $options['help'] = true;
            break;
        case '--serve-web':
            $options['serve-web'] = true;
            break;
        case '--serve-cli':
            $options['serve-cli'] = true;
            break;
        case '--benchmark':
            // Si hay un siguiente argumento que no empieza con "--", lo tomamos como URL
            $next = $argv[$i + 1] ?? null;
            if ($next && !str_starts_with($next, '--')) {
                $options['benchmark'] = $next;
                $i++; // saltar el argumento consumido
            } else {
                // URL por defecto
                $options['benchmark'] = 'http://0.0.0.0:8080';
            }
            break;
    }
}

// Mostrar ayuda
if ($options['help']) {
    echo "Uso: php command [--help] [--serve-web] [--serve-cli] [--benchmark URL]\n";
    echo "--help       Muestra este mensaje de ayuda.\n";
    echo "--serve-web  Inicia el servidor WEB en http://0.0.0.0:8080.\n";
    echo "--serve-cli  Inicia el servidor CLI en http://0.0.0.0:8080.\n";
    echo "--benchmark  Ejecuta benchmark con wrk (Linux x64). Se puede pasar URL opcional.\n";
    exit;
}

// Ejecutar servidor web
if ($options['serve-web']) {
    echo "Iniciando servidor web en localhost:8080...\n";
    passthru("php -S 0.0.0.0:8080 -t public");
    exit;
}

// Ejecutar servidor CLI
if ($options['serve-cli']) {
    echo "Iniciando servidor CLI...\n";
    passthru("php public/api/index.php start");
    exit;
}

// Ejecutar wrk-test
if ($options['benchmark']) {
    if (PHP_OS_FAMILY !== 'Linux') {
        echo "--benchmark solo está disponible en Linux.\n";
        exit(1);
    }

    $wrkPath = __DIR__ . '/tests/bin/wrk_linux_x64';
    if (!file_exists($wrkPath) || !is_executable($wrkPath)) {
        echo "wrk no encontrado o no es ejecutable en $wrkPath\n";
        exit(1);
    }

    $url = $options['benchmark'];
    echo "Ejecutando wrk benchmark en $url...\n";
    passthru("$wrkPath -t10 -c1000 -d10s $url");
    exit;
}

// Si no se pasa ninguna opción
echo "Uso: php command [--help] [--serve-web] [--serve-cli] [--benchmark URL]\n";

