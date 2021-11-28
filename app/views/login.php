<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="icon" href="<?= ROOT ?>/public/img/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/reset.css">
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/login.css">
</head>

<body>

   <div class="container login-process">
      <div class="heading">
         <div class="title">Login</div>
      </div>
      <!-- THÔNG BÁO VỀ VIỆC THAY MẬT KHẨU -->
      <?php if (isset($_SESSION['reset_status'])) : ?>
         <p class="<?= $_SESSION['reset_status'] ? "success" : "error" ?>"><?= $_SESSION['reset_msg'] ?></p>
         <?php unset($_SESSION['reset_status']) ?>
         <?php unset($_SESSION['reset_msg']) ?>
      <?php endif; ?>
      <!-- THÔNG BÁO NẾU ĐĂNG NHẬP SAI-->
      <?php if (isset($data['error'])) : ?>
         <p class="error"><?= $data['error'] ?></p>
         <?php unset($data['error']) ?>
      <?php endif; ?>

      <form class="form form-login" id="login" action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
         <div class="form-group">
            <input type="text" autocomplete="off" name="username" id="username" class="form-control" placeholder="Tên đăng nhập" value="<?= $_COOKIE['remember_username'] ?? "" ?>">
            <p class="form-message"></p>
         </div>
         <div class="form-group">
            <input type="password" autocomplete="off" name="password" id="password" class="form-control" placeholder="Mật khẩu" value="<?= $_COOKIE['remember_password'] ?? "" ?>">
            <span class="password-show"><i class="fas fa-eye"></i></span>
            <p class="form-message"></p>
         </div>
         <div class="form-group form-remember">
            <input type="checkbox" id="remember" name="remember" <?= isset($_COOKIE['remember']) ? "checked" : "" ?>>
            <label for="remember" style="margin-left: .5rem;">Ghi nhớ đăng nhập</label>
            <!-- <a href="<?= ROOT ?>/user/forgot/" class="forgot">Quên mật khẩu</a> -->
         </div>
         <input type="submit" name="login" form="login" class="btn btn-login" value="Đăng nhập">
      </form>
      <p class="notice">Chưa có tài khoản? <a href="<?= ROOT ?>/user/register">Đăng kí</a></p>
      <a href="<?= ROOT ?>/user/forgot/" class="forgot">Quên mật khẩu?</a>
   </div>
   <script type="module" src="<?= ROOT ?>/public/js/Login.js"></script>
</body>

</html>