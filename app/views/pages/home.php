<?php require_once  __DIR__ . "/../templates/banner.php" ?>
<!-- NEW PRODUCT -->
<section class="recommend">
   <div class="recommend-inner">
      <div class="container">
         <div class="section-heading">
            <span class="section-title">New Arrival</span>
         </div>
         <div class="section-content">
            <div class="product-list row">
               <?php foreach ($data['new_products'] as $new_product) : ?>
                  <div class="col-6 col-lg-3 col-md-6 col-sm-6">
                     <div class="product-item">
                        <div class="product-thumb">
                           <div class="product-img">
                              <img src="<?= $new_product['image'] ?>" alt="" />
                           </div>
                        </div>
                        <div class="product-content">
                           <?php $name = preg_replace('/\s/', "-", strtolower($new_product['name'])) ?>
                           <a href="<?= ROOT ?>/product/detail/<?= $name . "-" . $new_product['product_code'] ?>" class="product-name"><?= $new_product['name'] ?></a>
                           <div class="product-rating">
                              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                           </div>
                           <div class="product-price">
                              <div class="product-price--now"><?= number_format($new_product['price'], 0, ',', '.') ?></div>
                           </div>
                           <?php $qtyInCart = $data['cart'][$new_product['product_code']]['quantity'] ?? 0 ?>
                           <?php if ($new_product['quantity'] == 0) : ?>
                              <button class="btn-out">Out of stock</button>
                           <?php elseif ($new_product['quantity'] - $qtyInCart > 0) : ?>
                              <button class="btn btn-add-cart" data-id="<?= $new_product['product_code'] ?>">Add to cart</button>
                           <?php else : ?>
                              <button class="btn-disabled">Limit Exceeded</button>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- END NEW PRODUCT -->
<!-- TRENDING -->
<section class="trend">
   <div class="trend-inner">
      <div class="container">
         <div class="section-heading">
            <span class="section-title">Trending Now</span>
         </div>
         <div class="section-content">
            <div class="product-trend swiper">
               <div class="product-list swiper-wrapper">
                  <?php foreach ($data['trend_products'] as $trend_product) : ?>
                     <div class="swiper-slide trend-slide">
                        <div class="product-item">
                           <div class="product-thumb">
                              <div class="product-img">
                                 <img src="<?= $trend_product['image'] ?>" alt="" />
                              </div>
                           </div>
                           <div class="product-content">
                              <?php $name = preg_replace('/\s/', "-", strtolower($trend_product['name'])) ?>
                              <a href="<?= ROOT ?>/product/detail/<?= $name . "-" . $trend_product['product_code'] ?>" class="product-name"><?= $trend_product['name'] ?></a>
                              <div class="product-rating">
                                 <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                              </div>
                              <div class="product-price">
                                 <div class="product-price--now"><?= number_format($new_product['price'], 0, ',', '.') ?></div>
                              </div>
                              <?php if ($trend_product['quantity'] == 0) : ?>
                                 <button class="btn-out">Out of stock</button>
                              <?php elseif ($trend_product['quantity'] - $qtyInCart > 0) : ?>
                                 <button class="btn btn-add-cart" data-id="<?= $trend_product['product_code'] ?>">Add to cart</button>
                              <?php else : ?>
                                 <button class="btn-disabled">Limit Exceeded</button>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
            </div>
            <div class="trend-button trend-button-prev">
               <ion-icon name="chevron-back-outline"></ion-icon>
            </div>
            <div class="trend-button trend-button-next">
               <ion-icon name="chevron-forward-outline"></ion-icon>
            </div>
         </div>
      </div>
   </div>
</section>
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script type="text/javascript">
   const swiper = new Swiper(".banner-wrapper.swiper", {
      // Optional parameters
      direction: "horizontal",
      loop: true,
      speed: 1000,

      // Navigation arrows
      navigation: {
         nextEl: ".swiper-button-next",
         prevEl: ".swiper-button-prev",
      },
      // auto play
      autoplay: {
         delay: 6000,
         disableOnInteraction: false,
      },
      //pagination
      pagination: {
         el: ".swiper-pagination",
         clickable: true,
      },
   });
   const swiperTrend = new Swiper(".product-trend.swiper", {
      // Optional parameters
      // autoHeight: true,
      direction: "horizontal",
      slidesPerView: 2,
      spaceBetween: 15,
      speed: 500,
      // Navigation arrows
      navigation: {
         nextEl: ".trend-button-next",
         prevEl: ".trend-button-prev",
         disabledClass: "disabled",
      },
      breakpoints: {
         640: {
            slidesPerView: 2,
            spaceBetween: 15,
         },
         768: {
            slidesPerView: 4,
            spaceBetween: 15,
         },
         1024: {
            slidesPerView: 4,
            spaceBetween: 30,
         },
      },
   });
</script>