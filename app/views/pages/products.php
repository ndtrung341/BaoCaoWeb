<?php
$isSearch = isset($_GET['q']);
?>
<div class="content-inner">
   <div>
      <div class="container">
         <div class="breadcrumb">
            <a href="/mvc/public/">Home</a> >
            <?= $isSearch ? "Search" : "Shop" ?>
         </div>
         <?php if ($isSearch && $data['paginate']['totalItems'] > 0) : ?>
            <p style="font-size: 1.7rem;margin-bottom: 2rem;">Showing results for <span style="font-weight: 600;"><?= $_GET['q'] ?></span></p>
         <?php endif; ?>
         <?php if ($isSearch && $data['paginate']['totalItems'] == 0) : ?>
            <div style="text-align: center;padding: 4rem 2rem;background-color: #e6f3ff;">
               <h3 style="font-size: 3rem;margin-bottom: 3rem;">We couldn't find anything for "<?= $_GET['q'] ?>"</h3>
               <a href="/mvc/" class="btn" style="background-color: #000;color:#fff;font-size: 1.8rem;border-radius: 4px;">Home</a>
            </div>
         <?php else : ?>
            <div class="row">
               <div class="col-lg-3">
                  <div class="filter">
                     <div class="filter-action__mobile">
                        <button class="btn btn-reset">Reset all</button>
                        <button class="btn btn-finish">Done</button>
                     </div>
                     <div style="border:1px solid #ccc;position: relative;margin-top: 2rem;" class="sort-mobile">
                        <select name="sort" id="sort" class="sort" style="font-size: 1.6rem;width: 100%;height: 100%;position:absolute;opacity: 0;left: 0;top:0">
                           <option value="default" selected>Default</option>
                           <option value="price_desc">Price: High to low</option>
                           <option value="price_asc">Price: Low to high</option>
                        </select>
                        <label style="padding:1rem 2rem;display:flex;align-items: center;">
                           <div style="margin-right: 2rem ;">
                              <div style="font-size: 1.3rem;color:f5f5f5;margin-bottom: 1rem;">Sort by</div>
                              <div class="sort-selected" style="font-size: 1.6rem;">Default</div>
                           </div>
                           <div><i class="fas fa-angle-down"></i></div>
                        </label>
                     </div>
                     <div class="filter-item">
                        <div class="filter-title expand">
                           <span>Theme</span>
                           <i class="fas fa-angle-up"></i>
                        </div>
                        <div class="filter-content">
                           <ul>
                              <?php foreach ($data['themes'] as $item) : ?>
                                 <li>
                                    <label>
                                       <input type="checkbox" value="<?= $item['id'] ?>" hidden name="theme">
                                       <div class="checkbox-custom"><i class="fas fa-check"></i></div>
                                       <span><?= $item['theme'] ?></span>
                                    </label>
                                 </li>
                              <?php endforeach; ?>
                           </ul>
                        </div>
                     </div>
                     <div class="filter-item">
                        <div class="filter-title expand">
                           <span>Age</span>
                           <i class="fas fa-angle-up"></i>
                        </div>
                        <?php $ages = [4, 6, 9, 13, 18] ?>
                        <div class="filter-content">
                           <ul>
                              <?php foreach ($ages as $age) : ?>
                                 <li>
                                    <label>
                                       <input type="checkbox" hidden name="age" value="<?= $age ?>">
                                       <div class="checkbox-custom"><i class="fas fa-check"></i></div>
                                       <span><?= $age ?>+</span>
                                    </label>
                                 </li>
                              <?php endforeach; ?>
                           </ul>
                        </div>
                     </div>
                     <div class="filter-item">
                        <div class="filter-title expand">
                           <span>Price</span>
                           <i class="fas fa-angle-up"></i>
                        </div>
                        <div class="filter-content">
                           <?php $list_price = array_map(function ($item) {
                              return $item['price'];
                           }, $data['paginate']['total']);
                           $max_price = max($list_price);
                           $min_price = min($list_price); ?>
                           <div style="padding: 1.5rem;">
                              <div style="display: flex;justify-content: space-between;font-size: 1.4rem;font-weight: 600;margin-bottom: 2rem;">
                                 <span id="label__price--min">0₫</span>
                                 <span id="label__price--max"><?= formatNumber($max_price) ?>₫</span>
                              </div>
                              <div class="slider-range">
                                 <div class="slider-track"></div>
                                 <input type="range" min="0" max="<?= $max_price ?>" value="<?= $_GET['max_price'] ?? 0 ?>" id="price-min">
                                 <input type="range" min="0" max="<?= $max_price ?>" value="<?= $_GET['max_price'] ?? $max_price ?>" id="price-max">
                              </div>
                              <button class="btn filter_price">Apply</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <button class="btn btn-filter">Filter</button>
               </div>
               <div class="col-lg-9">
                  <div class="summary" style="display: flex;justify-content: space-between;font-size:1.8rem;align-items: center;margin-bottom: 2rem;">
                     <div class="show" style="font-size: 1.7rem;">Showing <?= $data['paginate']['start'] ?> - <?= $data['paginate']['end'] ?> of <?= $data['paginate']['totalItems'] ?> results</div>
                     <div style="border:1px solid #ccc;position: relative;">
                        <select name="sort" id="sort" class="sort" style="font-size: 1.6rem;width: 100%;height: 100%;position:absolute;opacity: 0;left: 0;top:0">
                           <option value="default" selected>Default</option>
                           <option value="price_desc">Price: High to low</option>
                           <option value="price_asc">Price: Low to high</option>
                        </select>
                        <label style="padding:1rem 2rem;display:flex;align-items: center;">
                           <div style="margin-right: 2rem ;">
                              <div style="font-size: 1.3rem;color:f5f5f5;margin-bottom: 1rem;">Sort by</div>
                              <div class="sort-selected" style="font-size: 1.6rem;">Default</div>
                           </div>
                           <div><i class="fas fa-angle-down"></i></div>
                        </label>
                     </div>
                  </div>
                  <div class="products-wrapper">
                     <div class="row no-gutters products">
                        <?php foreach ($data['paginate']['content'] as $product) : ?>
                           <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                              <div class="product-item">
                                 <div class="product-thumb">
                                    <div class="product-img">
                                       <img src="<?= $product['image'] ?>" alt="" />
                                    </div>
                                 </div>
                                 <div class="product-content">
                                    <?php $name = preg_replace('/\s/', "-", strtolower($product['name'])) ?>
                                    <a href="<?= ROOT ?>/product/detail/<?= $name . "-" . $product['product_code'] ?>" class="product-name"><?= $product['name'] ?></a>
                                    <div class="product-rating">
                                       <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                                    </div>
                                    <div class="product-price">
                                       <div class="product-price--now"><?= number_format($product['price'], 0, ',', '.') ?>₫</div>
                                    </div>
                                    <?php $qtyInCart = $data['cart'][$product['product_code']]['quantity'] ?? 0 ?>
                                    <?php if ($product['quantity'] == 0) : ?>
                                       <button class="btn-out">Out of stock</button>
                                    <?php elseif ($product['quantity'] - $qtyInCart > 0) : ?>
                                       <button class="btn btn-add-cart" data-id="<?= $product['product_code'] ?>">Add to cart</button>
                                    <?php else : ?>
                                       <button class="btn-disabled">Limit Exceeded</button>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>
                        <?php endforeach; ?>
                     </div>
                  </div>
                  <div class="loader-products">
                     <p>Loading more item...</p>
                     <div class="loader"></div>
                  </div>
                  <div style="display: flex;justify-content: center;margin-top:3rem">
                     <?= $data['links'] ?>
                  </div>
                  <?php if ($data['paginate']['totalPages'] > 1) : ?>
                     <div class="btn btn-show">Show all</div>
                  <?php endif; ?>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>
