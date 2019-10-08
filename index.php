<?php
require(__DIR__ . '/config/db_config.php');
$ret = '';

// Fetch data from database
$sql = "select name from items order by name";
$result = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
?>
<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<link rel="stylesheet" href="mystyle.css" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>

</head>
<body>
<form action="#" method="post">
<?php require("php/header.php") ?>
<table class="MainTable"> 
<caption class="block2"><i>Here's your shopping plan! Enjoy!</i></caption>
<thead>

    <tr>
        <th>Item</th>
        <th>Amount</th>
        <th></th> 
<?php
    $sql = "SELECT u.item_id, u.id, u.amount, u.position, d.name AS d_name FROM list u INNER JOIN items d ON u.item_id = d.id ORDER BY position"; //position ?
    $result3 = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));


//  Move
include "php/move.php"; 

//  Add check if item exists
session_start();
if (isset($_SESSION['ret']))
    {
         $ret = $_SESSION['ret']; 
         session_destroy();
    }

//  Update
if(isset($_GET['edit_id']) & !empty($_GET['edit_id'])){
 
    $a = htmlspecialchars($_GET['edit_id']);
    $editId = htmlspecialchars_decode($a);
 
    $sql = $connection->prepare("SELECT * FROM `list` WHERE `id`=? LIMIT 1") or die("Error " . mysqli_error($connection));
    $sql->bind_param("i", $editId);
    $sql->execute();
    $query = $sql->get_result();

    if ($query->num_rows != 0) {

        if(isset($_POST['e'.$editId]) & !empty($_POST['e'.$editId])){
    
            $a = htmlspecialchars($_POST['e'.$editId]);
            $editSave = htmlspecialchars_decode($a);
    
            $sql = $connection->prepare("UPDATE `list` SET `amount`=? WHERE `id`=? LIMIT 1") or die("Error " . mysqli_error($connection));
            $sql->bind_param("ii", $editSave, $editId);
            $sql->execute();
            $sql->close();
            header('Location: ?');
        }
    } else { echo "<script>alert('Item not fount.');
        window.location.href='?';
        </script>";};
};

//  Table
$flagColumn=1;
$flagMove = false;

if (!isset($editId)) {echo "<th></th><tr></tr>"; };
?>
</thead>
<tbody> 
<?php
$num = -1;

while($myrow3=mysqli_fetch_array($result3))
{
    // Edit
    $num = $num + 1;
    $_view = "style='display: inline;'";
    $_hidden = "style='display: none;'";
    $_editCell = "style='display: none;'"; 
    $_editValue = $myrow3['amount'];
    if ($flagMove){
        $_editTD    = "<td> <span class='blue' id='{$myrow3['id']}'>↑↓</span> </td> ";
        } else {
            $_editTD  = "<td></td> ";
            $flagMove = true;
        }
    
    // Check if exists
    if (isset($_GET['edit_id']) & !empty($_GET['edit_id'])) {
        $a = htmlspecialchars($_GET['edit_id']);
        $editId = htmlspecialchars_decode($a);

        $flagColumn=0;
        if ($editId==$myrow3['id']) {
            $_hidden = "style='display: inline;'";
            $_view   = "style='display: none;'";
            $_editCell = "type='number' min='1' value=".$myrow3['amount']." name='e{$myrow3['id']}'";
            $_editValue='';
            $_editTD = '';
        } else{
            $_view = "style='display: none;'";
            $_editCell = "type='hidden'";
            $_editTD = '';
            }     
    }

    //  Show tabel

    echo 
    
        "<tr>" .
        "<td> <input type='hidden' id=e{$myrow3['id']} min={$myrow3['position']}>{$myrow3['d_name']}</td>" .
        "<td> <input $_editCell >$_editValue</td>".
        "$_editTD".
        "<td> <a class='yellow' $_view href='?edit_id={$myrow3['id']}'>Edit</a>".
        "<span class='red' $_view id='del_id{$myrow3['id']}'>Delete</span>".
        "<input class='green' $_hidden type='submit' value='Save'>".
        " <a class='red' $_hidden href='?'>Cansel</a> </td>" .
        "</tr>";
}

?>
</tbody>
</table>
</form>
<div class='block3'><?php echo $ret; ?></div>
<?php include "php/footer.php"; ?>
</body>

</html>
