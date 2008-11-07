<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Infinity Metrics</title>
    </head>
    <body>
        Welcome to Infinity Metrics 0.1

   <?php
   
   set_include_path("c:\ppm8-dev\app\classes\bookstore\build\classes" . PATH_SEPARATOR . get_include_path());
   require_once 'propel/Propel.php';

   Propel::init('bookstore/build/conf/bookstore-conf.php');

   $author = new Author();
    $author->setFirstName("Andres");
    $author->setLastName("Ardila");
    $author->save();

   ?>


    </body>
</html>
