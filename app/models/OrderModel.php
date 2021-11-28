<?php
class OrderModel extends Database
{
   /**
    * Thêm hóa đơn
    */
   function add(
      $customer,
      $user_id,
      $phone,
      $email,
      $address,
      $payment,
      $details
   ) {
      $this->conn->beginTransaction();
      try {
         // Thêm thông tin hóa đơn
         $code = randomString(6);
         $sql = "INSERT INTO orders
               (code,customer,account,phone,email,address,payment,created_date) VALUES
               (?,?,?,?,?,?,?,now())";
         $stmt = $this->conn->prepare($sql);
         $stmt->execute([$code, $customer, $user_id, $phone, $email, $address, $payment]);
         // Thêm thông tin chi tiết mặt hàng của hóa đơn
         $sql = "INSERT INTO orders_detail
               (orders_code,product_code,price,quantity) VALUES
               (?,?,?,?)";
         // Lặp qua từng mặt hàng
         foreach ($details as $item) {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$code, $item['product_code'], $item['price'], $item['quantity']]);
         }
         $this->conn->commit();
      } catch (\Throwable $e) {
         // Hủy hết toàn bộ nếu có lỗi
         $this->conn->rollBack();
         Response::json(400, $e->getMessage());
      }
   }

   function getAllOrders()
   {
      $sql = "SELECT * FROM orders o
               INNER JOIN payment p
               ON p.id = o.payment
               ORDER BY created_date DESC";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll();
   }

   function getAllOrdersUser($user_id)
   {
      $sql = "SELECT * FROM orders WHERE account=? ORDER BY created_date DESC";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$user_id]);
      return $stmt->fetchAll();
   }

   function getOneOrdersUser($user_id, $order_id)
   {
      $sql = "SELECT * FROM orders WHERE account=? AND code=?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$user_id, $order_id]);
      return $stmt->fetch();
   }

   function getOrderDetail($order_id)
   {
      $sql = "SELECT o_d.*, p.image, p.name
              FROM orders_detail o_d
              INNER JOIN product p
              ON p.product_code = o_d.product_code
              WHERE orders_code = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$order_id]);
      return $stmt->fetchAll();
   }
}
