<?php
include('../dbcon_trainee.php');
session_start();

if (!isset($_SESSION['ruser'])) {
    if (isset($_REQUEST['rsignin'])) {
        $remail = mysqli_real_escape_string($conn_trainee, trim($_REQUEST['remail']));
        $rpassword = mysqli_real_escape_string($conn_trainee, trim($_REQUEST['rpassword']));

        // Corrected SQL query to use n_number instead of password
        $sql = "SELECT id, full_name FROM trainees WHERE phone_no='$remail' AND n_number='$rpassword' LIMIT 1";
        $result = $conn_trainee->query($sql);

        if ($result->num_rows == 1) {
            $row = mysqli_fetch_assoc($result);
            $name = $row['full_name'];
            $traineeId = $row['id'];
            $_SESSION['ruser'] = true;
            $_SESSION['remail'] = $remail;
            $_SESSION['name'] = $name;
            $_SESSION['traineeId'] = $traineeId;
            echo "<script> location.href='slide.html'; </script>";
            exit;
        } else {
            $msg = "Enter valid phone number and yellow card.";
        }
    }
} else {
    echo "<script> location.href='slide.html'; </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../image/10.png" type="image/png" >
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="../css/all.min.css">

    <title>Login</title>
<style>
    @media only screen and (min-width: 1080px){
   body{
    background-image: url("../image/web1.jpg");
   }
    }
/*tablet view styling*/
    @media only screen and (max-width: 1080px){ 
      body{
    background-image: url("../image/tab2.jpg");
   }
    }
@media only screen and (max-width: 400px){
    body{
    background-image: url("../image/phone3.jpg");
   }
    }
</style>
</head>
<body>
 <div class="text-center mt-5">
     <div style="color: white; font-weight: bold; font-size: 40px;">Login to Questionnaire</div>
</div>
     <div class="container-fluid">
         <div class="row justify-content-center mt-5">
             <div class="col-sm-6 col-md-4 text-white " style="background-color: rgba(0, 0, 0, 0.6); border-radius: 10px;">
                 <form action="" class="p-4 shadow-lg" method="POST" id="myForm">
                     <div class="form-group">
                         <i class="fas fa-user mr-2"></i><label for="name" class="font-weight-bold">Phone Number</label>
                         <input type="text" name="remail" placeholder="Enter phone number" class="form-control">
                     </div>
                     <div class="form-group">
                        <i class="fas fa-key mr-2"></i><label for="name" class="font-weight-bold">N number</label>
                        <input type="password" name="rpassword" placeholder="Enter n number" class="form-control">
                    </div>
                    <div class="submit" style="display: flex;">
                        <button class="btn btn-outline-danger  p-1 font-weight-bold mr-5" name="rsignin" >Submit</button>
                        <div><a class="btn btn-outline-info ml-1" href="../index.php">Home</a></div>
                    </div>
                 </form>
                 <div class="alert"><?php if(isset($msg)) { echo $msg;} ?> </div>
             </div>
         </div>
     </div>
 
  <!-- Boostrap JavaScript -->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/all.min.js"></script>
</body>
</html>