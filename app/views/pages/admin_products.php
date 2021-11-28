<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
   <h1 class="h2"><?= $data['title'] ?></h1>
</div>

<div class="d-flex pb-3">
   <a href="<?= ROOT ?>/admin/newProduct/" class="btn btn-outline-dark me-auto">Add new product</a>
   <form class="me-4 w-50 form-search d-flex">
      <div class="w-100" style="position: relative;">
         <input type="search" class="form-control form-control-dark rounded " placeholder="Search..." name="keyword">
         <input type="hidden" name="product_code" value="">
         <div class="bg-white shadow search-list" style="position: absolute;top: 100%;left: 0;width: 100%;z-index: 5;">
         </div>
      </div>
      <button class="btn btn-outline-dark">Search</button>
   </form>
   <select class="form-select" style="width: fit-content;">
      <option selected value="10">Show 6</option>
      <option value="25">Show 12</option>
      <option value="50">Show 18</option>
   </select>
</div>
<!-- TABLE -->
<div class="table-responsive table-products">
   <table class="table table-hover">
      <thead class="table-dark h5 p">
         <tr>
            <th class=" p-3">Product</th>
            <th class="text-center p-3">Status</th>
            <th class=" p-3">Theme</th>
            <th class="text-center p-3">Quantity</th>
            <th class="text-end p-3">Price</th>
            <th class="text-center p-3">Action</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($data['products'] as $product) : ?>
            <tr data-id="<?= $product['product_code'] ?>">
               <td class="">
                  <div class="d-flex align-items-center">
                     <img src="<?= $product['image'] ?>" style="width: 80px;height: 80px;object-fit: contain;"></img>
                     <div class="ms-2">
                        <p class="fw-bold mb-0"><?= $product['name'] ?></p>
                        <p class="fst-italic text-secondary"><?= $product['product_code'] ?></p>
                     </div>
                  </div>
               </td>
               <td class="align-middle text-center">
                  <label class="toggle-wrapper">
                     <input type="checkbox" hidden <?= $product['status'] ? "checked" : "" ?>>
                     <div class="toggle rounded-pill">
                        <div class="rounded-circle bg-white"></div>
                     </div>
                  </label>
               </td>
               <td class="align-middle"><?= $product['theme'] ?></td>
               <td class="text-center align-middle"><?= $product['quantity'] ?></td>
               <td class="text-end align-middle"><?= formatNumber($product['price']) ?></td>
               <td class="text-center align-middle text-light">
                  <div class="table-action d-flex justify-content-center">
                     <a href="<?= ROOT ?>/admin/editProduct/<?= $product['product_code'] ?>" class="action-edit bg-warning text-light text-decoration-none">
                        <i class="fas fa-pencil-alt"></i>
                     </a>
                     <div class="action-delete ms-2 bg-danger">
                        <i class="fas fa-trash-alt"></i>
                     </div>
                  </div>
               </td>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
<div class="float-end"><?= $data['paginator'] ?></div>