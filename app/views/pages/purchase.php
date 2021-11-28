<div class="content-inner" style="background-color: #f5f5f5;">
   <?php if (count($data['orders']) == 0) : ?>
   <?php else : ?>
      <div class="container">
         <div style="padding: 2rem 0;">
            <h3 style="font-size: 3rem;text-transform: uppercase;margin-bottom: 2rem;">Lịch sử mua hàng</h3>
            <?php foreach ($data['orders'] as $index => $order) : ?>
               <div class="" style="padding: 2rem;background-color: #fff;margin: 1.5rem 0;box-shadow: 0 1px 1px 0 rgb(0 0 0 / 5%);border-radius:0.125rem;font-size:1.6rem">
                  <div style="display: flex;justify-content: space-between;margin: 0 -2rem 1rem;padding-bottom: 2rem;border-bottom: 1px solid #ccc;padding: 0 2rem 2rem;">
                     <div>Mã đơn: <?= $order['code'] ?></div>
                     <div>Ngày mua: <?= $order['created_date'] ?></div>
                  </div>
                  <div>
                     <?php foreach ($data['orders_detail'][$index] as $item) : ?>
                        <div style="display: flex;align-items: center;padding: 1.5rem 0 ;border-bottom: 1px solid #e8e8e8;">
                           <div style="flex: 1;display: flex;padding-right: 1rem;">
                              <div style="width: 8rem;height:8rem;padding: .5rem;border: 1px solid #e8e8e8;">
                                 <img src="<?= ROOT . "/public/" . $item['image'] ?>" style="max-width: 100%;height: 100%;object-fit: contain;">
                              </div>
                              <div style="padding-left: 1rem;">
                                 <p style="margin-bottom: 1.5rem;"><?= $item['name'] ?></p>
                                 <p>Qty: <?= $item['quantity'] ?></p>
                              </div>
                           </div>
                           <div style="color:#dc3545;"><?= formatNumber($item['price']) ?>₫</div>
                        </div>
                     <?php endforeach; ?>
                  </div>
                  <div style="font-size: 1.5rem;text-align: right;margin: 1.5rem 0 2rem;">Thành tiền: <span style="font-size: 1.8rem;color:#dc3545;font-weight: 600"><?= formatNumber($order['total']) ?>₫</span></div>
                  <div style="display: flex;justify-content: flex-end;">
                     <a href="<?= ROOT ?>/cart/buyAgain/<?= $order['code'] ?>" style="display: inline-block;min-width: 10rem;padding:1rem 0;text-align: center;border-radius: 4px;background-color: darkorange;cursor: pointer;">Mua lại</a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   <?php endif; ?>

</div>