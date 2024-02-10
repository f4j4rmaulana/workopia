<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Router;

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
}
?>