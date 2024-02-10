<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;

// Instatiating the router
$router = new Router();

// Get routes
$routes = require basePath('routes.php');

// Get current uri and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
// inspectAndDie($uri); 


// Route the request
$router->route($uri);
?>