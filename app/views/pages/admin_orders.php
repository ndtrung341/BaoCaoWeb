<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
   <h1 class="h2"><?= $data['title'] ?></h1>
</div>
<div class="table-responsive table-orders">
   <table class=" table table-hover">
      <thead class="table-dark h5 p">
         <tr>
            <th class="p-3">#</th>
            <th class="text-center p-3">Ngày mua</th>
            <th class="text-center p-3">Khách hàng</th>
            <th class="text-end p-3">Tổng cộng</th>
            <th class="text-center p-3">Trạng thái</th>
            <th class="text-center p-3">Phương thức thanh toán</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($data['orders'] as $order) : ?>
            <tr data-id="<?= $order['code'] ?>">
               <td class="align-middle"><?= $order['code'] ?></td>
               <td class="text-center align-middle"><?= $order['created_date'] ?></td>
               <td class="text-center align-middle"><?= $order['customer'] ?></td>
               <td class="text-end align-middle"><?= formatNumber($order['total']) ?></td>
               <td class="align-middle text-center">
                  <label class="toggle-wrapper">
                     <input type="checkbox" hidden <?= $order['status'] ? "checked" : "" ?>>
                     <div class="toggle rounded-pill">
                        <div class="rounded-circle bg-white"></div>
                     </div>
                  </label>
               </td>
               <td class="text-end align-middle"><?= $order['name'] ?></td>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>