<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// // print current path information for debugging
// echo "Current working directory: " . getcwd() . "<br>";
// echo "Application base path: " . dirname(__DIR__) . "<br>";
// echo "Routes path: " . realpath(dirname(__DIR__) . '/routes/api.php') . "<br>";
// echo "Storage path: " . realpath(dirname(__DIR__) . '/storage') . "<br>";
// echo "<hr>";

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// HTTP Kernel インスタンスを解決してリクエストを処理する
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
