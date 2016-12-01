<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Francois+One|Open+Sans" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="main.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="script.js"></script>
</head>
<body>
<?php
include 'methods.php';
?>


  <div id="overlay-screen">
    <div class="top-space"></div>
    <div id="login-area">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

         <div class="row" id="user-input-section" >
           <div class="col-sm-12 overlay-header">
             <h2>Enter your username and password</h2>
             <hr>
           </div>
           <div class="col-sm-5 text-right">
             <p>Username:</p>
             <p>Password:</p>
           </div>
           <div class="col-sm-7" id="input-boxes">
             <input type="text" name="username"><br>
             <input type="password" name="password"><br>
           </div>
           <br>
         </div>

         <?php
         $submitted = false;
         $username = $password = $firstname = $userId = "";

         if ($_SERVER["REQUEST_METHOD"] == "POST") {

           if(empty($_POST["username"])) {
             $submitted = false;
           }
           else {
             $submitted = true;
             // var_dump($_POST);
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

             $sql = "SELECT * FROM login WHERE UserName = '$username' AND UserPassword ='$password'";
             $result = $conn->query($sql);
             if($result->num_rows > 0) {
               $authenticated = true;
               $row = $result->fetch_assoc();
               $firstname = $row["FirstName"];
               $userId = $row["ID"];
               ?>
                <script>
                $('#overlay-screen').css('display', 'none');
                // $('#user-specific-area').css('display', 'block');
                // $('#user-specific-area').html('<h1>Welcome <?php //echo $firstname ?></h1>');
                </script>
               <?php
             }
             else {
               ?>
               <script>
               $('#overlay-screen').append("<div class='error'>Unable to log in, try again</div>");
               </script>
               <?php
             }

             $conn->close();
             ?>
             <script>
             $('#input-boxes', 'input').val('');
             </script>
             <?php
           }
         }
         ?>

       <div class="text-center">
         <input type="submit" name="submit" value="Submit" id="submit-button">
         <input type="submit" id="signup-button" value="Register" onclick="goToRegisterPage()">
       </div>
     </form>
    </div>
  </div>



  <div class="row" id="background">
     <div class="col-md-8 col-md-offset-2 main-column">

       <h1>Welcome</h1>
       <input type="submit" value="logout" onclick="logout()"></input>
       <hr>
       <div id="user-specific-area">

         <form action="upload.php" method="post" enctype="multipart/form-data">
            <p>Select image to upload:</p>
            <input type="file" name="fileToUpload" id="fileToUpload"><br>
            <input type="submit" value="Upload Image" name="submit">

            <input type="hidden" name="userFirstname" value="<?php echo $firstname ?>">
            <input type="hidden" name="userId" value="<?php echo $userId ?>">
          </form>
          <hr>
       </div>
     </div>
  </div>

</body>
</html>
