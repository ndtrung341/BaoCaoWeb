<?php
if (!isset($_SESSION['admin']))
   header('location: ' . ROOT . '/admin/login');
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel | <?= $data['title'] ?></title>
   <link rel="icon" href="<?= ROOT ?>/public/img/logo.png">
   <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
   <link rel="stylesheet" href="<?= ROOT ?>/public/css/admin.css">
</head>

<body>
   <header class="py-2 px-4 bg-white shadow">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
         <div class="d-flex align-items-center">
            <a href="/" class=" mb-2 mb-lg-0 text-white text-decoration-none" style="width: 50px;height: 50;">
               <img src="<?= ROOT ?>/public/img/logo.png" style="max-width: 100%;">
            </a>
            <h5 class="text-uppercase ms-3 mb-0">Admin Panel</h5>
         </div>


         <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3 ms-auto me-auto">
            <input type="search" class="form-control form-control-dark rounded-pill" placeholder="Search..." aria-label="Search">
         </form>

         <div class="text-end">
            <div class="account" style="cursor: pointer;">
               <div class="p-2 d-flex align-items-center" data-bs-toggle="dropdown">
                  <img class="rounded-circle me-2" style="width: 45px;height: 45px;" src="https://source.unsplash.com/random" alt="">
                  <span class="h4"><?= $_SESSION['admin']['username'] ?></span>
               </div>
               <div class="dropdown-menu p-0">
                  <a class="dropdown-item p-2 " href="">User Profile</a>
                  <a class="dropdown-item p-2 border-top " href="<?= ROOT ?>/admin/logout">Logout </a>
               </div>
            </div>
            <!-- <button type="button" class="btn btn-outline-dark">Login</button> -->
         </div>
      </div>
   </header>
   <div class="container-fluid">
      <div class="d-flex">
         <nav class="d-md-block sidebar collapse">
            <div class="position-sticky">
               <ul class="nav flex-column text-light">
                  <li class="nav-item">
                     <a class="nav-link <?= $data['title'] == 'Dashboard' ? "active" : "" ?>" aria-current="page" href="<?= ROOT ?>/admin/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home" aria-hidden="true">
                           <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                           <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Dashboard
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link <?= $data['title'] == 'Orders' ? "active" : "" ?>" href="<?= ROOT ?>/admin/orders/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file" aria-hidden="true">
                           <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                           <polyline points="13 2 13 9 20 9"></polyline>
                        </svg>
                        Orders
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link <?= in_array($data['title'], ['Products', 'New Product', 'Edit Product']) ? "active" : "" ?>" href="<?= ROOT ?>/admin/products/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart" aria-hidden="true">
                           <circle cx="9" cy="21" r="1"></circle>
                           <circle cx="20" cy="21" r="1"></circle>
                           <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        Products
                     </a>
                  </li>
                  <!-- <li class="nav-item">
                     <a class="nav-link <?= $data['title'] == 'Customers' ? "active" : "" ?>" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users" aria-hidden="true">
                           <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                           <circle cx="9" cy="7" r="4"></circle>
                           <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                           <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Customers
                     </a>
                  </li> -->
               </ul>
            </div>
         </nav>
         <main class="main">
            <?php require  __DIR__ . "/pages/" . $data['page'] . ".php" ?>
         </main>
      </div>
   </div>
   <!-- JavaScript Bundle with Popper -->
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="<?= ROOT ?>/public/js/jquery-3.6.0.js"></script>
   <script type="module" src="<?= ROOT ?>/public/js/Admin.js"></script>
</body>

</html>