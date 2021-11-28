<?php
class CartController extends Controller
{
   function __construct()
   {
      $this->productModel = $this->loadModel("ProductModel");
      $this->cartModel = $this->loadModel("CartModel");
      $this->orderModel = $this->loadModel("OrderModel");
      $this->userModel = $this->loadModel("UserModel");
      $this->user_id = $_COOKIE['user_id'] ?? null;
   }

   private function RenderCart()
   {
      ob_start();
      $this->loadView("/templates/cart", [
         'cart' =>  $this->cartModel->getAll()
      ]);
      $html = ob_get_clean();
      return preg_replace('/^.*|.*$/', '', $html);
   }

   /**
    * Hiện thị chi tiết giỏ hàng
    */
   function index()
   {
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         // Phản hồi yêu cầu
         $cart = $this->cartModel->getAll();
         Response::json(200, "success", $cart);
      } else { // Tải giao diện
         $user = isset($_COOKIE['user_id']) ? $this->userModel->getOne($_COOKIE['user_id']) : null;
         $this->loadView("master", [
            "title" => "My Cart",
            "page" => "cart_detail",
            "cart" => $this->cartModel->getAll(),
            "product" => $this->productModel->getAllProduct(),
            "user" => $user
         ]);
      }
   }

   /**
    * THÊM VÀO GIỎ HÀNG
    */
   function add()
   {
      if (isset($_POST['action']) && $_POST['action'] == "add") {
         // Lấy dữ liệu
         $product_code = $_POST['product_code'];
         $qty = $_POST['quantity'] ?? 1;
         // Kiểm tra sản phẩm
         $product = $this->productModel->getOneProduct($product_code);
         if (!$product) Response::json(400, "Sản phẩm không tồn tại");
         // Thực hiện thêm
         if ($this->user_id) {
            // Thêm vào tải khoản người dùng
            if (!$this->cartModel->getOne($product_code)) {
               // Chưa có thì thêm mới
               $result = $this->cartModel->insert($this->user_id, $product_code, $qty);
            } else {
               // Đã có thì cập nhật, cộng dồn số lượng
               $result = $this->cartModel->update($this->user_id, $product_code, $qty);
            }
            if (!$result) Response::json(400, "Thêm thất bại");
            $this::clearSessionCart();
         } else {
            // Thêm vào session
            if (isset($_SESSION['cart'][$product_code])) { // Cập nhật số lượng nếu đã có
               $_SESSION['cart'][$product_code]['quantity'] += $qty;
            } else { // Thêm mới
               $_SESSION['cart'][$product_code] = array(
                  "product_code" => $product['product_code'],
                  "name" => $product['name'],
                  "image" => $product['image'],
                  "price" => $product['price'],
                  "quantity" => $qty
               );
            }
         }
         Response::json(200, "Đã thêm vào giỏ hàng", ["html" => $this->RenderCart(), "product" => $product, "qtyInCart" => $this->cartModel->getOne($product_code)['quantity']]);
      }
   }

   /**
    * CAP NHAT GIO HANG
    */
   function update()
   {
      if (isset($_POST['action']) && $_POST['action'] == "update") {
         $product_code = $_POST['product_code'];
         $qty = $_POST['quantity'];
         if ($this->user_id) {
            $this->cartModel->update($this->user_id, $product_code, $qty, false);
         } else {
            $_SESSION['cart'][$product_code]['quantity'] = $qty;
         }
         Response::json(200, "Đã cập nhật giỏ hàng", $this->cartModel->getAll());
      }
   }

   /**
    * LÀM TRỐNG GIỎ HÀNG
    */
   function clear()
   {
      if ($_POST['action'] && $_POST['action'] == 'clear') {
         if ($this->user_id) {
            // Làm trống trong tài khoản
            $this->cartModel->clear($this->user_id);
         } else {
            // Làm trống session
            $this::clearSessionCart();
         }
         Response::json(200, "Đã làm trống giỏ hàng");
      }
   }

   /**
    * =====================================
    * XÓA SẢN PHẨM RA KHỎI GIỎ HÀNG
    * @param String product_code Mã sản phẩm
    * =====================================
    */
   function delete($product_code)
   {
      if ($_POST['action'] && $_POST['action'] == 'delete') {
         if ($this->user_id) {
            // Xóa sản phẩm trong tài khoản
            $this->cartModel->delete($this->user_id, $product_code);
         } else {
            // Xóa sản phẩm trong Session
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($k) use ($product_code) {
               return $k != $product_code;
            }, ARRAY_FILTER_USE_KEY);
         }
         Response::json(200, "Xoa thanh cong", $this->cartModel->getAll());
      }
   }

   function buyAgain($order_code)
   {
      $order_detail = $this->orderModel->getOrderDetail($order_code);
      foreach ($order_detail as $item) {
         $product = $this->productModel->getOneProduct($item['product_code']);
         if ($product['quantity'] > 0) {
            if (!$this->cartModel->getOne($item['product_code']))
               $this->cartModel->insert($this->user_id, $item['product_code'], 1);
            else
               $this->cartModel->update($this->user_id, $item['product_code'], 1);
         }
      }
      $this->index();
   }

   public static function clearSessionCart()
   {
      unset($_SESSION['cart']);
   }
}
