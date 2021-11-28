<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../libs/PHPMailer/src/Exception.php";
require __DIR__ . "/../libs/PHPMailer/src/PHPMailer.php";
require __DIR__ . "/../libs/PHPMailer/src/SMTP.php";
class Email
{
   private $mail;

   function __construct($username, $password)
   {
      $this->mail = new PHPMailer(true);
      $this->mail->isSMTP();
      $this->mail->Host       = "smtp.gmail.com";
      $this->mail->SMTPAuth   = true;
      $this->mail->Username   = $username;
      $this->mail->Password   = $password;
      $this->mail->SMTPSecure = 'tls';
      $this->mail->Port       = 587;
      $this->mail->CharSet    = 'UTF-8';
      $this->mail->setFrom($username, "Shop Account");
   }

   function setContent($content)
   {
      $this->mail->Subject = $content['subject'];
      $this->mail->Body    = $content['body'];
   }

   function sendMail($m)
   {
      $this->mail->isHTML(true);
      $this->mail->addAddress($m);
      return $this->mail->send();
   }
}
