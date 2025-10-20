# HyperAPI Framework

**Ultra-light PHP microframework for hypermedia APIs and HTML fragments.**

HyperAPI is designed to be **minimal, fast, and flexible**, ideal for building:

* REST APIs for Hypermedia systems using HTML fragments
* Microservices

---

## 🚀 Features

* **Minimal core**: Only 3 classes (`Kernel`, `Request`, `Response`)
* **Middleware**: Before and after route processing
* **Named parameters**: Routes like `/tasks/{id}/toggle`
* **Addon support**: Load custom plugins from `addons/`
* **Response objects**: HTML and JSON
* **PHP >= 8.0** compatible
* **Dual mode**: Traditional FPM/Apache or Workerman CLI server

---

## 📋 Requirements

* PHP >= 8.0
* Composer

---

## 🛠️ Installation

```bash
git clone https://github.com/amaro900217/HyperAPI_PHP.git
cd HyperAPI_PHP
composer install
```

---

## 💡 Usage

### Basic Example

```php
use HyperAPI\Kernel;
use HyperAPI\Request;
use HyperAPI\Response;

$app = new Kernel();

// Define a route
$app->get('/api/tasks', function(Request $req, Response $res) {
    return $res->json(['tasks' => []]);
});

// Handle requests
$app->run();
```

### Middleware

```php
// Runs before route handler
$app->before(function() {
    header('Access-Control-Allow-Origin: *');
});

// Runs after route handler
$app->after(function(Request $req, Response $res) {
    // Post-processing
});
```

### Addons

```php
$app->useAddon('my_addon'); // loads addons/my_addon.php
```

---

## 🏗️ Architecture

### Core Classes

* **`Kernel`**: Main framework
* **`Request`**: HTTP request wrapper
* **`Response`**: HTTP response builder

### Routing

```php
$app->get('/path', $handler);
$app->post('/path', $handler);
```

---

## 🌐 Server Modes

### Traditional (FPM / PHP Built-in)

```bash
php -S localhost:8080 -t public
```

### Workerman (High Performance CLI)

```bash
php public/api/index.php start      # foreground
php public/api/index.php start -d   # daemon
php public/api/index.php stop|restart|status
```

* Serves both API (`/api`) and static assets from `public/`
* Configurable cache headers via `conf.php`

---

## ⚙️ Configuration (`conf.php`)

```php
<?php
return [
    'api_url_prefix' => '/api',
    'app_entrypoint' => __DIR__ . '/app.php',
    'public_path'    => __DIR__ . '/public',
    'log_file'       => '/dev/null', // ENABLE IT: __DIR__ . '/bin/workerman.server.log'
    'pid_file'       => '/dev/null', // ENABLE IT: __DIR__ . '/bin/workerman.server.pid'
    'workerman_server' => [
        'workers' => 4,
        'host' => '0.0.0.0',
        'port' => 8080,
    ],
    'static_cache_control' => 'public,max-age=3600', 
];
```

* `static_cache_control` applies to JS, CSS, images, and HTML served (only via Workerman)
* `api_url_prefix` defines the API routes prefix

---

## 📁 Project Structure

```
hyperapi_php/
├── src/                  # Core framework
│   ├── Kernel.php        # Main class
│   ├── Request.php       # Request wrapper
│   └── Response.php      # Response builder
├── bin/                  # CLI entry points
│   ├── cli.php           # Workerman server
│   └── web.php           # FPM/Web entry point
├── public/               # Public assets
│   ├── api/index.php     # API entry point
│   └── index.html        # Client page
├── conf.php              # Configuration
├── app.php               # Application routes/examples
└── composer.json         # Composer dependencies
```

---

## 📝 License

MIT License - see LICENSE file.

