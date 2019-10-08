<?php 
(strpos($_SERVER["SCRIPT_FILENAME"], "/php") === FALSE)? 
    include "config/db_config.php" :  
    include "../config/db_config.php";
?>
<form name="AddForm" method="POST" action="php/add.php">
    
    <table class="addTable"> 
    
    <caption class="block4"> Add Item </caption>
        <tr>
            <th>Item:</th>
            <th>Amount:</th>
        </tr>
        <tr>
            <td><input type="text" list="categoryname" autocomplete="off" name="pcategory" required>
            <datalist id="categoryname">
                 <?php while($row = mysqli_fetch_array($result)) {
                    echo "<option value='". $row['name'] ."'/>"; 
                 }?>
            </datalist>
            </td>
            <?php mysqli_close($connection); ?>
            <td><input type="number" min="1" autocomplete="off" name="icategory" required value=1></td>
        </tr>
        <tr>
            <td><input type="submit" class='blue-1' name="butt_add" value="Add"></td>
        </tr>
    </table>
        
    
    </form>
    