<?php
session_start();
include '../conn.php';
require '../function.php';

// logic logout
if(isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    setcookie('key', '', time() - 3600);
    setcookie('num', '', time() - 3600);
    header("location: ../index.php");
}

if(isset($_POST['deleteNews'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM news WHERE id='$id'";
    $query = $conn->query($sql);
    if($query) {
        echo "<script>alert('successfuly deleted news')</script>";
    } else {
        echo "<script>alert('failed to deleted news')</script>";
    }   
}

$result = mysqli_query($conn, "SELECT * FROM news ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News | TheDigity</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <script src="../bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <style>
        @media(max-width: 430px) {
            .nav-item.pc { display: none }
            .upPage {margin-bottom: 40px}
        }
    </style>
</head>
<body onload="load();">
<nav class="nv navbar navbar-pc navbar-expand fixed-top d-flex">
        <div class="col-6">
            <a class="navbar-brand fs-4 fw-bold" href="" style="margin-left: 10px; font-family:sofia">TheDigity</a>
        </div>

        <div class="col-6">
            <ul class="navbar-nav col-8 float-end fs-5 justify-content-evenly">
                <li class="nav-item pc">
                    <a class="nav-link" href="../"><i class="bi bi-house"></i></a>
                </li>
                <li class="nav-item pc">
                    <a class="nav-link" href="../post"><i class="bi bi-phone"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="../menfess" class="nav-link"><i class="bi bi-heart"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-newspaper"></i></a>
                </li>
                <?php if(isset($_SESSION['permission'])) { ?>
                <li class="nav-item pc">
                    <a href="../admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                </li>
                <?php }
                if(isset($_SESSION['super'])) { ?>
                <li class="nav-item pc">
                    <a href="../system.php" class="nav-link"><i class="bi bi-trophy"></i></a>
                </li>
                <?php }
                if(isset($_SESSION['login'])) { ?>
                <div class="dropdown rounded-pill">
                    <button class="btn rounded-pill p-0 px-1 d-flex" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img width="40" height="40" src="../assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle">
                        <p class="h5 text-warning mx-1 my-auto"><?= ucwords($_SESSION['user']) ?></p>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="nav-item">
                            <a href="../dashboard.php?user_profile=<?= $_SESSION['user'] ?>" class="dropdown-item"><i class="bi bi-gear"></i> Setting</a>
                        </li>
                        <li class="nav-item">
                            <form action="" method="post">
                                <button type="submit" name="logout" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                <?php } else { ?>
                <li class="nav-item pc">
                    <a class="nav-link active" aria-current="page" href="../login.php"><i class="bi bi-person-plus-fill"></i> Login</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <nav class="nv p-0 navbar d-lg-none d-md-none navbar-mobile navbar-expand fixed-bottom d-flex">
        <div class="col-12">
            <ul class="navbar-nav col-12 float-end fs-5 justify-content-evenly">
                <li class="nav-item">
                    <a class="nav-link" href="../"><i class="bi bi-house"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../post"><i class="bi bi-phone"></i></a>
                </li>
                <li class="nav-item">
                    <a href="../menfess" class="nav-link"><i class="bi bi-heart"></i></a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-newspaper"></i></a>
                </li>
                <?php if(isset($_SESSION['permission'])) { ?>
                <li class="nav-item">
                    <a href="../admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                </li>
                <?php }
                if(isset($_SESSION['super'])) {?>
                <li class="nav-item">
                    <a href="../system.php" class="nav-link"><i class="bi bi-trophy"></i></a>
                </li>
                <?php }
                if(isset($_SESSION['login'])) {?>
                    <img width="30" height="30" src="../assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle my-auto">                  
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../login.php"><i class="bi bi-person-plus-fill"></i></a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <span class="nv upPage shadow rounded p-2"><a onclick="window.scrollTo(0, 0);"><i class="p-2 bi bi-chevron-double-up"></i></a></span>

    <div class="container mt-5 py-5">
        <h1>TheDigity News</h1>
        <a href="javascript:history.back()" class="btn btn-dark p-0 px-2 rounded-pill"><i class="bi bi-door-open-fill"></i> back</a>
        <?php if(isset($_SESSION['permission'])) { ?>
            <a href="../admin" class="btn btn-dark p-0 px-2 rounded-pill"><i class="bi bi-newspaper"></i> Add new news</a>
        <?php } ?>
        <div class="d-flex col-12 py-5" id="prev-news">
            <?php
                while($row = mysqli_fetch_assoc($result)):
            ?>
                <div class="col-lg-4 m-1 col-md-6 col-12 mb-4">
                    <div class="card shadow-sm" style="height:450px">
                        <img src="../assets/news/assets/<?= $row['backgroundNews'] ?>" class="card-img-top" width="300" height="200" alt="<?= $row['backgroundNews'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['titleNews'] ?></h5>
                            <hr class="m-1">
                            <div class="newsArea">
                                <p class="card-text" style=""><?= $row['news'] ?></p>
                            </div>
                            <div class="card-bottom shadow-sm rounded w-100 px-3" style="position: absolute; left:0; bottom: 0">
                                <a href="?tag=<?= $row['hastag'] ?>" style="text-decoration: none;"><?= $row['hastag'] ?></a>
                                <div class="up d-flex justify-content-between">
                                    <?php if($row['link'] != '') { ?>
                                        <a href="<?= $row['link'] ?>" class="btn btn-dark p-1 rounded-pill my-auto px-3"><i class="bi bi-link-45deg"></i> <?= $row['nameLink'] ?></a>
                                    <?php } ?>
                                    <?php if(isset($_SESSION['permission'])) { ?>
                                        <form action="" method="post">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="deleteNews" class="btn btn-danger p-1 rounded-pill px-3"><i class="bi bi-trash"></i></button>
                                        </form>
                                    <?php } ?>
                                </div>
                                <p class="card-text mb-3"><small class="text-muted">posted by <span class="text-warning fw-bold"><?= $row['postedBy'] ?></span> at <?= time_elapsed_string($row['createdAt']) ?></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="../js/jquery-3.6.1.min.js"></script>
    <script>
        var container = document.getElementById("prev-news");

        function load() {
            $(container).load('../load/news.php');
        }

        setInterval(function() {
            load();
        }, 1000);

        function scrollTop() {
            window.scrollTo(0, 0);
        }
    </script>
    </script>
</body>
</html>