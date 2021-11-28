<?php
class AdminModel extends Database
{
   public function login($username, $password)
   {
      $sql = "SELECT id,username FROM admin
              WHERE username=? AND password=?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$username, $password]);
      return $stmt->fetch();
   }
}
