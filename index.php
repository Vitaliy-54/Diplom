<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
// ИЗМЕНИТЬ: убрать /../
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
// ИЗМЕНИТЬ: убрать /../
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
// ИЗМЕНИТЬ: убрать /../
(require_once __DIR__.'/bootstrap/app.php')
    ->handleRequest(Request::capture());