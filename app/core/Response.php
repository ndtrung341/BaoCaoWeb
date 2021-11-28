<?php
class Response
{
   public static function json($status = 1, $message = 'success', $data = array())
   {
      header("HTTP/1.1 " . $status . " " . $message);
      header('Content-type: application/json; charset=utf-8');
      $response = array('status' => $status, 'message' => $message, 'data' => $data);
      echo json_encode($response);
      die();
   }
}
