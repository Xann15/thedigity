<?php
session_start();

include '../conn.php';
require '../function.php';
error_reporting(0);

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

// cek apakah yang mengakses adalah admin, jika bukan admin -> tampilkan kode html ini:
if(!isset($_SESSION['permission'])) {
    header("location: ../");
    exit;
}

if(!isset($_SESSION['login'])) {
    header("location: ../");
    exit;
}

// logic logout
if(isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    setcookie('key', '', time() - 3600);
    setcookie('num', '', time() - 3600);
    header("location: ../index.php");
}

if(isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if($op == 'delete') {
    $id     = $_GET['id'];
    $query  = mysqli_query($conn, "DELETE FROM chats WHERE id = '$id'");
    if($query) {
        header("location: ./");
    } else {
        $error = "Failed delete data";
    }
}



// addNews
if(isset($_POST['addNews'])) {
    if(addNews($_POST) > 0) {
        echo "<script>alert('successfully added new news')</script>";
    } else{
        echo "<script>alert('failed to add new news')</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/index.css">
    <script src="../bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <style>
        @media(max-width: 430px) {
            .nav-item.pc { display: none }
            .upPage {margin-bottom: 40px}
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
                    <a class="nav-link" href="../"><i class="bi bi-house"></i></a>
                </li>
                <li class="nav-item pc">
                    <a class="nav-link" href="../post"><i class="bi bi-phone"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="../menfess" class="nav-link"><i class="bi bi-heart"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="../news" class="nav-link"><i class="bi bi-newspaper"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-code-slash"></i></a>
                </li>
                <?php if(isset($_SESSION['super'])) {?>
                <li class="nav-item pc">
                    <a href="../system.php" class="nav-link"><i class="bi bi-trophy"></i></a>
                </li>
                <?php } ?>
                <div class="dropdown rounded-pill">
                    <button class="btn rounded-pill p-0 px-1 d-flex" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img width="40" height="40" src="../assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle">
                        <p class="h5 text-warning mx-1 my-auto"><?= ucwords($_SESSION['user']) ?></p>
                    </button>
                    <ul class="dropdown-menu">
                        <?php if(isset($_SESSION['login'])) { ?>
                        <li class="nav-item">
                            <a href="../dashboard.php?user_profile=<?= $_SESSION['user'] ?>" class="dropdown-item"><i class="bi bi-gear"></i> Setting</a>
                        </li>
                        <?php }
                        if(isset($_SESSION['login'])) { ?>
                        <li class="nav-item">
                            <form action="" method="post">
                                <button type="submit" name="logout" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
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
                    <a href="../news" class="nav-link"><i class="bi bi-newspaper"></i></a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-code-slash"></i></a>
                </li>
                <?php if(isset($_SESSION['super'])) {?>
                <li class="nav-item">
                    <a href="../system.php" class="nav-link"><i class="bi bi-trophy"></i></a>
                </li>
                <?php } ?>
                <img width="30" height="30" src="../assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle my-auto">                  
            </ul>
        </div>
    </nav>

    <span class="nv upPage shadow rounded p-2"><a onclick="window.scrollTo(0, 0);"><i class="p-2 bi bi-chevron-double-up"></i></a></span>


    <h4 class="text-center text-muted py-5 mt-5 mx-2">Hi <span class=" mx-1 text-warning"><?= ucwords($_SESSION['user']) ?></span>, you are admin</h4>
    <div class="container">
        <div class="border rounded d-flex p-2 justify-content-between">
            <h5 class="my-auto">TheDigity</h5>
            <a href="../dashboard.php" class="btn btn-dark p-0 px-2 rounded-pill"><i class="bi bi-box-arrow-right"> back to Dashboard</i></a>
        </div>
        <hr class="my-2">
        <div class="area-chatting" id="area-chatting">
            <?php
                $data = mysqli_query($conn, "SELECT * FROM chats");
                while($r = mysqli_fetch_assoc($data)):
                    if($op == 'edit') {
                        $id = $_GET['id'];
                        if($r['id'] == $id ) {
                        $sql  = mysqli_query($conn, "SELECT * FROM chats WHERE id = '$id'");
                        $qy = mysqli_fetch_assoc($sql);
                        $newChat = $_POST['changeMessage'];
                        $datetime = date('Y:m:d H:i:s');
            ?>
                        <form action="" method="post">
                            <div class="chat-fields col-12 d-flex mb-3">
                                <img src="../assets/userProfile/<?= $r['profile'] ?>" alt="<?= $r['profile'] ?>" class="rounded-circle" height="50" width="50">
                                <div class="m-1"></div>
                                <div class="chatInfo d-flex border col-8 p-2 rounded">
                                    <div class="userMessage w-100">
                                        <span><?= $r['username'] ?></span>
                                        <input type="text" name="changeMessage" id="changeMessage" value="<?= $r['chat'] ?>" placeholder="change message from <?= $r['username'] ?>" class="form-control"></input>
                                    </div>
                                    <div class="time">
                                        <p class="text-muted"><?= time_elapsed_string($r['createdAt']) ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="action justify-content-center d-flex">
                                <button type="submit" class="btn border text-success" style="height: max-content">Save</button>
                                <a href="javascript:history.back()" class="text-warning border p-2 rounded mx-3" style="height: max-content">cancel</a>
                                <a href="?op=delete&id=<?= $r['id'] ?>" class="text-danger border p-2 rounded" style="height: max-content"><i class="bi bi-trash2"></i></a>
                            </div>
                        </form>
                        <?php
                        if($newChat != '') {
                            $sqls = "UPDATE chats SET chat = '$newChat',createdAt = '$datetime' WHERE id = '$id'";
                            $query = mysqli_query($conn, $sqls);
                        }
                    }
                    } else { ?>
                    <div class="chat-fields col-12 p-1 d-flex mb-3">
                        <img src="../assets/userProfile/<?= $r['profile'] ?>" alt="<?= $r['profile'] ?>" class="rounded-circle" height="50" width="50">
                        <div class="m-1"></div>
                        <div class="chatInfo d-flex border p-2 rounded">
                            <div class="userMessage">
                                <span><?= ucwords($r['username']) ?></span>
                                <p class="text-break mb-0"><?= $r['chat'] ?></p>
                            </div>
                            <div class="time">
                                <p class="text-muted"><?= time_elapsed_string($r['createdAt']) ?></p>
                            </div>
                        </div>
                        <div class="action col-lg-3 col-md-3 col-sm-3 col-3 d-flex my-1">
                            <a href="?op=edit&id=<?= $r['id'] ?>" class="text-warning border p-2 rounded mx-1" style="height: max-content"><i class="bi bi-pencil-square"></i></a>
                            <a href="?op=delete&id=<?= $r['id'] ?>" class="text-danger border p-2 rounded" style="height: max-content"><i class="bi bi-trash2"></i></a>
                        </div>
                    </div>
                <?php } ?>
            <?php endwhile; ?>
        </div>
        <hr>

        <section id="addNews" class="py-5">
            <h1 class="text-center text-primary mt-5">Add News</h1>
            <form action="" method="post" class="mt-4" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="titleNews" class="col-sm-1 col-form-label">Title News</label>
                    <div class="col-sm-11">
                        <input type="text" name="titleNews" id="titleNews" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="news" class="col-sm-1 col-form-label">News</label>
                    <div class="col-sm-11">
                        <textarea name="news" id="news" cols="10" rows="5" class="form-control" placeholder="max length 5000" maxlength="5000"></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="link" class="col-sm-1 col-form-label">Link</label>
                    <div class="col-sm-11">
                        <input type="text" name="link" id="link" class="form-control" placeholder="paste your link here">
                        <input type="text" name="nameLink" id="nameLink" class="mt-1 form-control" placeholder="name of your link">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="hastag" class="col-sm-1 col-form-label">Hastag</label>
                    <div class="col-sm-11">
                        <input type="text" name="hastag" id="hastag" class="form-control" placeholder="hastag">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="backgroundNews" class="col-sm-1 col-form-label">Background Images</label>
                    <div class="col-sm-11">
                        <img id="prev-image" width="50" src="" alt="your images">
                        <input type="file" onchange="readURL(this);" name="backgroundNews" id="backgroundNews" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <input type="submit" name="addNews" value="Add new news" class="btn btn-success">
                    </div>
                </div>
            </form>
        </section>

    </div>

    <script>
        function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev-image')
                            .attr('src', e.target.result)
                            .width(300)
                            .height(150);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

        var chatScroll = document.getElementById("area-chatting");
        
        chatScroll.scrollBy(0, 9999999999999999999);

        function scrollTop() {
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>