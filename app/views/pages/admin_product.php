<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
   <h1 class="h2"><?= $data['title'] ?></h1>
</div>
<form class="row form-product" enctype="multipart/form-data" action="<?= $data['action'] ?>">
   <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
      <div class="p-3 bg-white rounded">
         <div class="h4">Thông tin sản phẩm</div>
         <div class="mt-3">
            <div class="form-label">Mã sản phẩm</div>
            <input type="text" class="form-control" name='product_code' value="<?= $data['product']['product_code'] ?? "" ?>">
         </div>
         <div class="mt-3">
            <div class="form-label">Tên sản phẩm</div>
            <input type="text" class="form-control" name='name' value="<?= $data['product']['name'] ?? "" ?>">
         </div>
         <div class="mt-3">
            <div class="form-label">Chủ đề</div>
            <select name="theme" class="form-select">
               <?php foreach ($data['themes'] as $idx => $theme) : ?>
                  <option value="<?= $theme['id'] ?>" <?= (isset($data['product']['theme_id']) && $data['product']['theme_id'] == $theme['id']) ? "selected" : ""  ?>><?= $theme['theme'] ?></option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="mt-3">
            <div class="form-label">Số lượng</div>
            <input type="number" class="form-control" name='quantity' value="<?= $data['product']['quantity'] ?? "" ?>">
         </div>
         <div class="mt-3">
            <div class="form-label">Giá</div>
            <input type="number" class="form-control" name='price' value="<?= $data['product']['price'] ?? "" ?>">
         </div>
         <div class="mt-3">
            <div class="form-label">Mô tả</div>
            <textarea id="editor" name="description"><?= $data['product']['description'] ?? "" ?></textarea>
         </div>
      </div>
   </div>
   <div class="col-lg-6 col-md-12 col-sm-12">
      <div class="p-3 bg-white rounded">
         <div class="d-flex align-items-end">
            <div class="h4 mb-0">Hình ảnh</div>
            <span class="text-decoration-underline ms-3 text-primary fw-bold url" style="cursor: pointer;font-size: 16px;">URL</span>
         </div>
         <div class="d-flex flex-wrap image-list">
            <div class="image-wrapper">
               <label class="btn-upload">
                  <input type="file" hidden class="file-input" id="image" accept="image/*" multiple name="images[]">
                  <div><i class="fas fa-upload"></i></div>
               </label>
            </div>
            <?php foreach ($data['product_images'] ?? [] as $image) : ?>
               <div class="image-wrapper shadow">
                  <div class="image-item rounded" draggable='true'>
                     <img src="<?= $image ?>" class="rounded">
                     <span class="image-remove__btn"><i class="fas fa-trash-alt"></i></span>
                     <span class="image-overlay"></span>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>

   </div>
   <div class="col">
      <div class="py-3 border-top">
         <?php if ($data['action'] == 'add') : ?>
            <button type="submit" name="create" class="btn btn-dark">Thêm mới</button>
         <?php else : ?>
            <button type="submit" name="create" class="btn btn-info">Cập nhật</button>
         <?php endif; ?>
         <a href="<?= ROOT ?>/admin/products/" class="btn btn-secondary">Quay về</a>
      </div>
   </div>
</form>
<script>
   ClassicEditor
      .create(document.querySelector('#editor'))
      .catch(error => {
         console.error(error);
      });
</script>