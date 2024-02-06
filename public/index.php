<?php
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;


/** Example autoloader
spl_autoload_register(function ($class) {
  $path = basePath('Framework/' . $class . '.php');
  if (file_exists($path)) {
    require $path;
  }
});
*/

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