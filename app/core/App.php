<?php
class App
{
   private $controller = "Home";
   private $action = "index";
   private $params = array();

   public function __construct()
   {
      $this->processUrl();
      // $this->debug();
      if (file_exists(__DIR__ . "/../controllers/"  .  $this->controller . ".php")) {
         require_once __DIR__ . "/../controllers/" .  $this->controller . ".php";
         $this->controller = new $this->controller;
         if (method_exists($this->controller, $this->action)) {
            call_user_func_array(array($this->controller, $this->action), $this->params ?? []);
         } else {
            require_once __DIR__ . "/../views/pages/404.php";
         }
      } else {
         require_once __DIR__ . "/../views/pages/404.php";
      }
   }
   private function processUrl()
   {
      $url = $_GET['url'] ?? "";
      $url = trim($url, "/");

      $url = filter_var($url, FILTER_SANITIZE_URL);
      $arr = explode("/", $url);

      $this->controller = ((isset($arr[0]) && $arr[0]) ? ucfirst($arr[0]) : "Home") . "Controller";
      $this->action =  $arr[1] ?? "index";
      unset($arr[0], $arr[1]);
      $this->params = array_values($arr);
   }
   private function debug()
   {
      echo 'Controller: ' . $this->controller . '<br>';
      echo 'Action: ' . $this->action . '<br>';
      echo 'Parameters: ' . print_r($this->params, true) . '<br>';
   }
}
