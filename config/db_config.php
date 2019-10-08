<?php
  $db_config = array(
    'server'   => 'localhost',
    'login'    => 'razyapoo',
    'password' => '', // does not need to be submitted with the project
    'database' => 'stud_razyapoo',
   );

// Create connection
$connection = new mysqli($db_config['server'], $db_config['login'], $db_config['password'], $db_config['database']);


// Check connection
if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
} 
?>