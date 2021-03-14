<?php 
    $host='sql312.epizy.com';
    $db = 'epiz_28135181_blogspace';
    $username = 'epiz_28135181';
    $password = 'foV7sGG0kU0';
    $dsn= "mysql:host=$host;dbname=$db";
    $ConnectingDB = new PDO($dsn, $username, $password);
?>