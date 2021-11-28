<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Page not found</title>
   <style>
      body {
         background-color: #e1f5fa;
         margin: 0;
         padding: 0;
         display: flex;
         flex-direction: column;
         align-items: center;
      }

      img {
         margin: auto;
         max-width: 100%;
      }

      a {
         display: inline-block;
         min-width: 100px;
         padding: 10px 12px;
         text-align: center;
         background-color: #89d7ed;
         text-decoration: none;
         font-size: 16px;
         color: #000;
      }
   </style>
</head>

<body>
   <img src="<?= ROOT ?>/public/img/404.png">
   <a href="<?= ROOT ?>/home/" class="">Home</a>
</body>

</html>