<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel | Login </title>
   <link rel="icon" href="<?= ROOT ?>/public/img/logo.png">

   <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/admin.css">
   <style>
      body {
         display: flex;
         align-items: center;
         padding-top: 40px;
         padding-bottom: 40px;
         background-color: #f5f5f5;
      }
   </style>
</head>

<body class="text-center">
   <div style="width: 100%;max-width: 350px;padding: 15px;margin: auto">
      <img src="<?= ROOT ?>/public/img/logo.png" class="mb-4" width="72" height="72">
      <h1 class="h2 mb-3 fw-normal">Admin Panel</h1>
      <?php if ($data['error']) : ?>
         <div class="alert alert-danger" role="alert">
            <?= $data['error'] ?>
         </div>
      <?php endif; ?>
      <form action="<?= ROOT ?>/admin/system/login/" method="POST" class="">
         <div class="form-floating mb-3">
            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="duytrung">
            <label for="floatingInput">Username</label>
         </div>
         <div class="form-floating mb-3">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="duytrung">
            <label for="floatingPassword">Password</label>
         </div>
         <button class="w-100 btn btn-lg btn-primary mt-4" type="submit">Sign in</button>
      </form>
   </div>
   <!-- JavaScript Bundle with Popper -->
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="<?= ROOT ?>/public/js/jquery-3.6.0.js"></script>
   <script type="module" src="<?= ROOT ?>/public/js/Admin.js"></script>
</body>

</html>