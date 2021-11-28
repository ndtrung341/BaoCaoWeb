<?php
class CartModel extends Database
{
   /**
    * Lấy thông tin giỏ hàng
    */
   public function getAll()
   {
      // Lấy giỏ hàng của tài khoản
      if (isset($_COOKIE['user_id'])) {
         $user_id = $_COOKIE['user_id'];
         $sql = "SELECT c.*,p.name,CONCAT('" . ROOT . "/public/',p.image) as image,p.price FROM cart c
                 INNER JOIN product p ON p.product_code = c.product_code
                 INNER JOIN user u ON u.id = c.user_id
                 WHERE c.user_id=?";
         $stmt = $this->conn->prepare($sql);
         $stmt->execute([$user_id]);

         return array_reduce($stmt->fetchAll(), function ($acc, $item) {
            $acc[$item['product_code']] = $item;
            return $acc;
         }, []);
      } else {
         // Lấy giỏ hàng trong session
         return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
      }
   }

   /**
    * Lấy thông tin 1 sản phẩm trong giỏ hàng
    * @param String $user_id mã tài khoản
    * @param String $product_code mã sản phẩm
    */
   // public function getOne($user_id, $product_code)
   // {
   //    try {
   //       $sql = "SELECT c.*,p.name FROM cart c
   //            INNER JOIN product p ON p.product_code = c.product_code
   //            INNER JOIN user u ON u.id = c.user_id
   //            WHERE c.user_id=? AND c.product_code=?";
   //       $stmt = $this->conn->prepare($sql);
   //       $stmt->execute([$user_id,  $product_code]);
   //       $cart = $stmt->fetch();
   //       return $cart;
   //    } catch (\Throwable $e) {
   //       Response::json(400, $e->getMessage());
   //    }
   // }
   public function getOne($product_code)
   {
      if (isset($_COOKIE['user_id'])) {
         try {
            $sql = "SELECT c.*,p.name,CONCAT('" . ROOT . "/public/',p.image) as image FROM cart c
                 INNER JOIN product p ON p.product_code = c.product_code
                 INNER JOIN user u ON u.id = c.user_id
                 WHERE c.user_id=? AND c.product_code=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$_COOKIE['user_id'],  $product_code]);
            $cart = $stmt->fetch();
            return $cart;
         } catch (\Throwable $e) {
            Response::json(400, $e->getMessage());
         }
      } else {
         return $_SESSION['cart'][$product_code];
      }
   }
   /**
    * thêm vào giỏ hàng
    * @param String $user_id mã tài khoản
    * @param String $product_code mã sản phẩm
    * @param String $qty số lượng cần cập nhật
    */
   public function insert($user_id, $product_code, $qty = 1)
   {
      try {
         $sql = "INSERT INTO cart VALUES (null,?,?,?)";
         $stmt = $this->conn->prepare($sql);
         return $stmt->execute([$user_id, $product_code, $qty]);
      } catch (\Throwable $e) {
         Response::json(400, $e->getMessage());
      }
   }

   /**
    * cập nhật giỏ hàng
    * @param String $user_id mã tài khoản
    * @param String $product_code mã sản phẩm
    * @param String $qty số lượng cần cập nhật
    * @param Boolean $isAdd cho phép cộng dồn với số lượng đã có hay không
    */
   public function update($user_id, $product_code, $qty, $isAdd = true)
   {
      try {
         $sql = "UPDATE cart SET quantity=" . ($isAdd ? "quantity+" : "") . ":qty
              WHERE user_id=:user_id AND product_code=:product_code";
         $stmt = $this->conn->prepare($sql);
         return $stmt->execute([":qty" => $qty, "user_id" => $user_id, "product_code" => $product_code]);
      } catch (\Throwable $e) {
         Response::json(400, $e->getMessage());
      }
   }

   /**
    * Xóa sản phẩm giỏ hàng
    * @param String $user_id mã tài khoản
    * @param String $product_code mã sản phẩm
    */
   public function delete($user_id, $product_code)
   {
      try {
         $sql = "DELETE FROM cart WHERE user_id=:user_id AND product_code=:product_code";
         return $this->conn->prepare($sql)->execute(["user_id" => $user_id, "product_code" => $product_code]);
      } catch (\Throwable $e) {
         Response::json(400, $e->getMessage());
      }
   }

   /**
    * Làm trống giỏ hàng
    * @param String $user_id mã tài khoản
    */
   public function clear($user_id)
   {
      try {
         $sql = "DELETE FROM cart WHERE user_id=?";
         return $this->conn->prepare($sql)->execute([$user_id]);
      } catch (\Throwable $e) {
         Response::json(400, $e->getMessage());
      }
   }

   /**
    * Gộp giỏ hàng trong Session với tài khoản nếu có đăng nhập
    * @param String $user_id mã tài khoản
    */
   public function mergeCart($user_id)
   {
      if (!$_SESSION['cart']) return; // Nếu giỏ hàng session trống thì kết thúc
      // $user_id = $_COOKIE['user_id'];
      foreach ($_SESSION['cart'] as $item) {
         // Lặp qua từng sản phẩm
         $product_code = $item['product_code'];
         $qty = $item['quantity'];
         if ($this->getOne($product_code)) { // Nếu đã tồn sản phẩm trong giỏ hàng tài khoản
            $this->update($user_id, $product_code, $qty, false); // Cập nhật lại số lượng
         } else {
            $this->insert($user_id, $product_code, $qty); // Thêm mới
         }
      }
      // Cuối cùng làm trống giỏ hàng trong session
      unset($_SESSION['cart']);
   }
}
