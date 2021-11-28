<?php
class UserController extends Controller
{
   function __construct()
   {
      $this->cartModel = $this->loadModel("CartModel");
      $this->themeModel = $this->loadModel("ThemeModel");
      $this->userModel = $this->loadModel('UserModel');
   }

   function login()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         // Xử lý đăng nhập
         $username = $_POST['username'];
         $password = $_POST['password'];
         $user = $this->userModel->login($username, $password);
         if ($user) {
            if (isset($_POST['remember'])) { //Xử lý ghi nhớ tài khoản
               setcookie("remember_username", $username, time() + (86400 * 30 * 12), '/');
               setcookie("remember_password", $password, time() + (86400 * 30 * 12), '/');
               setcookie("remember", 1, time() + (86400 * 30 * 12), '/');
            } else {
               unset($_COOKIE);
               setcookie("remember_username", "", time() - (86400 * 30 * 12), '/');
               setcookie("remember_password", "", time() - (86400 * 30 * 12), '/');
               setcookie("remember", "", time() - (86400 * 30 * 12), '/');
            }
            // Cho phép đăng nhập trong 1h
            setcookie("user_id", $user['id'], time() + 3600, '/');
            setcookie("username", $user['username'], time() + 3600, '/');
            $_COOKIE['user_id'] = $user['id'];
            // Thực hiện gộp danh sách giỏ hàng
            // Xem hàm mergeCart trong CartModel dê biết thêm chi tiết
            $cartModel = $this->loadModel('CartModel');
            $cartModel->mergeCart($user['id']);
            header("location: " . ROOT);
         } else { // Đăng nhập thất bại
            $this->loadView('login', [
               "error" => "Sai tên đăng nhập hoặc mật khẩu"
            ]);
         }
      } else {
         // Tải trang đăng nhập
         $this->loadView('login');
      }
   }

   function register()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         // Xử lý đăng ki
         try {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $this->userModel->register($username, $email, $password);
            // Gửi thư
            include __DIR__ . "/../core/Email.php";
            $mail = new Email('ndtrung341@gmail.com', 'Trung_123');
            $mail->setContent(['subject' => "Đăng kí thành công", "body" => 'Tạo tài khoản thành công']);
            $mail->sendMail($email);

            Response::json(200, 1);
         } catch (\Throwable $e) {
            Response::json(400, $e->getMessage());
         }
      } else {
         // Tải trang đăng kí
         $this->loadView('register');
      }
   }

   function check()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $column = $_POST['column'];
         $value = $_POST['value'];
         echo $this->userModel->unique($column, $value);
      }
   }

   function logout()
   {
      unset($_COOKIE['user_id']);
      unset($_COOKIE['username']);
      setcookie('user_id', '', time() - 3600, '/');
      setcookie('username', '', time() - 3600, '/');
      header('location: ' . ROOT);
   }

   function purchase()
   {
      $ordersModel = $this->loadModel('OrderModel');
      $orders = $ordersModel->getAllOrdersUser($_COOKIE['user_id']);
      $orders_detail = array();
      foreach ($orders as $order) {
         $orders_detail[] = $ordersModel->getOrderDetail($order['code']);
      }
      $this->loadView('master', [
         "page" => "purchase",
         "orders" => $orders,
         "orders_detail" => $orders_detail,
         "cart" => $this->cartModel->getAll(),
         "themes" => $this->themeModel->getAll(),
      ]);
   }

   function forgot()
   {
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         // Lấy email cần reset pass
         $email = $_POST['email'];
         $status = 1;
         $msg = "";
         // Kiểm tra email đã dk chưa
         $check = $this->userModel->unique('email', $email);
         if ($check) {
            // Xóa hết token có chứa email này,nếu người dùng gửi yêu cầu nhiều lần
            $this->userModel->deleteToken($email);
            // Tạo token ngẫu nhiên
            $token = bin2hex(random_bytes(4));
            // Chèn token và email vào CSDL
            $this->userModel->createToken($token, $email);
            // Tạo mail
            include __DIR__ . "/../core/Email.php";
            $mail = new Email('ndtrung341@gmail.com', 'Trung_123');
            // Tiêu đề và nội dung mail
            $subject = "Yêu cầu đặt lại mật khẩu";
            $body    = '<div style="background-color: #f5f5f5;display: flex;padding: 20px 0;">
                           <div style="background: #fff; padding: 20px; box-sizing: border-box;margin:auto;">
                              <h1 style="text-align: center;font-size: 25px;margin: 0;padding: 0;">Xin chào</h1>
                              <p style="font-size: 20px">Nhấn vào nút Đặt lại mật khẩu dưới đây để đặt lại mật khẩu mới</p>
                              <a href="' . ROOT . '/user/resetPassword?token=' . $token . '" target="_blank" style="background: #00aa9d;border-radius: 3px;color: #fefefe;display: inline-block;font-size: 20px;font-weight: 700;line-height: 1.3;margin: 0;padding: 10px 20px 10px 20px;box-sizing: border-box;text-align: center;text-decoration: none;width: 100%;">Đặt lại mật khẩu</a>
                           </div>
                        </div>';
            $mail->setContent(['subject' => $subject, "body" => $body]);
            // Gửi mail
            if (!$mail->sendMail($email)) {
               $status = 0;
               $msg    = "Có lỗi xảy ra";
            } else
               $msg = 'Đã gửi yêu cầu khôi phục mật khẩu. Vui lòng kiểm tra hộp thư của bạn';
         } else {
            $status = 0;
            $msg    = 'Email không tồn tại';
         }
         Response::json($status, $msg);
      } else
         $this->loadView('forgot');
   }

   function resetPassword()
   {
      // Lấy token và email cần reset pass
      $token = $_GET['token'];
      $info = $this->userModel->getToken($token);
      date_default_timezone_set('Asia/Ho_Chi_Minh');
      if ($info && time() > strtotime($info['expired'])) {
         $this->userModel->deleteToken($info['email']);
         $info = false;
      }
      if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reset'])) {
         // Thay đổi pass
         $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
         $result = $this->userModel->changePassword($info['email'], $password);
         // Kết quả lưu vào session để chuyển qua trang login
         $_SESSION['reset_status'] = $result;
         $_SESSION['reset_msg'] =
            $result ? "Cập nhật mật khẩu thành công" : "Có lỗi xảy ra";
         if ($result) {
            // Gửi thư
            include __DIR__ . "/../core/Email.php";
            $mail = new Email('ndtrung341@gmail.com', 'Trung_123');
            $mail->setContent(['subject' => "Cập nhật mật khẩu", "body" => 'Mật khẩu của bạn đã được cập nhật']);
            $mail->sendMail($info['email']);
         }
         // Xóa token
         $this->userModel->deleteToken($info['email']);
         // Chuyển trang
         header('Location: ' . ROOT . '/user/login');
      }
      $this->loadView('resetPassword', [
         'info' => $info
      ]);
   }
}
