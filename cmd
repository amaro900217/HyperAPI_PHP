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
            $next = $argv[$i + 1] ?? null;
            if ($next && !str_starts_with($next, '--')) {
                $options['benchmark'] = $next;
                $i++;
            } else {
                $options['benchmark'] = 'http://0.0.0.0:8080';
            }
            break;
    }
}

// Mostrar ayuda
if ($options['help']) {
    echo "Uso: php cmd [--help] [--serve-web] [--serve-cli] [--benchmark URL]\n";
    echo "--help       Muestra este mensaje de ayuda.\n";
    echo "--serve-web  Inicia el servidor WEB en http://0.0.0.0:8080.\n";
    echo "--serve-cli  Inicia el servidor CLI en http://0.0.0.0:8080.\n";
    echo "--benchmark  Ejecuta benchmark con hey (Linux/macOS/Windows). Se puede pasar URL opcional.\n";
    exit;
}

// Ejecutar servidor web
if ($options['serve-web']) {
    echo "Iniciando servidor web en http://0.0.0.0:8080...\n";
    passthru("php -S 0.0.0.0:8080 -t public");
    exit;
}

// Ejecutar servidor CLI
if ($options['serve-cli']) {
    echo "Iniciando servidor CLI...\n";
    passthru("php public/api/index.php start");
    exit;
}

// Ejecutar benchmark con hey
if ($options['benchmark']) {
    $os = PHP_OS_FAMILY;
    $arch = php_uname('m');
    $binDir = __DIR__ . '/dev/bin';

    switch ($os) {
        case 'Linux':
            $heyBinary = "$binDir/hey_linux_amd64";
            break;
        case 'Darwin': // macOS
            $heyBinary = "$binDir/hey_darwin_amd64";
            break;
        case 'Windows':
            $heyBinary = "$binDir/hey_windows_amd64.exe";
            break;
        default:
            echo "Sistema operativo no soportado para benchmark con hey.\n";
            exit(1);
    }

    if (!file_exists($heyBinary)) {
        echo "Error: hey no encontrado en $heyBinary\n";
        exit(1);
    }

    // Asegurar permisos de ejecución (solo en Unix)
    if ($os !== 'Windows' && !is_executable($heyBinary)) {
        @chmod($heyBinary, 0755);
    }

    $url = $options['benchmark'];
    echo "Ejecutando benchmark con hey en $url...\n";
    echo "Usando binario: $heyBinary\n\n";

    // Ejecutar hey con parámetros estándar
    $cmd = ($os === 'Windows')
        ? "\"$heyBinary\" -c 500 -z 10s $url"
        : "$heyBinary -c 500 -z 10s  $url";

    passthru($cmd);
    exit;
}

// Si no se pasa ninguna opción
echo "Uso: php cmd [--help] [--serve-web] [--serve-cli] [--benchmark URL]\n";

