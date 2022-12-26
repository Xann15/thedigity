<?php
// 404 page
session_start();

if(!isset($_SESSION['login'])) {
    header("location: index.php");
}


// logic logout
if(isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    setcookie('key', '', time() - 3600);
    setcookie('num', '', time() - 3600);
    header("location: index.php");
}



// import file
include 'conn.php';
require 'function.php';
error_reporting(0);




// logic if user send message -> (function.php)
if(isset($_POST['send'])) {
    $chatText = htmlspecialchars($_POST['chatText']);

    // ?Jika chat field kosong
    if(empty(trim($chatText))) {
        ?>
        <div class="alert alert-danger fixed-top mt-5" role="alert">cant sent null message <button type="button" class="float-right btn-close btn btn-close-white" name="close-alert"  data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php
        return false;
    }
    // ?Get waktu sekarang
    $datetime = date("Y-m-d H:i:s");

    $profile = $_SESSION['profile'];
    $username = $_SESSION['user'];

    //? cek yang chat admin atau bukan
    if(isset($_SESSION['permission'])) { 
        $role = "admin";
    } else {
        $role = "user";
    }
    
    $query = mysqli_query($conn, "INSERT INTO chats(role, username, profile, chat, createdAt) VALUES('$role', '$username', '$profile', '$chatText', '$datetime')");
    if($query) {
        echo "Success";
    } else {
        echo "Gagal";
    }
}


$getRole = mysqli_query($conn, "SELECT * FROM users");
 
$allNews = mysqli_query($conn, "SELECT * FROM news");                    
$numNews = mysqli_num_rows($allNews);
$data = mysqli_query($conn, "SELECT * FROM chats");

if(isset($_POST['searchChat'])) {
    $keywordsChat = $_POST['keywordsChat'];
    if($keywordsChat != '') {
        $data = mysqli_query($conn, "SELECT * FROM chats WHERE chat LIKE '%$keywordsChat%'");
    } else {
        $data;
    }
}

// PROCESSED
if(isset($_GET['user_profile'])) {
    $user_profile = $_GET['user_profile'];
} else {
    $user_profile = "";
}

