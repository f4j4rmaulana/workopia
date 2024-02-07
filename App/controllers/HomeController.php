<?php

namespace App\Controllers;

use Framework\Database;

class HomeController {

  protected $db;

  public function __construct()
  {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);

  }


  /**
   * Show all listings with limit 6
   *
   * @return void
   */
  public function index() 
  {
    $listings = $this->db->queryWithShareLock('SELECT * FROM listings LIMIT 6')->fetchAll();

  // inspect($listings); tes

    loadView('home', [
      'listings' => $listings,
      // 'name' => 'tom'
    ]);
  }
}