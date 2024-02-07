<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Router;

class ListingController {

  protected $db;

  public function __construct()
  {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);

  }

  public function index() 
  {
    $listings = $this->db->queryWithShareLock('SELECT * FROM listings')->fetchAll();

  // inspect($listings); tes

    loadView('listings/index', [
      'listings' => $listings,
      // 'name' => 'tom'
    ]);
  }

  public function create() 
  {
    loadView('listings/create');
  }

  public function show() 
  {
    $id = $_GET['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $listing = $this->db->queryWithShareLock('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    /**
     * Error if id listings not found
     */
    if ($listing) {
      loadView('listings/show', ['listing' => $listing]);
    } else {
      // loadView('error/404');
      $router = new Router();
      $router->error();
    }
  }
}