$imageName = $_FILES['profilePicture']['name'];
$imageError = $_FILES['profilePicture']['error'];
$dir = 'assets/userProfile/';
// logic change profile 
if(isset($_POST['changeProfile'])) {
    $oldUsername = $_POST['oldUsername'];
    $username = $_POST['username'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$oldUsername'");
    
    if(mysqli_num_rows($result) === 1 ){
        
        $row = mysqli_fetch_assoc($result);
        $images = $row['profilePicture'];

        if($imageError === 4) {
            $imageName = $images;
        }

        if(password_verify($oldPassword, $row['password'])){
            $password = password_hash($newPassword, PASSWORD_DEFAULT);

            move_uploaded_file($_FILES['profilePicture']['tmp_name'], "$dir/$imageName");
            mysqli_query($conn, "UPDATE users SET profilePicture= '$imageName', username = '$username', password = '$password' WHERE username = '$oldUsername'");
            ?>
            <div class="alert alert-success" role="alert">Berhasil mengubah profile</div>
            <?
            header("refresh:3;");
        } else {
            ?>
            <div class="alert alert-danger" role="alert">Wrong password</div>
            <?php
            header("refresh:3; url=");
        }
    } else {
        ?>
        <div class="alert alert-danger" role="alert">Undefinded users</div>
        <?php
        header("refresh:3; url=");
    }

}


// menampilkan profile user settings
if($user_profile == $_SESSION['user']) {
        ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link rel="shortcut icon" href="logo.png" type="image/x-icon">
        <link rel="stylesheet" href="./assets/index.css">
        <script src="bootstrap.bundle.min.js"></script>
        <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    </head>
    <body>
        <h1 class="m-3 mt-4 m-lg-5">Settings <span class="text-warning"><?= ucwords($_SESSION['user']) ?></span></h1>
        <div class="container mt-4 profile-area p-2">
            <form action="" method="post" enctype="multipart/form-data">
                <p class="p-2 bg-info bg-opacity-10 border border-info border-start-0 border-end-0"><i class="bi bi-info-lg"></i> if you change the profile picture, re-login so that your profile picture is updated</p>
                <div class="row">
                    <label for="profilePictures" class="col-sm-2 col-form-label">Profile</label>
                    <div class="col-sm-9">
                        <img id="prev-image" width="100" height="100" src="assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>" class="rounded-circle">
                        <input type="file" onchange="readURL(this);" name="profilePicture" id="profilePicture" class="mt-2 form-control mb-3" value="<?php echo $profilePicture ?>">
                    </div>
                </div>
                <div class="row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="oldUsername" id="oldUsername" value="<?= $_SESSION['user'] ?>">
                        <input type="text" name="username" id="username" class="form-control mb-3" value="<?= $_SESSION['user'] ?>">
                    </div>
                </div>
                <div class="row">
                    <label for="password" class="col-sm-2 col-form-label">Change Password</label>
                    <div class="col-sm-9">
                        <input type="text" name="oldPassword" id="oldPassword" class="form-control mb-3" placeholder="old password" autocomplete="off">
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-9">
                        <input type="text" name="newPassword" id="newPassword" class="form-control mb-3" placeholder="new password" autocomplete="off">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <input type="submit" name="changeProfile" value="Change" class="col-3 btn btn-warning">
                    <a href="dashboard.php" class="col-3 mx-2 btn btn-primary">back</a>
                    <input type="submit" name="logout" value="Logout" class="col-3 btn btn-danger">
                </div>
            </form>
        </div>

        <script type="text/javascript">
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev-image')
                            .attr('src', e.target.result)
                            .width(100)
                            .height(100);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </body>
    </html>
    <?php
    exit;
}
?>



<!-- Main Code (tampilan jika user sudah login) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./assets/index.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="bootstrap.bundle.min.js"></script>
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
                    <a class="nav-link active" aria-current="page" href=""><i class="bi bi-house-fill"></i></a>
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
                <?php if(isset($_SESSION['permission'])) { ?>
                <li class="nav-item pc">
                    <a href="./admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                </li>
                <?php }
                if(isset($_SESSION['super'])) {?>
                <li class="nav-item pc">
                    <a href="system.php" class="nav-link"><i class="bi bi-trophy"></i></a>
                </li>
                <?php } ?>
                <div class="dropdown rounded-pill">
                    <button class="btn rounded-pill p-0 px-1 d-flex" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img width="40" height="40" src="assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle">
                        <p class="h5 text-warning mx-1 my-auto"><?= ucwords($_SESSION['user']) ?></p>
                    </button>
                    <ul class="dropdown-menu">
                        <?php if(isset($_SESSION['login'])) { ?>
                        <li class="nav-item">
                            <a href="?user_profile=<?= $_SESSION['user'] ?>" class="dropdown-item"><i class="bi bi-gear"></i> Setting</a>
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
                    <a class="nav-link active" aria-current="page" href=""><i class="bi bi-house-fill"></i></a>
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
                <?php if(isset($_SESSION['permission'])) { ?>
                <li class="nav-item">
                    <a href="./admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                </li>
                <?php }
                if(isset($_SESSION['super'])) {?>
                <li class="nav-item">
                    <a href="system.php" class="nav-link"><i class="bi bi-trophy"></i></a>
                </li>
                <?php } ?>
                <img width="30" height="30" src="assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle my-auto">                  
            </ul>
        </div>
    </nav>

    <span class="nv upPage shadow rounded p-2"><a onclick="window.scrollTo(0, 0);"><i class="p-2 bi bi-chevron-double-up"></i></a></span>

        <div class="col-10 mt-5 pt-5 mx-auto">
        <p class="text-center display-6">hi <span class="text-warning"><?= ucwords($_SESSION['user'])?></span>, Welcome to <span class="fw-bold">TheDigity</span></p>
        </div>

        <section id="news" class="mt-5">
            <div class="col-lg-6 col-md-6 col-sm-12 mx-auto">

                <!-- HEADLINE NEWS -->
                <div id="carousel" class="carousel slide border border rounded p-1 shadow-sm" data-bs-ride="true">
                    <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <?php for ($i=1; $i <= $numNews; $i++) {
                            ?>
                            <button type="button" data-bs-target="#carousel" data-bs-slide-to="<?= $i ?>" aria-label="Slide <?= $i ?>"></button>
                            <?php
                        } ?>
                    </div>
                    <div class="carousel-inner rounded">
                        <div class="carousel-item active" data-bs-interval="5000">
                            <img src="assets/news.png" class="rounded w-100 newsImage" alt="news" loading="lazy">
                            <div class="layer rounded">
                            </div>
                        </div>
                        <?php
                            $dataNews = mysqli_query($conn, "SELECT * FROM news ORDER BY id DESC");
                            while($row = mysqli_fetch_array($dataNews)):
                                if(strlen($row['news']) > 40) {
                                    $row['news'] = substr($row['news'], 0, 40) . '....';
                                }
                                ?>
                        <div class="carousel-item" data-bs-interval="5000">
                            <img src="assets/news/assets/<?= $row['backgroundNews'] ?>" class="newsImage rounded w-100" alt="<?= $row['backgroundNews'] ?>" loading="lazy">
                            <div class="layer">
                                <div class="carousel-caption py-0">
                                    <h3><?= $row['titleNews'] ?></h3>
                                    <p><?= $row['news'] ?></p>
                                    <div class="link">
                                        <a href="news/" class="text-white">readmore news..</a>
                                        /
                                        <a href="<?= $row['link'] ?>" class="text-white"><?= $row['nameLink'] ?></a>
                                        <p>posted by <span class="text-warning fw-bold"><?= $row['postedBy'] ?></span> at <?= time_elapsed_string($row['createdAt']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <a href="news/" class="btn btn-dark p-0 px-3 mt-1 mx-2 text-center rounded-pill"><i class="bi bi-newspaper"></i> read more news</a>
            </div>
        </section>

        <section id="content">
            <div class="container chat-area rounded mt-5 mb-5">
                <div class="d-flex navbar-chat justify-content-between border rounded px-2 py-2 mt-2">
                    <h5 class="my-auto fw-bold">Universal Chat</h5>
                </div>
                <hr class="my-2">
                    <div class="shadow-sm rounded">
                        <div class="area-chatting">
                            <div id="area-chating">
                                <?php
                                    while($r = mysqli_fetch_assoc($data)):
                                        if($r['role'] == 'admin') { // jika admin yang mengirim pesan
                                ?>
                                        <div id="chat-fields" class="chat-fields col-11 mx-2 d-flex mb-3">
                                        <img src="assets/userProfile/<?= $r['profile'] ?>" alt="<?= $r['profile'] ?>" class="rounded-circle" height="50" width="50">
                                            <div class="m-1"></div>
                                            <div class="chatInfo d-flex border border-primary shadow-sm p-2 rounded">
                                                <div class="userMessage">
                                                    <span class="text-primary fw-bold"><?= ucwords($r['username']) ?></span>
                                                    <p class="text-break mb-0"><?= $r['chat'] ?></p>
                                                </div>
                                                <div class="time">
                                                    <p class="text-muted"><?= time_elapsed_string($r['createdAt']) ?></p>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } else { // jika user biasa yang mengirim pesan ?>
                                    <div id="chat-fields" class="chat-fields mx-2 col-11 d-flex mb-3">
                                        <img src="assets/userProfile/<?= $r['profile'] ?>" alt="<?= $r['profile'] ?>" class="rounded-circle" height="50" width="50">
                                        <div class="m-1"></div>
                                        <div class="chatInfo d-flex border shadow-sm p-2 rounded">
                                            <div class="userMessage">
                                                <span class="fw-bold"><?= ucwords($r['username']) ?></span>
                                                <p class="text-break mb-0"><?= $r['chat'] ?></p>
                                            </div>
                                            <div class="time">
                                                <p class="text-muted"><?= time_elapsed_string($r['createdAt']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }  ?>
                                <?php endwhile; ?>
                                
                                <p class="p-4"></p>
                            </div>
                        </div>

                        <div class="send">
                            <!-- Area inputan pesan chatting -->
                            <form action="" method="post" class="d-flex py-1 col-lg-12 col-md-12 col-11 col-sm-12" id="sendForm" onkeydown="return event.key != 'Enter';">
                                <div class="col-lg-10 col-md-9 col-8 my-auto mx-lg-2 mx-lg-2 mx-0">
                                    <input type="text" name="chatText" id="chatText" class="form-control rounded-pill" autocomplete="off" maxlength="5000"></input>
                                </div>
                                <input type="file" name="chatFile" id="chatFile" style="display:none;">
                                <label for="chatFile" class="border p-2 fs-5 mx-1 px-3 rounded-circle" style="height:50px; width:50px"><i class="bi bi-link-45deg"></i></label>
                                <button type="button" id="sendMessage" name="send" class="btn btn-primary rounded-circle" style="height:50px; width:50px"><i class="bi bi-send"></i></button>
                            </form>
                        </div>
                    </div>
            </div>
        </section>

        <div class="py-5 my-5"></div>
    
    <script src="js/jquery-3.6.1.min.js"></script>
    <script>
        var containerChat = document.getElementById("area-chating");
    
        $('#sendMessage').on('click', function() {
            var data = $('#sendForm').serialize()+'&send=send';
            $.ajax({
                url: '',
                type: "POST",
                data: data,
                
            });

            $("#sendForm")[0].reset();
        });


        function load() {
            $(containerChat).load('load/chat.php');
        }

        setInterval(function() {
            load();
        }, 1000);

        function scrollTop() {
            window.scrollTo(0, 0);
        }

    </script>
    <script src="./js/script.js"></script>
</body>
</html>