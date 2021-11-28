<div class="content-inner">
   <?php if (count($data['cart']) == 0) : ?>
      <div class="cart-empty">
         <p class="">You don't have anything in your cart</p>
         <a href="<?= ROOT ?>" class="btn">Start shopping</a>
      </div>
   <?php else : ?>
      <div class="container">
         <h3>My cart</h3>
         <span class="btn btn-clear">Clear cart</span>
         <div class="row">
            <div class="col-12 col-lg-8 col-md-12 col-sm-12">
               <div class="cart-detail">
                  <div class="heading">
                     <div>Detail</div>
                     <div>Total</div>
                     <div></div>
                  </div>
                  <div class="cart-body">
                     <?php foreach ($data['cart'] as $item) : ?>
                        <div class="cart-item" data-id="<?= $item['product_code'] ?>">
                           <div class="" style="display: flex;flex:1">
                              <div class="product__img"><img src="<?= $item['image'] ?>" alt=""></div>
                              <div class="product-detail">
                                 <div class="product-text">
                                    <p class="product-name"><?= $item['name'] ?></p>
                                    <p class="product-price"><?= formatNumber($item['price']) ?></p>
                                 </div>
                                 <div class="product-quantity">
                                    <div class="quantity-control">
                                       <div class="quantity-decrease">
                                          <ion-icon name="remove-outline"></ion-icon>
                                       </div>
                                       <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>">
                                       <div class="quantity-increase">
                                          <ion-icon name="add-outline"></ion-icon>
                                       </div>
                                    </div>
                                    <p>
                                       Còn <?= array_values(array_filter($data['product'], function ($product) use ($item) {
                                                return $product['product_code'] == $item['product_code'];
                                             }))[0]['quantity'] ?> sản phẩm
                                    </p>
                                 </div>
                              </div>
                           </div>
                           <div class="product-total-price">
                              <span class=""><?= formatNumber($item['quantity'] * $item['price']) ?></span>
                           </div>
                           <div>
                              <div class="cart-delete" data-id="<?= $item['product_code'] ?>">
                                 <ion-icon name="trash-outline"></ion-icon>
                              </div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
            <div class="col-12 col-lg-4 col-md-12 col-sm-12">
               <div class="cart-totals__wrapper">
                  <div class="coupon">
                     <div class="heading">
                        <div>Coupon</div>
                     </div>
                     <form class="form-coupon">
                        <div class="form-group">
                           <input type="text" class="form-control" name="coupon" placeholder="Enter coupon...">
                           <p class="form-message"></p>
                        </div>
                        <button class="btn" type="submit">Apply</button>
                     </form>
                  </div>
                  <div class="cart-totals">
                     <div class="heading">
                        <div>Summary</div>
                     </div>
                     <div class="cart-totals__list">
                        <div>
                           <span>Item(s)</span>
                           <span class="total-amount">
                              <?=
                              array_reduce($data['cart'], function ($acc, $item) {
                                 return $acc + intval($item['quantity']);
                              }, 0);
                              ?>
                           </span>
                        </div>
                        <div>
                           <span>Shipping</span>
                           <span>Free</span>
                        </div>
                        <div>
                           <span>Total</span>
                           <span class="total-price">
                              <?php
                              $total = array_reduce($data['cart'], function ($acc, $item) {
                                 return $acc + intval($item['price']) * intval($item['quantity']);
                              }, 0);
                              echo formatNumber($total);
                              ?>
                           </span>
                        </div>
                        <a href="<?= ROOT ?>/checkout/" class="btn btn-checkout">Checkout</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endif; ?>
</div>