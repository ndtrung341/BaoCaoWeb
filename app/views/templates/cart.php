<?php $carts = $data['cart'] ?? array(); ?>
<div class="cart">
   <div class="cart-header">
      <h3>shopping</h3>
      <div class="btn-close">
         <ion-icon name="close"></ion-icon>
      </div>
   </div>
   <?php if (count($carts) == 0) : ?>
      <div class="cart-empty">
         <img style="width: 100%;" src="/mvc/public/img/cart_empty.png" alt="">
         <p>chưa có sản phẩm</p>
      </div>
   <?php else : ?>
      <div class="cart-body">
         <div class="cart-list">
            <?php foreach ($carts as $cart) : ?>
               <div class="cart-item" data-id="<?= $cart['product_code'] ?>">
                  <div class="cart-thumb">
                     <img src="<?= $cart['image'] ?>" alt="" />
                  </div>
                  <div class="cart-info">
                     <h3 class="cart-product__name">
                        <?= $cart['name'] ?>
                     </h3>
                     <div class="cart-calc__price">
                        <span class="cart-product__amount"><?= $cart['quantity'] ?></span> x
                        <strong class="cart-product__price"><?= formatNumber($cart['price']) ?> ₫</strong>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>

         <div class="cart-total">
            <span>Total:</span>
            <span class="cart-total__price">
               <span>
                  <?php
                  $total = array_reduce($carts, function ($acc, $item) {
                     return $acc + intval($item['price']) * intval($item['quantity']);
                  }, 0);
                  echo formatNumber($total);
                  ?>
               </span>
               ₫</span>
         </div>

         <p>Shipping, taxes, and discounts will be calculated at checkout.</p>
         <a href="<?= ROOT ?>/cart/" class="cart-view">view cart</a>
         <a href="<?= ROOT ?>/checkout/" class="btn btn-checkout">checkout</a>
      </div>
   <?php endif; ?>
</div>