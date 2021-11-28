<?php $product = $data['product'] ?>
<div class="product">
   <div class="container">
      <div class="breadcrumb">
         <a href="/mvc/public/">Home</a> >
         <a href=""><?= $product['theme'] ?></a> >
         <?= $product['name'] ?>
      </div>
      <div class="product-inner">
         <div class="row">
            <div class="col-12 col-lg-5 col-md-6 col-sm-12">
               <div class="gallery">
                  <div class="image-main">
                     <div class="image-list swiper">
                        <div class="swiper-wrapper">
                           <?php foreach ($data['images'] as $image) : ?>
                              <div class="swiper-slide">
                                 <div class="img-item">
                                    <img src="<?= $image ?>" alt="" class="">
                                 </div>
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>
                     <!-- <img src="<?= $data['images'][0] ?>" alt=""> -->
                  </div>
                  <div class="thumbs swiper">
                     <div class="swiper-wrapper">
                        <?php foreach ($data['images'] as $image) : ?>
                           <div class="swiper-slide">
                              <div class="img-item">
                                 <img src="<?= $image ?>" alt="" class="">
                              </div>
                           </div>
                        <?php endforeach; ?>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-12 col-lg-7 col-md-6 col-sm-12">
               <div class="product-info">
                  <div class="" style="display: flex;justify-content: space-between;align-items: flex-start;">
                     <div class="product-name"><?= $product['name'] ?></div>
                     <div class="product-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                     </div>
                  </div>
                  <div class="product-price"><?= formatNumber($product['price']) ?> ₫</div>
                  <p style="font-size: 1.6rem;line-height: 2.5rem;text-align: justify;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa debitis reiciendis iure quam a repellat eaque, numquam placeat libero odio dolorum modi adipisci nam veniam eum dolor alias harum nulla!</p>
                  <?php $qtyInCart = $data['cart'][$product['product_code']]['quantity'] ?? 0 ?>
                  <?php if ($product['quantity'] == 0) : ?>
                     <div style="font-size: 2rem;margin-top:2rem;color:rgb(208, 2, 27)">Temporarily out of stock</div>
                  <?php elseif ($product['quantity'] - $qtyInCart > 0) : ?>
                     <div class="quantity">
                        <div class="quantity-control">
                           <div class="quantity-decrease">
                              <ion-icon name="remove-outline"></ion-icon>
                           </div>
                           <input type="number" class="quantity-input" value="1">
                           <div class="quantity-increase">
                              <ion-icon name="add-outline"></ion-icon>
                           </div>
                        </div>
                        <div class="quantity-available"><span class="product-quantity"><?= $product['quantity'] ?></span> sản phẩm có sẵn</div>
                     </div>
                     <button class="btn btn-add-cart" data-id="<?= $product['product_code'] ?>">Add to cart</button>
                  <?php else : ?>
                     <button class="btn-disabled">Limit Exceeded</button>
                  <?php endif; ?>
                  <div class="product-more">
                     <div><span>Thương hiệu:</span><span><?= $product['theme'] ?></span></div>
                     <div><span>SKU:</span><span><?= $product['product_code'] ?></span></div>
                     <div><span>Tags:</span><span>Exclusives</span></div>
                  </div>
               </div>

            </div>
         </div>
      </div>
      <div style="padding: 4rem 0;">
         <div class="tab-wrapper">
            <div class="tabs">
               <div class="tab active">mô tả</div>
               <div class="tab">đánh giá</div>
            </div>
            <div class="tab-content">
               <div class="tab-panel active">
                  <p class="" style="white-space: pre-wrap;"><?= $product['description'] ?></p>
               </div>
               <div class="tab-panel">
                  <p style="font-size: 2.5rem;">Chưa có đánh gía cho sản phẩm này</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>