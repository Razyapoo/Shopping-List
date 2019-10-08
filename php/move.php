<?php 
(strpos($_SERVER["SCRIPT_FILENAME"], "/php") === FALSE)? 
    include "config/db_config.php" :  
    include "../config/db_config.php";

if (isset($_POST['id']) && isset($_POST['position'])) { 
    $row = array();
    
    foreach($_POST as $key => $mov_pos ){
        $a = htmlspecialchars($mov_pos);
        $num = htmlspecialchars_decode($a);
        array_push($row, $mov_pos);
    }
    $_POST = '';
    $sql = "SELECT * FROM `list` WHERE 1 ORDER BY `position`";
    $result3 = mysqli_query($connection, $sql);

    // Go to the row num
    mysqli_data_seek($result3, $row[2]-1);
    
    // Get the row
    $DownRow = mysqli_fetch_row($result3);

    // Update. Swap rows
    $sql = $connection->prepare("UPDATE `list` SET `position`=? WHERE `id`=? LIMIT 1") or die("Error " . mysqli_error($connection));
    $sql->bind_param("ii", $pos, $id);

    $pos = $DownRow[3];
    $id = $row[0];
    $sql->execute();
    
    $sql->bind_param("ii", $pos, $id);

    $pos = $row[1];
    $id = $DownRow[0];
    $sql->execute();

    mysqli_query($connection, "ALTER TABLE `list` order by `position`");

    $sql->close();
    
   }
   //exit();
?>