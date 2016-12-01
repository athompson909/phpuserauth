<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Francois+One|Open+Sans" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="main.css"/>
  <script src="script.js"></script>
</head>
<body>

<?php
include 'methods.php';

$submitted = false;

$firstname = $lastname = $username = $password = $birthday = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if(empty($_POST["firstname"])) {
    $submitted = false;
  }
  else {
    $submitted = true;

    // var_dump($_POST);

    $firstname = test_input($_POST["firstname"]);
    $lastname = test_input($_POST["lastname"]);
    $birthday = test_input($_POST["birthday"]);
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);

    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "root";
    $dbname = "login_data";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    if($conn->connect_error) {
      die("Connection failed: ".$conn->connect_error);
    }

    $sql = "INSERT INTO login (FirstName, LastName, Birthday, UserName, UserPassword) VALUES ('$firstname', '$lastname', '$birthday', '$username', '$password')";

    // echo $sql;
    if($conn->query($sql) != TRUE) {
      echo "error inserting in db";
    }

    $conn->close();
  }
}
?>

<div class="row">
   <div class="col-md-8 col-md-offset-2 main-column">

<?php if(!$submitted) { ?>
     <div id="form-area">

       <div class="page-header">
         <h1>New User Registration</h1>
       </div>

       <div id="register-area">
       <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

          <div class="row">
            <div class="col-sm-5 text-right">
              <p>First Name:</p>
              <p>Last Name:</p>
              <p>Birthday:</p>
              <p>User Name:</p>
              <p>Password:</p>
            </div>
            <div id="user-input-section" class="col-sm-7">
              <input type="text" name="firstname"><br>
              <input type="text" name="lastname"><br>
              <input type="text" name="birthday"><br>
              <input type="text" name="username"><br>
              <input type="password" name="password"><br>
            </div>
          </div>
          <br>

          <div class="row">
            <div class="col-sm-12 text-center">
              <input type="submit" name="submit" value="Submit" id="submit-button"><br>
              <a href="/login/login.php">Return to login page</a>
            </div>
          </div>
       </form>
       </div>
     </div>
<?php } ?>


    <div id="user-input">
      <?php
      if($submitted) {
        echo "<h1>Your Input:</h1><hr>";
        echo "<p>First Name: ".$firstname."</p>";
        echo "<p>Last Name: ".$lastname."</p>";
        echo "<p>User Name: ".$username."</p>";
        echo "<p>Birthday: ".$birthday."</p>";
        echo "<p>Password: ".$password."</p>";
        ?>
        <a href="/login/login.php">Return to login page</a>
      <?php } ?>
    </div>

  </div>
</div>

</body>
</html>
