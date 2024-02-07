<?php

use Framework\Database;


$listings = $db->queryWithShareLock('SELECT * FROM listings LIMIT 6')->fetchAll();

// inspect($listings); tes

loadView('home', [
  'listings' => $listings,
  // 'name' => 'tom'
]);
?>