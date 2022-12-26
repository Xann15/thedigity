<?php
session_start();

if(isset($_SESSION['login'])) {
    header("location: index.php");
    exit;
}

include 'conn.php';
require 'function.php';

if(isset($_POST['register'])) {
    if(register($_POST) > 0 ) {
        header("location: login.php");
    } else {
        
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <script src="bootstrap.bundle.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
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
        .container { background: rgba(255, 255, 255, 0.2); margin-top:6%; border-radius: 16px; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(2.7px); -webkit-backdrop-filter: blur(2.7px); border: 1px solid rgba(255, 255, 255, 0.3);}

        @media(max-width: 884px) {
            .container { margin-top: 25% }
        }
        @media(max-width: 834px) {
            .container { margin-top: 30% }
        }
        @media(max-width: 800px) {
            .container { margin-top: 35% }
        }
        @media(max-width: 798px) {
            .container { margin-top: 25% }
        }
        @media(max-width:375px) {
            .container { margin-top: 20% }
        }
        @media(max-width: 325px) {
            body { overflow: auto; }
            .container { margin-top: 4%; }
        }
    </style>
</head> 
<body>
    <div class="container col-lg-7 col-11 py-3">
        <h1 class="text-center">SignUp | <span class="text-warning">TheDigity</span></h1>
        <form action="" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="mb-lg-2 mb-1 row">
                <label for="profilePicture" class="col-3 col-form-label">Profile Picture</label>
                <div class="col-9">
                    <img id="prev-image" src="#" alt="choose a image" class="rounded-circle">
                    <input type="file" onchange="readURL(this);"  name="profilePicture" id="profilePicture" class="mt-2 form-control mb-3" autocomplete="off">
                </div>
            </div>
            <div class="mb-lg-2 mb-1 row">
                <label for="username" class="col-3 col-form-label">Username</label>
                <div class="col-9">
                    <input type="text" name="username" id="username" class="form-control mb-3" placeholder="Username" autocomplete="off">
                </div>
            </div>
            <div class="mb-lg-2 mb-1 row">
                <label for="password" class="col-3 col-form-label">Password</label>
                <div class="col-9">
                    <input type="password" name="password" id="password" class="form-control mb-3" placeholder="***" autocomplete="off">
                </div>
            </div>
            <div class="mb-lg-2 mb-1 row">
                <label for="password2" class="col-3 col-form-label">Verify Password</label>
                <div class="col-9">
                    <input type="password" name="password2" id="password2" class="form-control mb-3" placeholder="***" autocomplete="off">
                </div>
            </div>
            <div class="mb-lg-2 mb-1 row">
                <label for="email" class="col-3 col-form-label">Email</label>
                <div class="col-9">
                    <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Email" autocomplete="off" required>
                </div>
            </div>
            <button type="submit" class="btn btn-dark p-1 px-3 rounded-pill mt-3 mb-2 w-100" name="register"><i class="bi bi-person-add"></i> Sign up</button>
            <a href="login.php" class="btn text-primary">already have account?</a> / <a href="javascript:history.back()" class="text-primary btn">back</a>

        </form>
    </div>

    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#prev-image')
                        .attr('src', e.target.result)
                        .width(75)
                        .height(75);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>