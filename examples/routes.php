<?php
use HyperAPI\HyperAPI;
use HyperAPI\Request;
use HyperAPI\Response;

$app = new HyperAPI();

$app->before(fn()=>header('Access-Control-Allow-Origin: *'));

$pdo = new PDO('sqlite:'.__DIR__.'/../database/tasks.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$pdo->exec("CREATE TABLE IF NOT EXISTS tasks (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, done INTEGER DEFAULT 0)");

$app->get('/api/tasks', function(Request $req, Response $res) use ($pdo) {
    $tasks = $pdo->query("SELECT * FROM tasks ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    $html="<ul class='space-y-2'>";
    foreach($tasks as $t){
        $checked=$t['done']?'checked':'';
        $class=$t['done']?'line-through text-gray-500':'';
        $html.="<li class='flex items-center gap-2 p-2 border rounded'>
            <input type='checkbox' data-action='toggle' data-id='{$t['id']}' $checked>
            <span class='{$class}'>".htmlspecialchars($t['title'],ENT_QUOTES,'UTF-8')."</span>
            <button data-action='delete' data-id='{$t['id']}' class='text-red-500'>✕</button>
        </li>";
    }
    $html.="</ul>";
    return $html;
});

$app->post('/api/tasks', function(Request $req, Response $res) use ($pdo){
    $title = trim($req->input('title'));
    if($title){
        $stmt = $pdo->prepare("INSERT INTO tasks (title) VALUES (:t)");
        $stmt->execute([':t'=>$title]);
    }
    return "<div hx-get='/api/tasks' hx-trigger='load'></div>";
});

$app->post('/api/tasks/{id}/toggle', function(Request $req, Response $res) use ($pdo){
    $id = (int)$req->param('id');
    $pdo->exec("UPDATE tasks SET done=1-done WHERE id=$id");
    return "<div hx-get='/api/tasks' hx-trigger='load'></div>";
});

$app->post('/api/tasks/{id}/delete', function(Request $req, Response $res) use ($pdo){
    $id = (int)$req->param('id');
    $pdo->exec("DELETE FROM tasks WHERE id=$id");
    return "<div hx-get='/api/tasks' hx-trigger='load'></div>";
});

return $app;

