<?php
   class ThemeModel extends Database{
      function getAll(){
         $sql = "SELECT * FROM theme";
         $stmt = $this->conn->prepare($sql);
         $stmt->execute();
         return $stmt->fetchAll();
      }
   }
