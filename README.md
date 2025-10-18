# HyperAPI 0.3

**Ultra-light PHP microframework for hypermedia APIs and HTML fragments.**

HyperAPI is designed to be **minimal, fast, and flexible**, perfect for:

- REST or hypermedia APIs  
- HTML fragments (compatible with HTMX)  
- SQLite or microservices  
- Traditional PHP server (`php -S`)  
- CLI mode with Workerman  

---

## Features

- Minimal core: only 3 classes (`HyperAPI`, `Request`, `Response`)  
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
git clone https://github.com/yourusername/hyperapi.git
cd hyperapi
composer install
```

---

## Project Structure

```
HyperAPI_0.3/
 ├── src/          # Core framework
 │    ├── HyperAPI.php
 │    ├── Request.php
 │    └── Response.php
 ├── examples/     # Example usage
 │    ├── index.php
 │    └── routes.php
 ├── bin/          # CLI runner with Workerman
 │    └── hyperapi
 ├── addons/       # Optional plugins
 ├── database/     # SQLite DB
 ├── composer.json
 ├── README.md
 └── LICENSE
```

---

## Running EXAMPLE (Traditional PHP)

```bash
cd examples
php -S localhost:8000
```

Open your browser at [http://localhost:8000](http://localhost:8000)  

### Routes EXAMPLE

```
GET  /api/tasks             → list tasks (HTML fragment)
POST /api/tasks             → create a new task
POST /api/tasks/{id}/toggle → toggle task done
POST /api/tasks/{id}/delete → delete a task
```

---

## Running EXAMPLE (CLI / Workerman)

```bash
php bin/hyperapi 8080
```

- Runs an HTTP server on port 8080
- Supports concurrent connections
- Works with APIs and HTML fragments

---

## Creating a New Task (EXAMPLE)

### Using cURL

```bash
curl -X POST -d "title=Buy milk" http://localhost:8000/api/tasks
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

