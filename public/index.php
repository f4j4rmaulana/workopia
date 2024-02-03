<?php
require '../helpers.php'; // fungsi untuk path didalamnya
// loadView('home'); 

$uri = $_SERVER['REQUEST_URI'];

// inspectAndDie($uri); check uri

require basePath('router.php');

?>