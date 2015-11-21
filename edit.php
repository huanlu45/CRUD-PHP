<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['name']) && isset($_SESSION['user_id']) ) {
    die("ACCESS DENIED");
}

if ( isset($_POST['first_name']) && isset($_POST['last_name']) 
    && isset($_POST['email']) && isset($_POST['headline']) 
    && isset($_POST['summary'])) {

    //data validation
    if ( strlen($_POST['first_name']) < 1 ||  strlen($_POST['last_name']) < 1  ||  strlen($_POST['email']) < 1  || strlen($_POST['headline']) < 1  || strlen($_POST['summary']) < 1  ) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php"."?id=".$_GET['id']);
        return;
    }

    //email validation
    $email = $_POST['email'];
    if (strpos($email, "@") !== false) {
        $split = explode("@", $email);
        if (strpos($split['1'], ".") == false) { 
        $_SESSION['error'] = 'Email address must contain @'; 
        header("Location: edit.php"."?id=".$_GET['id']);
        return;
        }
   }
   else { 
    $_SESSION['error'] = 'Email address must have @'; 
    header("Location: edit.php"."?id=".$_GET['id']);
    return;
    }
    
    //Update data to array
    $stmt = $pdo->prepare('UPDATE Profile SET first_name= :first_name, last_name= :last_name, email= :email, headline= :headline, summary= :summary WHERE profile_id= :pid');
    $stmt->execute(array(
        ':pid' => $_GET['id'],
        ':first_name' => htmlentities($_POST['first_name']),
        ':last_name' => htmlentities($_POST['last_name']),
        ':email' => htmlentities($_POST['email']),
        ':headline' => htmlentities($_POST['headline']),
        ':summary' => htmlentities($_POST['summary']))
    );
    $_SESSION['success'] = 'Profile updated';
    header('Location: index.php');
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Lu Huang's Profile Editing</title>
</head>
<body style="font-family: sans-serif;">
<h1>Editing Profile for <?= htmlentities($_SESSION['name']); ?></h1>
<?php
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
<?php
$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(':pid' => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<form method="post">
    <p>First Name:<input type="text" name="first_name" size="70" value="<?php echo $row['first_name'];?>"></p>
    <p>Last Name:<input type="text" name="last_name" size="70" value="<?php echo $row['last_name'];?>"></p>
    <p>Email:<input type="text" name="email" size="50" value="<?php echo $row['email'];?>"></p>
    <p>Headline:<input type="text" name="headline" size="70" value="<?php echo $row['headline'];?>"></p>
     <p>Summary:</p><input type="text" name="summary" style="width:500px; height:100px;" value="<?php echo $row['summary'];?>"><br /><br />



<input type="submit" value="Update">
<a href="index.php">Cancel</a>
</form>
</body>
</html>
