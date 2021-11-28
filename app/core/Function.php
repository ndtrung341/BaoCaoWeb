<?php


function formatNumber($number)
{
   return number_format($number, 0, ',', '.');
}


function randomString($n)
{
   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $str = '';
   for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $str .= $characters[$index];
   }
   return $str;
}

function uploadImages($images, $product_code)
{
   $upload_dir = __DIR__ . "/../../public/upload/products/" . $product_code;
   if (!is_dir($upload_dir))
      mkdir($upload_dir);
   $images_path = [];
   for ($i = 0; $i < count($images['name']); $i++) {
      $ext = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
      $filename = randomString(5) . "." . $ext;
      move_uploaded_file($images['tmp_name'][$i],  $upload_dir . "/" . $filename);
      $images_path[] = 'upload/products/' . $product_code . '/' . $filename;
   }
   return $images_path;
}

function deleteFolderImage($product_code)
{
   $dir = __DIR__ . "/../../public/upload/products/" . $product_code;
   if (is_dir($dir)) {
      foreach (glob($dir . '/*') as $file) {
         unlink($file);
      }
      rmdir($dir);
   }
}
