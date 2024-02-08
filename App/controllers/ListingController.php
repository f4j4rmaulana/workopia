<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController {

  protected $db;

  public function __construct()
  {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);

  }

  /**
   * Show all listings
   *
   * @return void
   */
  public function index() 
  {

    $listings = $this->db->queryWithShareLock('SELECT * FROM listings')->fetchAll();

  // inspect($listings); tes

    loadView('listings/index', [
      'listings' => $listings,
      // 'name' => 'tom'
    ]);
  }

  /**
   * Create a listings
   *
   * @return void
   */
  public function create() 
  {
    loadView('listings/create');
  }

  /**
   * Show detail listing
   *
   * @return void
   */
  public function show($params) 
  {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $listing = $this->db->queryWithShareLock('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    /**
     * Error if id listings not found
     */
    if (!$listing) {
      ErrorController::notFound('Listings not found');
      
    } else {
      // loadView('error/404');
      loadView('listings/show', ['listing' => $listing]);
    }
  }

  /**
   * Store data in Database
   * 
   * @return void
   */
  public function store() {

    $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'company', 'address', 'city', 'province', 'phone', 'email'];

    $newListingData = array_intersect_key($_POST,array_flip($allowedFields));

    $newListingData['user_id'] = 1;

    $newListingData = array_map('sanitize', $newListingData);

    $requiredFields = ['title', 'description', 'city', 'province', 'phone', 'email'];

    $errors = [];

    foreach ($requiredFields as $field) {
      if(empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if (!empty($errors)) {
      // reload view with errors
      loadView('listings/create', [
        'errors' => $errors,
        'listing' => $newListingData
      ]);
    } else {
      echo 'success';
    }
  }
}