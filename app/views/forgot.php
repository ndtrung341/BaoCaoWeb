<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot</title>
   <link rel="icon" href="<?= ROOT ?>/public/img/logo.png">
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/reset.css">
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/loader.css">

   <style>
      body {
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         background: #705ded;
      }

      .container {
         min-width: 400px;
         background-color: #fff;
         border-radius: 6px;
         box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.4);
         position: relative;
      }

      .form-forgot {
         font-size: 18px;
      }

      .form-forgot>:first-child {
         padding: 1rem;
         border-bottom: 1px solid #ccc;
         font-size: 20px;
         font-weight: 600;
      }

      .form-forgot>:nth-child(2) {
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

      .form-forgot>:last-child {
         padding: 1rem;
         display: flex;
         justify-content: flex-end;
      }

      .form-forgot>:last-child>* {
         display: inline-block;
         padding: 8px 16px;
         border-radius: 4px;
         font: 16px;
      }

      a {
         background-color: #ccc;
         margin-right: 10px;
      }

      button {
         background: #705ded;
         outline: none;
         border: none;
         font-size: 16px;
         color: #fff;
      }

      .loader {
         width: 80px;
         height: 80px;
         border-width: 10px;
      }
   </style>
</head>

<body>
   <div class="container">
      <form class="form-forgot">
         <div>Khôi phục mật khẩu của bạn</div>
         <div>
            <p>Vui lòng nhập email của bạn để đặt lại mật khẩu</p>
            <input type="text" name="email" placeholder="Email">
         </div>
         <div>
            <a href="<?= ROOT ?>/user/login/">Hủy</a>
            <button type="submit">Khôi phục</button>
         </div>
      </form>
   </div>
   <script type="text/javascript">
      const form = document.querySelector('.form-forgot');
      const container = document.querySelector('.container');
      form.onsubmit = function(e) {
         e.preventDefault();
         const email = document.querySelector('[name=email]').value;
         if (!validateEmail(email))
            alert('Email không hợp lệ');
         else {
            container.insertAdjacentHTML('afterbegin', '<div style="padding:20px"><div class="loader"></div></div>');
            form.style.display = 'none';
            forgot(email);
         }

      }

      function validateEmail(email) {
         const reg = /^\w+([.-]?w+)*@\w+([.-]?\w+)*(.\w{2,3})+$/g;
         return email.match(reg);
      }

      async function forgot(email) {
         const res = await fetch('/mvc/user/forgot', {
            method: "POST",
            body: new URLSearchParams({
               email: email
            })
         });
         const data = await res.json();
         container.firstElementChild.remove();
         if (!data.status) {
            alert(data.message);
            form.style.display = 'block';
         } else {
            container.innerHTML = `<p style="padding:10px;font-size:16px">${data.message}</p>`;
         }
      }
   </script>
</body>

</html>