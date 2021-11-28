<?php
// if (isset($_COOKIE['user'])) {
//    $user = json_decode($_COOKIE['user'], true);
// }
?>
<header class="header<?= $data['page'] != 'home' ? " fill" : "" ?>">
   <div class="header-wrapper">
      <div class="container">
         <div class="row">
            <!-- HEADER NAVBAR -->
            <div class="col-lg-5 col-md-5  header-navbar">
               <ul class="navbar-list">
                  <li class="navbar-item active">
                     <a href="<?= ROOT ?>" class="navbar-link">Home</a>
                  </li>
                  <li class="navbar-item">
                     <a href="<?= ROOT ?>/product/" class="navbar-link">Shop</a>
                     <!-- SUB NAVBAR -->
                     <ul class="sub-navbar">
                        <li class="sub-navbar__item">
                           <a href="#" class="sub-navbar__link">Set by Theme</a>
                        </li>
                        <li class="sub-navbar__item">
                           <a href="#" class="sub-navbar__link">Theme</a>
                           <!-- SUB-NAVBAR CHILD-->
                           <ul class="sub-navbar__child sub-navbar__theme">
                              <li><a href="#">Technic™</a></li>
                              <li><a href="#">Star Wars™</a></li>
                              <li><a href="#">NINJAGO®</a></li>
                              <li><a href="#">Technic™</a></li>
                              <li><a href="#">Star Wars™</a></li>
                              <li><a href="#">NINJAGO® 123</a></li>
                              <li><a href="#">NINJAGO®</a></li>
                              <li><a href="#">Technic™</a></li>
                              <li><a href="#">Star Wars™</a></li>
                              <li><a href="#">NINJAGO® 123</a></li>
                           </ul>
                           <!--END SUB-NAVBAR CHILD-->
                        </li>
                        <li class="sub-navbar__item">
                           <a href="#" class="sub-navbar__link">Age</a>
                           <ul class="sub-navbar__child">
                              <li>
                                 <a href="#">1.5+</a>
                              </li>
                              <li>
                                 <a href="#">4+</a>
                              </li>
                              <li>
                                 <a href="#">6+</a>
                              </li>
                              <li>
                                 <a href="#">9+</a>
                              </li>
                              <li>
                                 <a href="#">14+</a>
                              </li>
                              <li>
                                 <a href="#">18+</a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                     <!-- END SUB NAVBAR -->
                  </li>
                  <li class="navbar-item">
                     <a href="#" class="navbar-link">Contact</a>
                  </li>
                  <li class="navbar-item">
                     <a href="#" class="navbar-link">Help</a>
                  </li>
               </ul>
            </div>
            <!-- HEADER NAVBAR -->

            <!-- HEADER LOGO -->
            <div class="col-2 col-lg-2 col-md-2  header-logo">
               <a href="<?= ROOT ?>/" class="header-logo__link" id="">
                  <img src="<?= ROOT ?>/public/img/logo.png" alt="" />
               </a>
            </div>
            <!-- HEADER LOGO -->

            <!-- HEADER ICON-->
            <div class="col-10 col-lg-5 col-md-10 header-icon" style="flex: 1;">
               <ul class="icon__list">
                  <li class="icon__item icon__bars">
                     <ion-icon name="list-outline"></ion-icon>
                  </li>
                  <li class="icon__item search">
                     <div class="search-inner">
                        <form class="search-form" action="<?= ROOT ?>/product/">
                           <div class="btn-close"><i class="fas fa-times"></i></div>
                           <div><input type="text" name="q" placeholder="Search..."></div>
                           <button type="submit">
                              <ion-icon name="search"></ion-icon>
                           </button>
                        </form>
                        <div class="icon-search">
                           <ion-icon name="search"></ion-icon>
                        </div>
                     </div>
                  </li>
                  <?php if (!isset($_COOKIE['user_id'])) : ?>
                     <li class="icon__item icon__account">
                        <a href="<?= ROOT . "/user/login" ?>" class="">
                           <ion-icon name="person"></ion-icon>
                        </a>
                     </li>
                  <?php else : ?>
                     <li class="icon__user">
                        <img src="https://source.unsplash.com/random" alt="">
                        <span><?= $_COOKIE['username'] ?></span>
                        <ul class="user_action">
                           <li>Tài khoản</li>
                           <li><a href="<?= ROOT ?>/user/purchase">Đơn mua</a></li>
                           <li><a href="<?= ROOT ?>/user/logout">Đăng xuất</a></li>
                        </ul>
                     </li>
                  <?php endif; ?>
                  <?php if ($data['page'] != 'cart_detail') : ?>
                     <li class="icon__item icon__cart">
                        <ion-icon name="cart"></ion-icon>
                        <?php
                        $count = array_reduce($data['cart'], function ($acc, $item) {
                           return $acc + $item['quantity'];
                        }, 0);
                        ?>
                        <span class="cart-amount"><?= $count ?></span>
                     </li>
                  <?php endif; ?>
               </ul>
            </div>
            <!-- HEADER ICON -->
         </div>
      </div>
   </div>
</header>


<!-- MOBILE NAVBAR -->
<div class="mobile-menu">
   <div class="mobile-menu__header">
      <h3>menu</h3>
      <div class="btn-close">
         <ion-icon name="close"></ion-icon>
      </div>
   </div>
   <ul class="menu">
      <li class="menu-item">
         <a href="#" class="menu-link">Home</a>
      </li>
      <li class="menu-item active">
         <a href="#" class="menu-link">Shop</a>
         <ion-icon name="caret-forward"></ion-icon>
         <ul class="submenu">
            <li class="menu-item active">
               <div class="menu-link">Theme</div>
               <ion-icon name="caret-forward"></ion-icon>
               <ul class="submenu__child">
                  <li class="menu-item"><a href="#">Architecture</a></li>
                  <li class="menu-item"><a href="#">Technic</a></li>
                  <li class="menu-item"><a href="#">Star Wars</a></li>
                  <li class="menu-item"><a href="#">Ninjago</a></li>
               </ul>
            </li>
            <li class="menu-item">
               <div class="menu-link">Age</div>
               <ion-icon name="caret-forward"></ion-icon>
               <ul class="submenu__child">
                  <li class="menu-item"><a href="#">4+</a></li>
                  <li class="menu-item"><a href="#">8+</a></li>
                  <li class="menu-item"><a href="#">14+</a></li>
                  <li class="menu-item"><a href="#">18+</a></li>
               </ul>
            </li>
         </ul>
      </li>
      <li class="menu-item">
         <a href="#" class="menu-link">Contact</a>
      </li>
      <li class="menu-item">
         <a href="#" class="menu-link">Help</a>
      </li>
   </ul>
</div>
<!-- END MOBILE NAVBAR -->