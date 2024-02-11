<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

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

    $listings = $this->db->queryWithShareLock('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();

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
   * @param array $params
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

    $newListingData['user_id'] = Session::get('user')['id'];

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
      // Submit Data

      $fields = [];

      /* Kalau gunakan array reduce dan keys
      $fields = array_reduce(array_keys($newListingData), function ($carry, $field) {
        return $carry !== '' ? $carry . ', ' . $field : $field;
        }, '');

      */

      foreach($newListingData as $field => $value) {
        $fields[] = $field;
      }
      $fields = implode(', ', $fields);

      $values = [];

      foreach($newListingData as $field => $value) {
        // convert empty string into null
        if($value === '') {
          $newListingData[$field] = null;
        }
        $values[] = ':' . $field;
      }
      $values = implode(', ', $values);

      $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

      $this->db->query($query, $newListingData);

      Session::setFlashMessage('success_message', 'Listing created successfully');

      redirect('/listings');
    }
  }

  /**
   * Delete a listing
   * 
   * @param array $params
   * @return void
   */
  public function destroy($params) {
    $id = $params['id'];

    $params = [
      'id' => $id
    ];

    $listing = $this->db->queryWithShareLock('SELECT * FROM listings WHERE id = :id', $params)->fetch();
    
    // Check if listing exists
    if(!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    // Authorization
    if(!Authorization::isOwner($listing->user_id)) {
      Session::setFlashMessage('error_message', 'You\'re not authorized to delete this listing');
      return redirect('/listings/' . $listing->id);
    }

      $this->db->queryWithShareLock('DELETE FROM listings WHERE id = :id', $params);

      // Set flash message
      Session::setFlashMessage('success_message', 'Listing deleted successfully');

      redirect('/listings');
  }

   /**
   * Show the listing edit form
   *
   * @param array $params
   * @return void
   */
  public function edit($params) 
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
      return;
    }

    if(!Authorization::isOwner($listing->user_id)) {
      Session::setFlashMessage('error_message', 'You\'re not authorized to update this listing');
      return redirect('/listings/' . $listing->id);
    }

      // loadView('error/404');
      loadView('listings/edit', [
        'listing' => $listing]);
  }

  /**
   * Update a listing
   * 
   * @param array $params
   * @return void
   */
  public function update($params) {
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
      return;
    }

    // Authorization
    if(!Authorization::isOwner($listing->user_id)) {
      Session::setFlashMessage('error_message', 'You\'re not authorized to update this listing');
      return redirect('/listings/' . $listing->id);
    }
    
    $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'company', 'address', 'city', 'province', 'phone', 'email'];

    $valueToUpdate = [];

    $valueToUpdate = array_intersect_key($_POST,array_flip($allowedFields));

    $valueToUpdate = array_map('sanitize', $valueToUpdate);

    $requiredFields = ['title', 'description', 'city', 'province', 'phone', 'email'];

    $errors = [];

    foreach ($requiredFields as $field) {
      if(empty($valueToUpdate[$field]) || !Validation::string($valueToUpdate[$field])) {
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    if(!empty($errors)) {
      loadView('listings/edit', [
        'listing'=> $listing,
        'errors' => $errors
      ]);
      exit;
    } else {
      // submit to database
      $updateFields = [];

      foreach(array_keys($valueToUpdate) as $field) {
        $updateFields[] = "$field = :{$field}";
      }

      $updateFields = implode(', ', $updateFields);

      $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

      $valueToUpdate['id'] = $id;
      $this->db->queryWithShareLock($updateQuery, $valueToUpdate);

      Session::setFlashMessage('success_message', 'Listing updated successfully');
      
      redirect('/listings/' . $id);
    }
  }

  /**
   * Search listings by keyword/location
   * 
   * @return void
   */
  public function search() {
    $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';

    $query = "SELECT * FROM listings WHERE (LOWER(title) LIKE LOWER(:keywords) OR LOWER(description) LIKE LOWER(:keywords) OR LOWER(company) LIKE LOWER(:keywords) OR LOWER(tags) LIKE LOWER(:keywords)) AND LOWER(city) LIKE LOWER(:location) OR LOWER(province) LIKE LOWER(:location)";

    $params = [
      'keywords' => "%{$keywords}%",
      'location' => "%{$location}%" 
    ];

    $listings = $this->db->queryWithShareLock($query, $params)->fetchAll();

    loadView('/listings/index',[
      'listings' => $listings,
      'keywords' => $keywords,
      'location' => $location
    ]);
  }
}