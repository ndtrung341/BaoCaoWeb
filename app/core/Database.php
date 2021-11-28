<?php
class Database
{
   public $conn;
   public $stmt;

   public function __construct()
   {
      try {
         $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
         );
         $this->conn = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS,
            $options
         );
      } catch (PDOException $e) {
         echo 'Connection Error:' . $e->getMessage();
         die();
      }
   }
   public function closeConnect()
   {
      $this->conn = null;
   }
}
