<?php
class ProductController extends Controller
{
   public function __construct()
   {
      $this->productModel = $this->loadModel("ProductModel");
      $this->userModel = $this->loadModel("UserModel");
      $this->cartModel = $this->loadModel("CartModel");
      $this->themeModel = $this->loadModel("ThemeModel");
      $this->user = isset($_COOKIE['user_id']) ? $this->userModel->getOne($_COOKIE['user_id']) : null;
   }

   function index()
   {
      include __DIR__ . "/../core/Pagination.php";

      $result = $this->paginate();
      // if ($result['totalItems'] == 0 && isset($_GET['q'])) {
      //    echo "Khong co san pham";
      //    die();
      // }
      $pagination = new Pagination($result['currentPage'], $result['totalPages']);
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         ob_start();
         $this->loadView("/pages/products", [
            'links' => $pagination->renderPageLink("/mvc/product"),
            "paginate" => $result,
         ]);
         $html = ob_get_clean();
         echo json_encode(['html' => $html, 'paginate' => $result]);
      } else {
         $this->loadView("master", [
            'page' => "products",
            "user" => $this->user,
            'links' => $pagination->renderPageLink("/mvc/product"),
            "cart" => $this->cartModel->getAll(),
            "paginate" => $result,
            "themes" => $this->themeModel->getAll(),
         ]);
      }
   }

   private function paginate()
   {
      $page = $_GET['page'] ?? 1;
      $whereClause = $whereArgs = array();

      if (isset($_GET['q'])) {
         $whereClause[] = "name LIKE ?";
         $whereArgs[] = '%' . $_GET['q'] . '%';
      }

      if (isset($_GET['theme'])) {
         $params = explode(',', $_GET['theme']);
         $place_holders = implode(',', array_fill(0, count($params), '?'));
         $whereClause[] = "theme_id IN (" . $place_holders . ")";
         $whereArgs = [...$whereArgs, ...$params];
      }

      if (isset($_GET['minPrice']) && isset($_GET['maxPrice'])) {
         $whereClause[] = "price BETWEEN ? AND ?";
         $whereArgs[] = $_GET['minPrice'];
         $whereArgs[] = $_GET['maxPrice'];
      }
      $whereClause = count($whereClause) > 0 ? "WHERE " . join(" AND ", $whereClause) : "";

      $sortClause = "ORDER BY created_date DESC";
      if (isset($_GET['sort_key']) && isset($_GET['sort_order'])) {
         $keys = ['price'];
         $orders = ['asc', 'desc'];

         if (!in_array($_GET['sort_key'], $keys) || !in_array($_GET['sort_order'], $orders)) return;
         $sortClause = "ORDER BY " . $_GET['sort_key'] . " " . $_GET['sort_order'];
      }
      $whereClause .= " " . $sortClause;
      return $this->productModel->paginator($page, 9, $whereClause, $whereArgs);
      // return $pagination->paginator($this->productModel, $whereClause, $whereArgs);
   }

   /**
    * Hiễn thị chi tiết sản phẩm
    * @param String $param Tên sản phẩm + mã (Ví dụ: san-pham-123)
    */
   public function detail($param)
   {
      // Tách chuỗi để lấy mã sản phẩm
      $strs = explode("-", $param);
      $product_code = array_pop($strs);
      // Lấy thông tin tài khoản
      $user = isset($_COOKIE['user_id']) ? $this->userModel->getOne($_COOKIE['user_id']) : null;
      // Lấy thông tin sản phẩm
      $product = $this->productModel->getOneProduct($product_code);
      // Lấy hình ảnh của sản phẩm
      $product_images = $this->productModel->getImagesProduct($product_code);
      $this->loadView('master', [
         "title" => $product['name'],
         "page" => "product",
         "product" => $product,
         "images" => $product_images,
         "user" => $user,
         "cart" => $this->cartModel->getAll()
      ]);
   }


   /**
    * Lấy thông tin sản phẩm
    */
   public function getOne()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $product_code = $_POST['product_code'];
         $product = $this->productModel->getOneProduct($product_code);
         Response::json(200, 'success', $product);
      }
   }

   /**
    * Lấy thông tin toàn bộ sản phẩm
    */
   public function getAll()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         // echo 123;
         $products = $this->productModel->getAllProduct();
         Response::json(200, 'success', $products);
      }
   }

   function changeStatus()
   {
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         $status = $_POST['status'];
         $product_code = $_POST['product_code'];
         $result = $this->productModel->changeStatus($status, $product_code);
         if ($result) Response::json(200, "Update status successfully");
         else Response::json(400, "Something went wrong");
      }
   }

   function add()
   {
      $product_code = $_POST['product_code'];
      $theme = $_POST['theme'];
      $name = $_POST['name'];
      $description = $_POST['description'];
      $price = $_POST['price'];
      $quantity = $_POST['quantity'];
      // Upload hình
      $images_path = uploadImages($_FILES['images'], $product_code);
      // Lưu vào CSDL
      $this->productModel->add($product_code, $theme, $name, $description, $price, $quantity, $images_path);
      Response::json(200, 'Thêm thành công');
   }
   function delete()
   {
      $product_code = $_POST['product_code'];
      $this->productModel->delete($product_code);
      deleteFolderImage($product_code);
      Response::json(200, 'Xóa thành công');
   }
   function edit()
   {
      $product_code = $_POST['product_code'];
      $theme = $_POST['theme'];
      $name = $_POST['name'];
      $description = $_POST['description'];
      $price = $_POST['price'];
      $quantity = $_POST['quantity'];
      deleteFolderImage($product_code);
      // Upload hình
      $images_path = uploadImages($_FILES['images'], $product_code);
      // cập nhật vào CSDL
      $this->productModel->update($product_code, $theme, $name, $description, $price, $quantity, $images_path);
      Response::json(200, 'Cập nhật thành công');
   }
}
