<?php
class ProductModel extends Database
{

   public function getAllProduct()
   {
      $sql = "SELECT p.*,CONCAT('" . ROOT . "/public/',p.image) as image,t.theme
              FROM product p
              INNER JOIN theme t
              ON p.theme_id = t.id
              ORDER BY created_date DESC";
      $result = $this->conn->query($sql)->fetchAll();
      return $result;
   }

   public function getOneProduct($product_code)
   {
      try {
         $sql = "SELECT p.*,CONCAT('" . ROOT . "/public/',p.image) as image,t.theme
              FROM product p
              INNER JOIN theme t
              ON p.theme_id = t.id
              WHERE product_code=?";
         $stmt = $this->conn->prepare($sql);
         $stmt->execute([$product_code]);
         return $stmt->fetch();
      } catch (\Throwable $e) {
         Response::json(400, $e->getMessage());
      }
   }

   /**
    * Lấy danh sách hình ảnh của sản phẩm
    * @param String $product_code mã sản phẩm
    */
   public function getImagesProduct($product_code)
   {
      $sql = "SELECT CONCAT('" . ROOT . "/public/',image) as image FROM product_image WHERE product_code = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$product_code]);
      return $stmt->fetchAll(PDO::FETCH_COLUMN);
   }

   /**
    * Lấy danh sách sản phẩm mới nhất
    * @param number $limit giới hạn số sản phẩm được lấy
    */
   public function getNewProduct($limit = 8)
   {
      // echo ROOT;
      $sql = "SELECT p.*,CONCAT('" . ROOT . "/public/',p.image) as image FROM product p ORDER BY created_date DESC LIMIT :limit";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([":limit" => $limit]);
      $result = $stmt->fetchAll();
      return $result;
   }

   /**
    * Lấy danh sách sản phẩm ngẫu nhiên
    * @param number $limit giới hạn số sản phẩm được lấy
    */
   public function getRandomProduct($limit = 10)
   {
      $sql = "SELECT p.*,CONCAT('" . ROOT . "/public/',p.image) as image FROM product p ORDER BY RAND() LIMIT :limit";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([":limit" => $limit]);
      $result = $stmt->fetchAll();
      return $result;
   }

   function changeStatus($status, $product_code)
   {
      $sql = "UPDATE product SET status=? WHERE product_code=?";
      return
         $this->conn->prepare($sql)->execute([$status, $product_code]);
   }

   function paginator($page, $limit, $whereClause, $whereArgs)
   {
      $sql = "SELECT * FROM product " . $whereClause;
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($whereArgs);
      $totalItems = $stmt->fetchAll();
      $totalPages = ceil(count($totalItems) / $limit);

      $start = ($page - 1) * $limit;
      $sql = "SELECT p.*,CONCAT('" . ROOT . "/public/',p.image) as image,t.theme
              FROM product p
              INNER JOIN theme t
              ON p.theme_id = t.id " . $whereClause . " LIMIT $start,$limit";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($whereArgs);
      $products = $stmt->fetchAll();

      return [
         "currentPage" => $page,
         "content" => $products,
         "total" => $totalItems,
         "totalPages" => $totalPages,
         "totalItems" => count($totalItems),
         "start" => $start + 1,
         "end" => $start  + count($products)
      ];
   }

   function add($product_code, $theme, $name, $description, $price, $quantity, $images_path)
   {
      $this->conn->beginTransaction();
      try {
         # LƯU THÔNG TIN
         $sql = "INSERT INTO product (product_code,theme_id,name,description,price,quantity,image) VALUES (?,?,?,?,?,?,?)";
         $stmt = $this->conn->prepare($sql);
         $stmt->execute([
            $product_code, $theme, $name, $description, $price, $quantity, $images_path[0]
         ]);
         # LƯU HÌNH ẢNH
         foreach ($images_path as $image_path) {
            $sql = "INSERT INTO product_image (product_code,image) VALUES (?,?)";
            $stmt = $this->conn->prepare($sql)->execute([$product_code, $image_path]);
         }
         # LƯU NHỮNG THAY ĐỔI
         $this->conn->commit();
      } catch (\Throwable $e) {
         // Hủy hết toàn bộ nếu có lỗi
         $this->conn->rollBack();
         // Xóa thư mục hình
         deleteFolderImage($product_code);
         Response::json(400, $e->getMessage());
      }
   }

   function delete($product_code)
   {
      $sql = "DELETE FROM product WHERE product_code=?";
      try {
         $this->conn->prepare($sql)->execute([$product_code]);
      } catch (\Throwable $e) {
         Response::json(400, $e->getMessage());
      }
   }

   function update($product_code, $theme, $name, $description, $price, $quantity, $images_path)
   {
      $this->conn->beginTransaction();
      try {
         # CẬP NHẬT THÔNG TIN
         $sql = "UPDATE product SET theme_id=?,name=?,description=?,price=?,quantity=?,image=?,modified_date=now() WHERE product_code=?";
         $stmt = $this->conn->prepare($sql);
         $stmt->execute([
            $theme, $name, $description, $price, $quantity, $images_path[0], $product_code
         ]);
         # XÓA ĐƯỞNG DẪN CŨ TRONG CSDL
         $sql = "DELETE FROM product_image WHERE product_code=?";
         $this->conn->prepare($sql)->execute([$product_code]);
         # CẬP NHẬT ĐƯỜNG DẪN HÌNH ẢNH MỚI
         foreach ($images_path as $image_path) {
            $sql = "INSERT INTO product_image (product_code,image) VALUES (?,?)";
            $stmt = $this->conn->prepare($sql)->execute([$product_code, $image_path]);
         }
         # LƯU NHỮNG THAY ĐỔI
         $this->conn->commit();
      } catch (\Throwable $e) {
         // Hủy hết toàn bộ nếu có lỗi
         $this->conn->rollBack();
         // Xóa thư mục hình
         deleteFolderImage($product_code);
         Response::json(400, $e->getMessage());
      }
   }

   function search($keyword)
   {
      $sql = "SELECT * FROM product WHERE CONCAT(product_code,name) LIKE ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(['%' . $keyword . '%']);
      return $stmt->fetchAll();
   }
}
