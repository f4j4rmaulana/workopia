<?php
$config = require basePath('config/db.php');
$db = new Database($config);

$id = $_GET['id'] ?? '';

$params = [
  'id' => $id
];

$listing = $db->queryWithShareLock('SELECT * FROM listings WHERE id = :id', $params)->fetch();



if ($listing) {
  loadView('listings/show', ['listing' => $listing]);
} else {
  // loadView('error/404');
  $router = new Router();
  $router->error();
}
?>