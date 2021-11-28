<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset password</title>
   <link rel="icon" href="<?= ROOT ?>/public/img/logo.png">
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/reset.css">
   <style>
      body {
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         background: #00aa9d;
      }

      .container {
         min-width: 400px;
         background-color: #fff;
         border-radius: 6px;
         box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.4);
      }

      .form-reset {
         font-size: 18px;
      }

      .form-reset>:first-child {
         padding: 1rem;
         border-bottom: 1px solid #ccc;
         font-size: 20px;
         font-weight: 600;
      }

      .form-reset>:nth-child(2) {
         padding: 2rem 1rem;
         border-bottom: 1px solid #ccc;

      }

      input {
         width: 100%;
         padding: 8px 14px;
         margin: 15px 0 0;
         border-radius: 4px;
         border: 1px solid #ccc;
         outline: none;
      }

      button {
         background: #00aa9d;
         outline: none;
         border: none;
         color: #fff;
         padding: 8px 16px;
         border-radius: 4px;
         font-size: 16px;
         width: 100%;
         margin-top: 15px;
      }
   </style>
</head>

<body>
   <div class="container">
      <?php if ($data['info']) : ?>
         <form class="form-reset" action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
            <div>Đặt lại mật khẩu</div>
            <div>
               <p class="">Mật khẩu mới</p>
               <input type="password" name="password" placeholder="Nhập mật khẩu">
               <button type='submit' name="reset">Xác nhận</button>
            </div>
         </form>
      <?php else : ?>
         <p style="padding:20px;">Không tìm thấy mã</p>
      <?php endif; ?>
   </div>
</body>

</html>