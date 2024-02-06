<?php

use Framework\Database;

$config = require basePath('config/db.php');
$db = new Database($config);

$listings = $db->queryWithShareLock('SELECT * FROM listings LIMIT 6')->fetchAll();

// inspect($listings); tes

loadView('listings/index', [
  'listings' => $listings,
  // 'name' => 'tom'
]);