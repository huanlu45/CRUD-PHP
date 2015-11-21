<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM Profile WHERE profile_id = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':pid' => $_GET['id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name, profile_id FROM Profile where profile_id = :pid");
$stmt->execute(array(":pid" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php') ;
    return;
}

?>
<html>
<head><title>Lu Huang's Profile Deleting</title></head>
<body  style="font-family: sans-serif;">
<h1>Deleting Profile</h1>

<p>First Name: <?= htmlentities($row['first_name']) ?></p>
<p>Last Name: <?= htmlentities($row['last_name']) ?></p>

<form method="post"><input type="hidden"
name="profile_id" value="<?= $row['profile_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>
</body>
</html>
