<?php

require '../vendor/autoload.php';
use App\Router;

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Redirection si page=1
if (isset($_GET['page']) && $_GET['page'] === '1') {
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if (!empty($query)) {
        $uri = $uri . '?' . $query;
    }
    header('Location: '. $uri);
    http_response_code(301);
    exit();
}

$router = new Router(dirname(__DIR__) . "/views");
$router
    ->get('/','post/index','home')
    ->get('/blog','post/index','blog')
    ->get('/blog/category/[*:slug]-[i:id]','category/show','category')
    ->get('/blog/[*:slug]-[i:id]','post/show','post')
    ->get('/admin','admin/index','admin')
    ->get('/admin/post','admin/post/show','admin_post')
    ->run();