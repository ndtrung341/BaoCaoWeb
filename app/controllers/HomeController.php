<?php
class HomeController extends Controller
{
   function __construct()
   {
      $this->productModel = $this->loadModel("ProductModel");
      $this->cartModel = $this->loadModel("CartModel");
      $this->userModel = $this->loadModel("UserModel");
   }

   function index()
   {
      // Lấy dữ liệu
      $data["page"] = "home";
      $data["new_products"] = $this->productModel->getNewProduct(); // Lấy sản phẩm mới
      $data["trend_products"] = $this->productModel->getRandomProduct(); // Lấy sản phẩm ngẫu nhiên
      // $data['user'] = isset($_COOKIE['user_id']) ? $this->userModel->getOne($_COOKIE['user_id']) : null;
      $data['cart'] = $this->cartModel->getAll(); // lấy giỏ hàng
      $this->loadView("master", $data);
   }
}
