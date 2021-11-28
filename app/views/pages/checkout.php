<div class="content-inner">
   <div class="container">
      <div style="padding: 2.5rem 0;">
         <div class="row">
            <div class="col-12 col-lg-7 col-md-12 col-sm-12">
               <div class="customer-detail">
                  <h3>Thông tin thanh toán</h3>
                  <form class="customer-form" id="customer-form">
                     <div class="form-group">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="customer" id="name" class="form-control">
                        <p class="form-message"></p>
                     </div>
                     <div class="form-group">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="address" id="address" class="form-control">
                        <p class="form-message"></p>
                     </div>
                     <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                        <p class="form-message"></p>
                     </div>
                     <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control">
                        <p class="form-message"></p>
                     </div>
                  </form>
               </div>
            </div>
            <div class="col-12 col-lg-5 col-md-12 col-sm-12">
               <div class="order-detail">
                  <h3>Thông tin đơn hàng</h3>
                  <div>
                     <table style="width: 100%;font-size:1.7rem" class="order-table">
                        <thead>
                           <tr>
                              <th style="text-align: left;font-weight: 600;">Sản phẩm</th>
                              <th style="text-align: right;font-weight: 600;">Tổng tiền</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($data['cart'] as $item) : ?>
                              <tr>
                                 <td><?= $item['name'] ?> x<?= $item['quantity'] ?></td>
                                 <td><?= formatNumber($item['price'] * $item['quantity']) ?></td>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                        <tfoot style="border-bottom: 1px solid #ccc;">
                           <tr>
                              <th style="font-weight: bold;">Tổng</th>
                              <td style="color:#dc3545;font-weight:bold">
                                 <?php
                                 $total = array_reduce($data['cart'], function ($acc, $item) {
                                    return $acc + intval($item['price']) * intval($item['quantity']);
                                 }, 0);
                                 echo formatNumber($total);
                                 ?>
                              </td>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
                  <div class="payment">
                     <div>
                        <input type="radio" checked name="payment" value="2"> Thanh toán khi nhận hàng
                     </div>
                     <div>
                        <input type="radio" name="payment" value="1"> Chuyển khoản
                     </div>
                  </div>
                  <?php if (!isset($_COOKIE['user_id'])) : ?>
                     <a href="<?= ROOT ?>/user/login" class="btn btn-order">Đặt hàng</a>
                  <?php else : ?>
                     <button class="btn btn-order" type="submit" form="customer-form">Đặt hàng</button>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>