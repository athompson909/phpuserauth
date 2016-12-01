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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function insertImageInDB($name, $id) {
  $servername = "localhost";
  $dbUsername = "root";
  $dbPassword = "root";
  $dbname = "login_data";

  $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
  if($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
  }

  $sql = "INSERT INTO imageNames (ID, imageName) VALUES ('$id', '$name')";

  // echo $sql;
  if($conn->query($sql) != TRUE) {
    echo "error inserting in db";
  }

  $conn->close();
}

function getImagesFromDB($id) {
  $arr = array();

  $servername = "localhost";
  $dbUsername = "root";
  $dbPassword = "root";
  $dbname = "login_data";

  $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
  if($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
  }

  $sql = "SELECT * FROM imageNames WHERE ID = $id";

  $result = $conn->query($sql);
  if($result->num_rows > 0) {
    $i = 0;
    while($row = $result->fetch_assoc()) {
      array_push($arr, $row["imageName"]);
    }
  }
  $conn->close();
  return $arr;
}
 ?>

<div class="row">
   <div class="col-md-8 col-md-offset-2 main-column">
     <br>
     <input type="submit" value="Return to image upload" id="return-to-image-upload">
     <br>
    <?php
    $firstname = $userId = "";
    $index = 0;

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // var_dump($_POST);
      $firstname = $_POST["userFirstname"];
      $userId = $_POST["userId"];
    }

    if(isset($_POST["submit"])) {

      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {

        echo "<p>userId: '$userId'</p>";
        // echo "File is an image - " . $check["mime"] . ".";

        $image = addslashes($_FILES['fileToUpload']['tmp_name']);
        $image_name = addslashes($_FILES["fileToUpload"]["name"]);
        // echo "<br>image: '$image'";
        echo "<br>image name: '$image_name'<br>";

        insertImageInDB($image_name, $userId);
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
      // echo "<br>target file: '$target_file'<br>" //produces error
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
          echo "Sorry, there was an error uploading your file.";
      }
    }

    $imagesArr = getImagesFromDB($userId);
    ?>
    <h1>Images</h1>

      <img id="img1" src="">
      <img id="img2" src="">
      <img id="img3" src="">
      <img id="img4" src="">
    <hr>



    <hr>
    <input id="prev" type="submit" value="previous">  <input id="next" type="submit" value="next">

  </div>
</div>

</body>
<script>
var index = 0;
$('#prev').click(function() {
  if(index > 0)
    index -=4;
    setImages();
})

$('#next').click(function() {
  index += 4;
  setImages();
});

var images = <?php echo json_encode($imagesArr); ?>;
console.log(images);

function setImages() {
  console.log(index);
  if(images[index] == undefined) {
    index -=4;
    return;
  }
  else {
    $('#img1').attr('src','uploads/'+images[index]);
  }

  if(images[index+1] != undefined) {
    $('img2').css('display','none');
    $('#img2').attr('src','uploads/'+images[index+1]);
  }
  else {
    $('img2').css('display','none');
  }
  if(images[index+2] != undefined) {
    $('img3').css('display','none');
    $('#img3').attr('src','uploads/'+images[index+2]);
  }
  else {
    $('img3').css('display','none');
  }
  if(images[index+3] != undefined) {
    $('img4').css('display','none');
    $('#img4').attr('src','uploads/'+images[index+3]);
  }
  else {
    $('img4').css('display','none');
  }
}
setImages();
</script>
