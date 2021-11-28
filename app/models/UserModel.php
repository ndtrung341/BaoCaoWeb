<?php
class UserModel extends Database
{

   public function login($username, $password)
   {
      // $sql = "SELECT id,username FROM user
      //         WHERE username=? AND password=?";
      // $stmt = $this->conn->prepare($sql);
      // $stmt->execute([$username, $password]);
      // return $stmt->fetch();
      $sql = "SELECT id,username,password FROM user
              WHERE username=?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$username]);
      $user = $stmt->fetch();
      if ($user && password_verify($password, $user['password'])) {
         return $user;
      } else {
         return false;
      }
   }

   public function getOne($user_id)
   {
      $sql = "SELECT * FROM user
              WHERE id=?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$user_id]);
      return $stmt->fetch();
   }

   function unique($column, $value)
   {
      $sql = "SELECT COUNT(*) FROM user WHERE $column=?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$value]);
      return $stmt->fetchColumn();
   }

   function register($username, $email, $password)
   {
      $sql = "INSERT INTO user  VALUES (NULL,?,?,?,now(),now())";
      $stmt = $this->conn->prepare($sql);
      return  $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
   }

   function createToken($token, $email)
   {
      $sql = "INSERT INTO reset_password VALUES (?,?,now()+900)";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([$token, $email]);
   }

   function deleteToken($email)
   {
      $sql = "DELETE FROM reset_password WHERE email=?";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([$email]);
   }

   function getToken($token)
   {
      $sql = "SELECT * FROM reset_password WHERE token=?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$token]);
      return $stmt->fetch();
   }

   function changePassword($email, $password)
   {
      $sql = "UPDATE user SET password=?,modified_date=now() WHERE email=?";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([$password, $email]);
   }
}
