<?php
require_once "pdo.php";
session_start();
if ( ! (isset($_SESSION['name']) && isset($_SESSION['user_id']) )) {
    die("ACCESS DENIED");
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) 
     && isset($_POST['email']) && isset($_POST['headline'])  
     && isset($_POST['summary'])) {

    // Data validation
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) <1 || strlen($_POST['email']) <1 || strlen($_POST['headline']) <1 || strlen($_POST['summary'])< 1) {
        $_SESSION['error'] = 'All fileds are required';
        header("Location: add.php");
        return;
    }

  //email validation
   $email = $_POST['email']; 
   if (strpos($email, "@") !== false) {
      $split = explode("@", $email);
         if (strpos($split['1'], ".") == false) { 
          $_SESSION['error'] = 'Email address must contain @'; 
          header("Location: add.php");
          return;
        }
   }
   else { 
    $_SESSION['error'] = 'Email address must have @'; 
    header("Location: add.php");
    return;
  }


    $sql = "INSERT INTO Profile (user_id, first_name, email, headline, summary, last_name ) VALUES (:user_id, :first_name, :email, :headline, :summary, :last_name)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':user_id' => $_SESSION['user_id'],
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary']));
   $_SESSION['success'] = 'Record Added';
   header( 'Location: index.php' ) ;
   return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
<html>
<head><title>Lu Huang</title></head>
<body style="font-family: sans-serif;">
<h1>Adding Profile for <?= htmlentities($_SESSION['name']); ?> </h1>
<form method="post">
<p>First Name:
<input type="text" size="70" name="first_name"></p>
<p>Last Name:
<input type="text"  size="70" name="last_name"></p>
<p>Email:
<input type="text"  size="50"  name="email"></p>
<p>Headline:
<input type="text"  size="70"  name="headline"></p>
<p>Summary:</p>
<textarea name="summary" rows="8" cols="80"></textarea>
<p><input type="submit" value="Add"/>
<input type="submit" name="cancel" value="Cancel">
<!-- <p><input type="submit" value="Cancel"/> -->
<!-- <a href="view.php">Cancel</a> --></p>
</form>
</body>
</html>
