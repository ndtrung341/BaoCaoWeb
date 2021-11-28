<?php
class CheckoutController extends Controller
{
   function __construct()
   {
      $this->productModel = $this->loadModel("ProductModel");
      $this->cartModel = $this->loadModel("CartModel");
      $this->orderModel = $this->loadModel("OrderModel");
      $this->userModel = $this->loadModel("UserModel");
      $this->user_id = $_COOKIE['user_id'] ?? null;
   }
   /**
    * Tải trang đặt hàng
    */
   function index()
   {
      $user = isset($_COOKIE['user_id']) ? $this->userModel->getOne($_COOKIE['user_id']) : null;
      $this->loadView('master', [
         "title" => "Checkout",
         "page" => "checkout",
         "user" => $user,
         "cart" => $this->cartModel->getAll()
      ]);
   }
   function buyAgain($order_id)
   {
      $order_detail = $this->orderModel->getOrderDetail($order_id);
      $order = $this->orderModel->getOneOrdersUser($_COOKIE['user_id'], $order_id);
      $this->loadView('master', [
         "title" => "Checkout",
         "page" => "checkout",
         "order" => $order,
         "order_detail" => $order_detail,
         "cart" => $this->cartModel->getAll()
      ]);
   }
   /**
    * Thực hiện chức năng đặt hàng
    */
   function order()
   {
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         // Lấy thông tin
         $customer = $_POST['customer'];
         $user_id = $_COOKIE['user_id'];
         $phone = $_POST['phone'];
         $email = $_POST['email'];
         $address = $_POST['address'];
         $payment = intval($_POST['payment']);
         $details = $this->cartModel->getAll();
         // Thêm vào hóa đơn
         $this->orderModel->add(
            $customer,
            $user_id,
            $phone,
            $email,
            $address,
            $payment,
            $details
         );
         // Làm trống giỏ hàng
         $this->cartModel->clear($user_id);
         Response::json(200, "Đặt hàng thành công");
      }
   }
}
