<?php 
(strpos($_SERVER["SCRIPT_FILENAME"], "/php") === FALSE)? 
    include "config/db_config.php" :  
    include "../config/db_config.php";

    // Add item
    if(isset($_POST["butt_add"])) {

            $a = htmlspecialchars($_POST['pcategory']);
            $b = htmlspecialchars_decode($a);
            $ret = "";
        // Check if input is valid
        if( !preg_match("/^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮa-zA-Z 0-9]+$/u",$b) ){
            $ret = "Invalid input.";  
        } else {
                // Try connection to database with prevent SQL injections 
                // Check if item is in items
                $stmt = $connection->prepare("SELECT * FROM items WHERE name=? LIMIT 1") or die("Error " . mysqli_error($connection));
                $stmt->bind_param('s', $b);
                $stmt->execute();
                $query = $stmt->get_result();
                $a = mysqli_fetch_row( $query );
                $c = $a[0];

                // Check if item is in list
                $stmt = $connection->prepare( "SELECT * FROM list WHERE item_id=? LIMIT 1") or die("Error " . mysqli_error($connection));
                $stmt->bind_param('i', $c);
                $stmt->execute();
                $query = $stmt->get_result();
                
                // If isn't, add
                if( @mysqli_num_rows( $query ) == 0 ){
                    
                    // Get last id of item in list to add new one
                    $rec = mysqli_query($connection, "SELECT * FROM list ORDER BY id DESC") or die("Error " . mysqli_error($connection));
                    $_id = mysqli_fetch_array($rec);
                    $row_id = $_id['id'] + 1; 
                    
                    // Check if item is in items
                    $stmt = $connection->prepare("SELECT * FROM items WHERE name=? LIMIT 1") or die("Error " . mysqli_error($connection));
                    $stmt->bind_param('s', $b);
                    $stmt->execute();
                    $query = $stmt->get_result();

                    // If isn't, add
                    if( @mysqli_num_rows( $query ) == 0 )
                    {
                        //Get last id of item in items
                        $_query = mysqli_query($connection, "SELECT * FROM items  ORDER BY id DESC") or die("Error " . mysqli_error($connection));
                        $_id = mysqli_fetch_array($_query); 
                        $_id = $_id['id'] + 1;
                        
                        $_tov = mb_convert_encoding($_POST['pcategory'], 'UTF-8', 'Windows-1252');
                        
                        // Insert item into items
                        $stmt = $connection->prepare("INSERT INTO items (`id`, `name`) VALUES (?, ?)") or die("Error " . mysqli_error($connection));
                        $stmt->bind_param('is', $_id, $_tov);
                        $stmt->execute();
                    }
                    $stmt = $connection->prepare("SELECT * FROM items WHERE name=? LIMIT 1") or die("Error " . mysqli_error($connection));
                    $stmt->bind_param('s', $b);
                    $stmt->execute();
                    $query = $stmt->get_result();
                    
                    $_names = mysqli_fetch_array($query);
                    $a = htmlspecialchars($_POST['icategory']);
                    $b = htmlspecialchars_decode($a);
                    
                    //Insert item into a list
                    $stmt = $connection->prepare("INSERT INTO list (`id`, `item_id`, `amount`, `position`) VALUES (?, ?, ?, ?)") or die("Error " . mysqli_error($connection));
                    $stmt->bind_param('iiii', $row_id, $_names['id'], $b, $row_id);
                    $stmt->execute();
                } else { $ret = "Item already in the tabel."; 
                    unset($_POST); };
                $stmt->close();
            }
    }
    session_start();
    header('Location: '.$_SERVER['HTTP_REFERER'] , $_SESSION['ret']=$ret);
    exit();
?>