<?php

namespace Framework;

use PDO;
use PDOException;
use Exception;

class Database {
  public $conn;

  /**
   * Constructor for Database class
   * 
   * @param array $config
   */
  public function __construct($config)
  {
    $dsn ="pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    try {
      $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
    } catch (PDOException $e) {
      throw new Exception("Database connection failed: {$e->getMessage()}");
    }
  }

  /**
   * Query the database
   * 
   * @param string $query
   * 
   * @return PDOStatement
   * @throws PDOException
   */
  public function query($query, $params = []) {
    try {
      $sth = $this->conn->prepare($query);
      
      // Bind Params name
      foreach($params as $param => $value) {
        $sth->bindValue(':' . $param, $value);
      }

      $sth->execute();
      return $sth;
    } catch (PDOException $e) {
      throw new Exception("Query failed to execute: {$e->getMessage()}");
    }
  }

  /**
     * Query the database with SHARE LOCK
     * 
     * @param string $query
     * @param array $params
     * 
     * @return PDOStatement
     * @throws Exception
     */
    public function queryWithShareLock($query, $params = []) {
      try {
          $this->conn->beginTransaction();

          // Set SHARE LOCK
          $query .= ' FOR SHARE';

          $sth = $this->query($query, $params);

          $this->conn->commit();

          return $sth;
      } catch (Exception $e) {
          $this->conn->rollBack();
          throw new Exception($e->getMessage());
      }
  }

  /**
   * Query the database with EXCLUSIVE LOCK
   * 
   * @param string $query
   * @param array $params
   * 
   * @return PDOStatement
   * @throws Exception
   */
  public function queryWithExclusiveLock($query, $params = []) {
      try {
          $this->conn->beginTransaction();

          // Set EXCLUSIVE LOCK
          $query .= ' FOR UPDATE';

          $sth = $this->query($query, $params);

          $this->conn->commit();

          return $sth;
      } catch (Exception $e) {
          $this->conn->rollBack();
          throw new Exception($e->getMessage());
      }
  }
}
?>