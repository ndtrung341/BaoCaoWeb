<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'lego');
define('DB_USER', 'root');
define('DB_PASS', '');

$folder = str_replace($_SERVER['DOCUMENT_ROOT'], "", str_replace("\\", "/", __DIR__));
$root =  "http://" . $_SERVER['HTTP_HOST'] . "/" . $folder;
define('ROOT', $root);


function check()
{
   global $folder;
   echo $_SERVER['DOCUMENT_ROOT'];
   echo "<br>";
   echo __DIR__;
   echo "<br>";
   echo $folder;
   echo "<br>";
   echo ROOT;
}
