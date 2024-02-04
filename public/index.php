<?php
require '../helpers.php';
require basePath('Database.php');
require basePath('Router.php');

// Instatiating the router
$router = new Router();

// Get routes
$routes = require basePath('routes.php');

// Get current uri and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
// inspectAndDie($uri); 
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
$router->route($uri, $method);
?>