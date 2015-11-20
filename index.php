<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Lu Huang's Resume Registry</title>
</head>
<body style="font-family: sans-serif;">
<h1>Lu Huang's Resume Registry</h1>
<?php
if (isset($_SESSION['user_id'])) {
    echo('<a href=');
    echo("logout.php");
    echo('>Logout</a>');

    }else{
      echo('<a href=');
      echo("login.php");
      echo('>Login</a>');
}
?>
<br/ ><br/ >
<br/ >
<table border="1">
    <tr><th>Name</th>
        <th>Headline</th>
        <?php
         if(isset($_SESSION['user_id'])){
            echo('<th>Action</th>');
         }
        ?>
    <tr>
<?php
$stmt = $pdo->prepare("SELECT * FROM Profile");
$stmt->execute(array( ':uid' => $_SESSION['user_id']));
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    $v_url="view.php?id=".$row['profile_id'];
    echo('<a href=');
    echo($v_url);
    echo('>');
    echo($row['first_name']);
    echo(" ");
    echo($row['last_name']);
    echo('</a>');
    echo("</td><td>");
    echo($row['headline']);

    if (isset($_SESSION['user_id'])){
        echo("</td><td>");
        $e_url="edit.php?id=".$row['profile_id'];
        echo('<a href=');
        echo($e_url);
        echo('>Edit</a>');
        echo("  ");
        $d_url="delete.php?id=".$row['profile_id'];
        echo('<a href=');
        echo($d_url);
        echo('>Delete</a>');
        echo("</td></tr>\n");
        }
 }   

?>
</table>
<br /><br />
<a href="Add.php">Add New</a>
</body>
