<?php
define('PAGE', 'adminlogin.php');
include('../dbcon.php');
session_start();
if (!isset($_SESSION['radmin'])) {
    if (isset($_REQUEST['rsignin'])) {
        $remail = mysqli_real_escape_string($conn, trim($_REQUEST['remail']));
        $rpassword = mysqli_real_escape_string($conn, trim($_REQUEST['rpassword']));
        $sql = "SELECT email, password FROM adminlogin WHERE email='" . $remail . "' AND password='" . $rpassword . "' LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $_SESSION['radmin'] = true;
            $_SESSION['remail'] = $remail;
            echo "<script>location.href='dashboard.php';</script>";
            exit;
        } else {
            $msg = "Enter valid email and password";
        }
    }
} else {
    echo "<script>location.href='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../image/10.png" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../css/all.min.css">

    <title>Admin Login</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-size: 24px;
            color: #343a40;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-container .form-group label {
            font-weight: bold;
            color: #495057;
        }

        .form-container .form-control {
            border-radius: 8px;
            padding: 10px;
        }

        .form-container .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }

        .form-container .btn:hover {
            background: #007bff;
            color: white;
        }

        .form-container .alert {
            margin-top: 15px;
            color: red;
            font-size: 14px;
        }

        .text-center h2 {
            font-size: 28px;
            color: #343a40;
            font-weight: bold;
        }

        @media only screen and (min-width: 1080px) {
            body {
                background-image: url("../image/4.png");
                background-size: cover;
                background-position: center;
            }
        }

        @media only screen and (max-width: 1080px) {
            body {
                background-image: url("../image/4.jpg");
                background-size: cover;
                background-position: center;
            }
        }

        @media only screen and (max-width: 400px) {
            body {
                background-image: url("../image/phone3.jpg");
                background-size: cover;
                background-position: center;
            }
        }
    </style>
</head>

<body>
    <div class="text-center mt-5">
        <h2>Admin Login Area</h2>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-sm-6 col-md-4 form-container">
                <h2 class="text-center">Login</h2>
                <form action="" method="POST" id="myForm">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user mr-2"></i>Email</label>
                        <input type="text" name="remail" placeholder="Enter email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-key mr-2"></i>Password</label>
                        <input type="password" name="rpassword" placeholder="Enter password" class="form-control" required>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-block" name="rsignin">Login</button>
                    </div>
                </form>
                <?php if (isset($msg)) { ?>
                    <div class="alert text-center"><?php echo $msg; ?></div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/all.min.js"></script>
</body>

</html>
