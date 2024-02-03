<?php
require '../helpers.php'; // fungsi untuk path didalamnya
// loadView('home'); 

$routes = [
  '/' => 'controllers/home.php',
  '/listings' => 'controllers/listings/index.php',
  '/listings/create' => 'controllers/listings/create.php',
  '404' => 'controllers/error/404.php'
];

$uri = $_SERVER['REQUEST_URI'];

// inspectAndDie($uri); check uri

if(array_key_exists($uri,$routes)) {
  require(basePath($routes[$uri])); 
} else {
  require basePath(($routes['404']));
}

?>