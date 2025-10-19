# HyperAPI Framework 0.3

**Ultra-light PHP microframework for hypermedia APIs and HTML fragments.**

HyperAPI is designed to be **minimal, fast, and flexible**, perfect for:

- REST or hypermedia APIs  
- HTML fragments (compatible with HTMX)  
- SQLite or microservices  
- Traditional PHP server (`php -S`)  
- CLI mode with Workerman  

---

## Features

- Minimal core: only 3 classes (`Core`, `Request`, `Response`)  
- Middleware before and after routes  
- Named route parameters (`/tasks/{id}/toggle`)  
- Plugin/addon support  
- PDO wrapper for SQLite or other DBs  
- Response objects for HTML and JSON  
- PHP >= 8.0  
- Dual mode: traditional or CLI/Workerman  

---

## Requirements

- PHP >= 8.0  
- SQLite (optional, for the example)  
- Composer  
- Workerman (optional, for CLI mode)  

---

## Installation

Clone the repository and install dependencies:

```bash
git clone https://github.com/yourusername/hyperapi_php.git
cd hyperapi_php
composer install
```

---

## Usage

```php
use HyperAPI\Core;
use HyperAPI\Request;
use HyperAPI\Response;

$app = new Core();

$app->get('/api/tasks', function(Request $req, Response $res) {
    // Your logic here
    return $res->json(['tasks' => []]);
});

$app->run();
```

---

## Middleware Example

```php
$app->before(function() { 
    header('Access-Control-Allow-Origin: *'); 
});

$app->after(function($req, $res){
    // post-processing
});
```

---

## Plugin / Addon Example

```php
$app->useAddon('my_addon'); // loads addons/my_addon.php
```

