<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $data['title'] ?? "Lego" ?></title>
   <link rel="icon" href="<?= ROOT ?>/public/img/logo.png">
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/reset.css">

   <!-- CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
   <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
   <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-4-grid@3.4.0/css/grid.min.css"> -->

   <!-- CSS -->
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/bootstrap-grid.css">
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/main.css">
   <?php if ($data['page'] == 'product') : ?>
      <link rel="stylesheet" href="<?= ROOT ?>/public/css/product.css">
   <?php endif; ?>
   <?php if ($data['page'] == 'cart_detail') : ?>
      <link rel="stylesheet" href="<?= ROOT ?>/public/css/cart.css">
   <?php endif; ?>

</head>

<body>
   <?php require_once __DIR__ . "/templates/header.php" ?>
   <!-- VIEW -->
   <div class="content">
      <?php require_once  __DIR__ . "/pages/" . $data['page'] . ".php" ?>
   </div>
   <!-- VIEW -->
   <?php require_once  __DIR__ . "/templates/footer.php" ?>
   <?php if ($data['page'] != 'cart_detail') : ?>
      <?php require  __DIR__ . "/templates/cart.php" ?>
   <?php endif; ?>

   <!-- Javascript CDN -->
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
   <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
   <!-- Javascript -->
   <script type="module" src="<?= ROOT ?>/public/js/Main.js"></script>
   <?php if ($data['page'] == 'product') : ?>
      <script src="<?= ROOT ?>/public/js/Spinner.js"></script>
      <script type="module" src="<?= ROOT ?>/public/js/Product.js"></script>
   <?php elseif ($data['page'] == 'cart_detail') : ?>
      <script src="<?= ROOT ?>/public/js/Spinner.js"></script>
      <script type="module" src="<?= ROOT ?>/public/js/CartDetail.js"></script>
   <?php elseif ($data['page'] == 'checkout') : ?>
      <script type="module" src="<?= ROOT ?>/public/js/Checkout.js"></script>
   <?php elseif ($data['page'] == 'products') : ?>
      <script type="module" src="<?= ROOT ?>/public/js/ListProduct.js"></script>
   <?php endif; ?>
</body>

</html>