<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;


class UserController {
  protected $db;

  public function __construct()
  {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Show the login page
   * 
   * @return void
   */
  public function login () {
    loadview('users/login');
  }

  /**
   * Show the register page
   * 
   * @return void
   */
  public function register() {
    loadview('users/register');
  }

  /**
   * Store user in database
   * @return void
   */
  public function store() {
    $name = $_POST['name'];
    $email= $_POST['email'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['password_confirmation'];

    $errors = [];

    // Validation
    if(!Validation::email($email)) {
      $errors['email'] = 'Please enter a valid email address';
    }
    if(!Validation::string($name, 2, 50)) {
      $errors['name'] = 'Name must be between 2 and 50 characters';
    }
    if(!Validation::string($password, 6, 50)) {
      $errors['password'] = 'Password must at least 6 characters';
    }
    if(!Validation::match($password,$passwordConfirmation)) {
      $errors['password_confirmation'] = 'Password do not match';
    }

    if(!empty($errors)) {
      loadView('users/register', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
          'province' => $province
        ]
        ]);
        exit;
    } 

    // Check if email exist
    $params = [
      'email' => $email
    ];

    $user = $this->db->queryWithShareLock('SELECT * FROM users WHERE email = :email', $params)->fetch();

    if($user) {
      $errors['email'] = 'Email already exists';
      loadView('users/register', [
        'errors' => $errors
      ]);
      exit;
    }

    // Create user account
    $params = [
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'province' => $province,
      'password' => password_hash($password, PASSWORD_DEFAULT),
    ];

    $this->db->query('INSERT INTO users (name, email, city, province, password) VALUES (:name, :email, :city, :province, :password)',$params);

    // Get new user ID
    $userId = $this->db->conn->lastInsertId();

    Session::set('user', [
      'id' => $userId,
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'province' => $province
    ]);

    redirect('/');
  }
}
?>