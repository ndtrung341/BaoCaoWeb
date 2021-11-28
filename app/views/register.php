<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="icon" href="<?= ROOT ?>/public/img/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/reset.css">
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/login.css">
   <style>
      label {
         display: inline-block;
         font-size: 1.6rem;
         font-weight: 600;
         margin-bottom: 1rem;
      }

      body {
         background: #41b3a3;
      }

      .btn-register {
         background: #41b3a3;
         font-size: 1.7rem;
      }
   </style>
</head>

<body>

   <div class="container login-process">
      <div class="heading">
         <div class="title" style="color:#41b3a3;">Register</div>
      </div>
      <form class="form form-login" id="register">
         <div class="form-group">
            <label>Tên đăng nhập</label>
            <input type="text" autocomplete="off" name="username" id="username" class="form-control" placeholder="Tên đăng nhập">
            <p class="form-message"></p>
         </div>
         <div class="form-group">
            <label>Email</label>
            <input type="text" autocomplete="off" name="email" id="email" class="form-control" placeholder="Email">
            <p class="form-message"></p>
         </div>
         <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" autocomplete="off" name="password" id="password" class="form-control" placeholder="Mật khẩu">
            <p class="form-message"></p>
         </div>
         <div class="form-group">
            <label>Nhập lại mật khẩu</label>
            <input type="password" autocomplete="off" name="password-confirm" id="password-confirm" class="form-control" placeholder="Nhập lại mật khẩu">
            <p class="form-message"></p>
         </div>
         <input type="submit" name="register" form="register" class="btn btn-register" value="Đăng kí">
      </form>
      <p class="notice">Đã có tài khoản? <a href="<?= ROOT ?>/user/login" style="color:#41b3a3;">Đăng nhập</a></p>
   </div>
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script type="module" src="<?= ROOT ?>/public/js/Register.js"></script>
</body>

</html>