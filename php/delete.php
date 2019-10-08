<?php 
(strpos($_SERVER["SCRIPT_FILENAME"], "/php") === FALSE)? 
    include "config/db_config.php" :  
    include "../config/db_config.php";

$id = $_POST['del_id'];

if($id > 0){

  // Check if record exists
  $stmt = $connection->prepare("SELECT * FROM list WHERE id=?") or die("Error " . mysqli_error($connection));
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $sql = $stmt->get_result();
  $num = mysqli_num_rows($sql);

  if($num > 0){
    // Delete record
    $stmt = $connection->prepare("DELETE FROM list WHERE id=?") or die("Error " . mysqli_error($connection));
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo 1;
    exit;
  }

  $stmt->close();
}
echo 0;
exit;