<!-- <div class="filter-mobile">
   <div class="" style="display: flex;justify-content: space-between;padding-bottom:2rem;font-size: 2rem;">
      <button class="btn btn-reset">Reset all</button>
      <button class="btn btn-done" style="border: 1px solid dodgerblue;">Done</button>
   </div>
   <div style="border:1px solid #ccc;position: relative;margin-bottom: 2rem;">
      <select name="sort" id="sort" class="sort" style="font-size: 1.6rem;width: 100%;height: 100%;position:absolute;opacity: 0;left: 0;top:0">
         <option value="default" selected>Default</option>
         <option value="price_desc">Price: High to low</option>
         <option value="price_asc">Price: Low to high</option>
      </select>
      <label class="sort-label" style="padding:1rem 2rem;display:flex;align-items: center;">
         <div style="margin-right: 2rem ;">
            <div style="font-size: 1.3rem;color:f5f5f5;margin-bottom: 1rem;">Sort by</div>
            <div class="sort-selected" style="font-size: 1.6rem;">Default</div>
         </div>
         <div><i class="fas fa-angle-down"></i></div>
      </label>
   </div>
   <div class="filter-item">
      <div class="filter-title expand">
         <span>Theme</span>
         <i class="fas fa-angle-up"></i>
      </div>
      <div class="filter-content">
         <ul>
            <?php foreach ($data['themes'] as $item) : ?>
               <li>
                  <label>
                     <input type="checkbox" value="<?= $item['id'] ?>" hidden name="theme">
                     <div class="checkbox-custom"><i class="fas fa-check"></i></div>
                     <span><?= $item['theme'] ?></span>
                  </label>
               </li>
            <?php endforeach; ?>
         </ul>
      </div>
   </div>
   <div class="filter-item">
      <div class="filter-title expand">
         <span>Age</span>
         <i class="fas fa-angle-up"></i>
      </div>
      <?php $ages = [4, 6, 9, 13, 18] ?>
      <div class="filter-content">
         <ul>
            <?php foreach ($ages as $age) : ?>
               <li>
                  <label>
                     <input type="checkbox" hidden name="age" value="<?= $age ?>">
                     <div class="checkbox-custom"><i class="fas fa-check"></i></div>
                     <span><?= $age ?>+</span>
                  </label>
               </li>
            <?php endforeach; ?>
         </ul>
      </div>
   </div>
   <div class="filter-item">
      <div class="filter-title expand">
         <span>Price</span>
         <i class="fas fa-angle-up"></i>
      </div>
      <div class="filter-content">
         <div style="padding: 1.5rem;">
            <div style="display: flex;justify-content: space-between;font-size: 1.4rem;font-weight: 600;margin-bottom: 2rem;">
               <span id="label__price--min">0₫</span>
               <span id="label__price--max"><?= formatNumber($data['max_price']) ?>₫</span>
            </div>
            <div class="slider-range">
               <div class="slider-track"></div>
               <input type="range" min="0" max="<?= $data['max_price'] ?>" value="<?= $_GET['max_price'] ?? 0 ?>" id="price-min">
               <input type="range" min="0" max="<?= $data['max_price'] ?>" value="<?= $_GET['max_price'] ?? $data['max_price'] ?>" id="price-max">
            </div>
            <button class="btn filter_price">Apply</button>
         </div>
      </div>
   </div>
</div> -->