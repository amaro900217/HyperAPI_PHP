# HyperAPI Framework

**Ultra-light PHP microframework for hypermedia APIs and HTML fragments.**

HyperAPI is designed to be **minimal, fast, and flexible**, perfect for building:

- REST or hypermedia APIs
- Hypermedia Systems that use HTML fragments
- Microservices

## 🚀 Features

- **Minimal core**: Only 3 classes (`Kernel`, `Request`, `Response`)
- **Middleware**: Before and after route processing
- **Named parameters**: Routes like `/tasks/{id}/toggle`
- **Plugin support**: Addon system for extensibility
- **Response objects**: HTML and JSON responses
- **PHP >= 8.0** compatibility
- **Dual mode**: Traditional web server or FAST CLI/Workerman

## 📋 Requirements

- **PHP >= 8.0**
- **Composer** (for dependency management)

## 🛠️ Installation

```bash
# Clone the repository
git clone <repository-url>
cd hyperapi_php

# Install dependencies
composer install
```

## 💡 Usage

### Basic Example

```php
use HyperAPI\Kernel;
use HyperAPI\Request;
use HyperAPI\Response;

$app = new Kernel();

// Define a route
$app->get('/api/tasks', function(Request $req, Response $res) {
    // Your API logic here
    return $res->json(['tasks' => []]);
});

// Handle requests
$app->run();
```

### Middleware

```php
// Before middleware (runs before route handler)
$app->before(function() {
    header('Access-Control-Allow-Origin: *');
});

// After middleware (runs after route handler)
$app->after(function($req, $res) {
    // Post-processing logic
});
```

### Plugins/Addons

```php
// Load custom addon
$app->useAddon('my_addon'); // loads addons/my_addon.php
```

## 🏗️ Architecture

### Core Classes

- **`Kernel`**: Main application framework
- **`Request`**: HTTP request wrapper with utilities
- **`Response`**: HTTP response builder for HTML/JSON

### Routing

```php
// GET routes
$app->get('/path', $handler);

// POST routes
$app->post('/path', $handler);

// PUT/PATCH routes
$app->put('/path/{id}', $handler);

// DELETE routes
$app->delete('/path/{id}', $handler);
```

## 🌐 Server Modes

### Traditional Mode

```bash
# Using built-in PHP server
php -S localhost:8080 -t public
```

### Workerman Mode (High Performance)

```bash
# Start Workerman server
php bin/server.php start

# Or run as daemon
php bin/server.php start -d

# Management commands
php bin/server.php stop|restart|status
```

## ⚙️ Configuration

Configure via `conf.php`:

```php
<?php
return [
    'backend_server' => [
        'workers' => 4,
        'host' => '0.0.0.0',
        'port' => 8080,
    ],
    'frontend_server' => [
        'workers' => 2,
        'host' => '0.0.0.0',
        'port' => 8081,
        'public_path' => __DIR__ . '/public',
    ],
];
```

## 📁 Project Structure

```
hyperapi_php/
├── src/                  # Framework core
│   ├── Kerenel.php       # Main framework class
│   ├── Request.php       # Request handling
│   └── Response.php      # Response building
├── bin/                  # Executables
│   └── server.php        # Workerman server
├── public/               # Public folder
│   ├── index.php         # FPM entry point
│   └── index.html        # Client page
├── conf.php              # Configuration
├── app.php               # Application routes (example)
└── composer.json         # Dependencies
```

## 📝 License

MIT License - see LICENSE file for details.
