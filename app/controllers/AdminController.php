<?php
class AdminController extends Controller
{
   function __construct()
   {
      $this->productModel = $this->loadModel('ProductModel');
      $this->orderModel = $this->loadModel('OrderModel');
      $this->themeModel = $this->loadModel("ThemeModel");
      $this->adminModel = $this->loadModel('AdminModel');
   }

   function index()
   {
      $this->loadView('admin', [
         'title' => 'Dashboard',
         'page' => 'admin_home'
      ]);
   }

   function login()
   {
      $error = false;
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         $username = $_POST['username'];
         $password = $_POST['password'];
         $check = $this->adminModel->login($username, $password);
         if (!$check) $error = "Sai tên đăng nhập hoặc mật khẩu";
         else {
            $_SESSION['admin'] = $check;
            header('location: ' . ROOT . '/admin/');
         }
      }
      $this->loadView('admin_login', [
         'error' => $error,
      ]);
   }

   function logout()
   {
      unset($_SESSION['admin']);
      header('location: ' . ROOT . '/admin/login');
   }

   function products()
   {
      include __DIR__ . "/../core/Pagination.php";

      $result = $this->paginate();
      $paginator = new Pagination($result['currentPage'], $result['totalPages']);
      $this->loadView('admin', [
         'title' => "Products",
         'page' => 'admin_products',
         'products' => $result['content'],
         'paginator' => $paginator->renderBootstrap(ROOT . '/admin/products/')
      ]);
   }

   private function paginate()
   {
      $page = $_GET['page'] ?? 1;
      $limit = $_GET['limit'] ?? 6;
      $sortClause = "ORDER BY created_date DESC";
      return $this->productModel->paginator($page, $limit, $sortClause, []);
   }

   function newProduct()
   {
      $themes = $this->themeModel->getAll();
      $this->loadView('admin', [
         'title' => "New Product",
         'page' => 'admin_product',
         'themes' => $themes,
         'action' => 'add'
      ]);
   }

   function editProduct($product_code)
   {
      $themes = $this->themeModel->getAll();
      $product = $this->productModel->getOneProduct($product_code);
      $product_images = $this->productModel->getImagesProduct($product_code);
      $this->loadView('admin', [
         'title' => $product['name'],
         'page' => 'admin_product',
         'themes' => $themes,
         'product' => $product,
         'product_images' => $product_images,
         'action' => 'edit'
      ]);
   }

   function searchProduct($keyword)
   {
      $products = $this->productModel->search($keyword);
      Response::json(200, '', $products);
   }

   function findProduct()
   {
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         $product_code = $_POST['product_code'];
         $product = $this->productModel->getOneProduct($product_code);
         Response::json(200, '', $product);
      }
   }

   function orders()
   {
      $orders = $this->orderModel->getAllOrders();
      $this->loadView('admin', [
         'title' => 'Orders',
         'page' => 'admin_orders',
         'orders' => $orders
      ]);
   }
}
