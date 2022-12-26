<?php
session_start();
include 'conn.php';

if(!isset($_SESSION['super'])) {
    header("location: dashboard.php");
}

// logic logout
if(isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    setcookie('key', '', time() - 3600);
    setcookie('num', '', time() - 3600);
    header("location: login.php");
}

$users = $conn->query("SELECT * FROM users");
error_reporting(0);
$i =  1;


//? Functions
function addRole($users) {
    global $conn;
    $username = $_POST['username'];
    $role = $_POST['role'];
    if(empty(trim($username))) {
        echo "<script>alert('select the person you want to change')</script>";
        return false;
    }
    $conn->query("UPDATE users SET role = '$role' WHERE username = '$username'");
    return mysqli_affected_rows($conn);


}



//! Search users
if(isset($_POST['searchUsers'])) {
    $keyword = $_POST['keyword'];
    if($keyword != '') {
        $users = $conn->query("SELECT * FROM users WHERE username LIKE '%".$keyword."%' OR email LIKE '%".$keyword."%'");
    } else {
        $users = $users;
    }
}

//? Deleted Users

if(isset($_POST['delete'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM users WHERE id = $id");
    echo "<script>alert('Successfuly deleted users')</script>";
    header("refresh:0;");
}

if(isset($_POST['edit'])) {
    $id = $_POST['id'];
    $getUser = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    $r = mysqli_fetch_array($getUser);
    $username = $r['username'];
    $role = $r['role'];
}

if(isset($_POST['addRole'])) {
    if(addrole($_POST) > 0) {
        echo "<script>alert('Successfuly add role')</script>";
        header('refresh:0; url=');
    } else {
        echo "<script>alert('Something Error :P')</script>";
        header('refresh:0;url=');
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <script src="bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <style>

        /* width */
        ::-webkit-scrollbar {
            width: 4px!important;
            height: 4px!important;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: rgb(225,225,225);
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: rgb(200,200,200);
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        * { scroll-behavior: smooth; }

        .nv { background: rgb(248,249,249) }

        .upPage {
            position:fixed;
            bottom: 0; right: 0;
            margin: 30px;
            z-index:99;
        }

        @media(max-width: 430px) {
            .nav-item.pc { display: none }
            .upPage {margin-bottom: 40px}
        }

        @media(max-width: 325px) {
            .upPage { margin: 20px }
        }
    </style>
</head>
<body>
    <nav class="nv navbar navbar-pc navbar-expand fixed-top d-flex">
        <div class="col-6">
            <a class="navbar-brand fs-4 fw-bold" href="" style="margin-left: 10px; font-family:sofia">TheDigity</a>
        </div>

        <div class="col-6">
            <ul class="navbar-nav col-8 float-end fs-5 justify-content-evenly">
                <li class="nav-item pc">
                    <a class="nav-link" href="./"><i class="bi bi-house"></i></a>
                </li>
                <li class="nav-item pc">
                    <a class="nav-link" href="./post"><i class="bi bi-phone"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="./menfess" class="nav-link"><i class="bi bi-heart"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="./news" class="nav-link"><i class="bi bi-newspaper"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="./admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-trophy-fill"></i></a>
                </li>
                <div class="dropdown rounded-pill">
                    <button class="btn rounded-pill p-0 px-1 d-flex" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img width="40" height="40" src="assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle">
                        <p class="h5 text-warning mx-1 my-auto"><?= ucwords($_SESSION['user']) ?></p>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="nav-item">
                            <a href="./dashboard.php?user_profile=<?= $_SESSION['user'] ?>" class="dropdown-item"><i class="bi bi-gear"></i> Setting</a>
                        </li>
                        <li class="nav-item">
                            <form action="" method="post">
                                <button type="submit" name="logout" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
    </nav>

    <nav class="nv p-0 navbar d-lg-none d-md-none navbar-mobile navbar-expand fixed-bottom d-flex">
        <div class="col-12">
            <ul class="navbar-nav col-12 float-end fs-5 justify-content-evenly">
                <li class="nav-item">
                    <a class="nav-link" href="./"><i class="bi bi-house"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./post"><i class="bi bi-phone"></i></a>
                </li>
                <li class="nav-item">
                    <a href="./menfess" class="nav-link"><i class="bi bi-heart"></i></a>
                </li>
                <li class="nav-item">
                    <a href="./news" class="nav-link"><i class="bi bi-newspaper"></i></a>
                </li>
                <li class="nav-item">
                    <a href="./admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-trophy"></i></a>
                </li>
                <img width="30" height="30" src="assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle my-auto">                  
            </ul>
        </div>
    </nav>
    <span class="nv upPage shadow rounded p-2"><a onclick="window.scrollTo(0, 0);"><i class="p-2 bi bi-chevron-double-up"></i></a></span>

    <!-- Main -->
    <div class="container py-5">
        <p class="display-5 text-center text-primary mt-4">Welcome Boss</p>
        
        <section id="getRole" class="py-5">
            <div class="tableUser">
                <p class="display-6">Users</p>
                <form class="d-flex" role="search" action="" method="POST">
                    <input class="form-control me-2" name="keyword" type="search" value="<?= $keyword ?>" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-primary" name="searchUsers" type="submit">Search</button>
                </form>
                <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr class="text-center align-middle fw-bold">
                        <td>#</td>
                        <td>Profile Picture</td>
                        <td>Username</td>
                        <td>Role</td>
                        <td>Email</td>
                        <td style="width:15%">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($users)): ?>
                    <tr class="text-center align-middle">
                        <td><?= $i++ ?></td>
                        <td><img src="assets/userProfile/<?= $row['profilePicture'] ?>" alt="<?= $row['profilePicture'] ?>" width="60" height="60" class="rounded-circle"></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['role'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="edit" value="Edit" class="text-info btn btn-dark rounded-pill p-1 px-3"><i class="bi bi-pencil-square"></i></button>
                                <button type="submit" name="delete" value="Delete" class="text-warning btn btn-dark rounded-pill p-1 px-3"><i class="bi bi-trash "></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </section>

        <section id="setRole" class="py-5">
            <div class="container">
                <p class="display-6">Set Role Users</p>

                <form action="" method="post">
                    <div class="mb-3 row">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="username" id="username" placeholder="username" autocomplete="off" class="form-control" value="<?= $username ?>">
                        </div>
                    </div>
                    <div class="mb-3 row"> 
                        <label for="role" class="col-sm-2 col-form-label">Set Role to</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="role" id="role" value="<?= $role ?>">
                                <option value="user">- Choose Role -</option>
                                <option value="user" <?php if($role === "user") echo "selected"?>>user
                                </option>
                                <option value="admin" <?php if($role === "admin") echo "selected"?>>admin
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="addRole" class="btn btn-dark rounded-pill p-1 px-3">Set Role</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</head>
<body>
    
</body>
</html>