<?php
session_start();

include 'conn.php';
require 'function.php';


//? cek cookie

if(isset($_COOKIE['num']) && isset($_COOKIE['key']))  {
    $id = $_COOKIE['num'];
    $key = $_COOKIE['key'];
    $query = mysqli_query($conn, "SELECT username FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($query);

    //? cek cookie dan username
    if($key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
    }
}


if(isset($_SESSION['login'])) {
    header("location: index.php");
    exit;
}

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    $r = mysqli_query($conn, "SELECT * FROM chats WHERE username = '$username'");
    $rr = mysqli_fetch_assoc($r);

    if(mysqli_num_rows($result) === 1 ) {
        $row = mysqli_fetch_assoc($result);
        $profile = $row['profilePicture'];
        $role = $row['role'];

        if($role == 'superAdmin') {
            $_SESSION['super'] = true;
            $_SESSION['permission'] = true;
        }

        if($role == 'admin') {
            $_SESSION['permission'] = true;
        }

        if(password_verify($password, $row['password'])){
            $_SESSION['profile'] = $profile;
            $_SESSION['login'] = true;
            $_SESSION['user'] = strtolower($username);

            // cookie
            if(isset($_POST['remember'])) {
                setcookie('num', $row['id'], time() + 86400); //? set cookie 24hours
                setcookie('key', hash('sha256', $row['username']), time() + 86400); //? set cookie 24hours
            }
            header("location: dashboard.php");
            exit;
        } else {
            ?>
            <div class="alert alert-danger" role="alert">Username or passwword incorret</div>
            <?php
        }
    } else {
        ?>
        <div class="alert alert-danger" role="alert">Undefinded users</div>
        <?php
    }

    if(empty(trim($username))) {
        ?>
        <div class="alert alert-danger" role="alert">Please enter your username</div>
        <?php
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="bootstrap.bundle.min.js"></script>
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 6px!important;
            height: 6px!important;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: rgb(222,222,222);
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background-color: rgb(65,65,65);
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #333;
        }

        * { scroll-behavior: smooth; }

        body{ background: url('assets/background.jpg'); background-repeat: no-repeat; height:100vh; background-size: cover; overflow: hidden;}
        .container { background: rgba(255, 255, 255, 0.2); margin-top: 15%; border-radius: 16px; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(2.7px); -webkit-backdrop-filter: blur(2.7px); border: 1px solid rgba(255, 255, 255, 0.3);}

        input[type="checkbox"]{display:none;}

        .checkbox {
            cursor: pointer;
            color: #fefefe;
            transform: translate(15%, 0%);
        }

        .checkbox::before {
            content: ''; 
            position: absolute;
            width: 15px; height:15px;
            border: 1px solid;
            transition: all 0.3s ease;
            transform: translate(-150%, 40%);
        }

        input[type="checkbox"]:checked + label:before {
            border-color: transparent;
            border-left-color: #7dce13;
            border-bottom-color: #7dce13;
            transform: translate(-150%, 80%)rotate(-50deg);
            width: 15px; height:8px;
        }
    </style>
</head>
<body>
    <div class="container col-lg-6 col-11 py-3">
        <h1 class="text-center">Login | <span class="text-warning">TheDigity</span></h1>
        <form action="" method="post" class="mt-4">
            <div class="mb-1 row">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="username" id="username" class="form-control mb-3" placeholder="Username">
                </div>
            </div>
            <div class="mb-1 row">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" id="password" class="form-control mb-3" placeholder="***">
                </div>
            </div>
            <div class="mb-1 d-flex mx-2">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" class="checkbox text-dark mx-2">Remember me</label>
            </div>
            <button type="submit" name="login" class="btn btn-dark p-1 px-3 rounded-pill mt-3 mb-2 w-100"><i class="bi bi-person-add"></i> Login</button>
            <div class="check-row">
                <a href="signup.php" class="btn text-primary">not have any account?</a> / <a href="javascript:history.back()" class="text-primary btn">back</a>
            </div>
        </form>
    </div>
</body>
</html>