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

    $q = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    $qr = mysqli_fetch_assoc($q);
    $profile = $qr['profilePicture'];
    $username = $qr['username'];

    $rd = mysqli_query($conn, "SELECT username FROM users WHERE role = 'admin'");
    $rds = mysqli_fetch_assoc($rd);

    if($key === hash('sha256', $rds['username'])) {
        $_SESSION['permission'] = true;
    }

    //? cek cookie dan username
    if($key === hash('sha256', $row['username'])) {
        $_SESSION['profile'] = $profile;
        $_SESSION['user'] = strtolower($username);
        $_SESSION['login'] = true;
    }
}

if(isset($_SESSION['login'])) {
    header('location: dashboard.php'); exit;
}

/*
    seleksi hanya format gambar jpg png jpeg saja yang bisa di input di signup page ( FIXED )
    seleksi gambar format valid profile picture
    set session login -> password bcrypt ( FIXED )
    alert showing max 5 message kalau belum login ( FIXED )
    bikin navbar menu & pindahin profile setting ke navbar ( FIXED )
    USER YANG BELUM LOGIN BISA MELIHAT CHAT, TAPI GABISA NGECHAT ( FIXED )
    bikin hak akses button delete untuk admin ( FIXED )
    fix userProfile pas login ke set Profile nya ga cuma signup doang ( FIXED )
    Change function system belum ( FIXED )
    ADMIN PAGE LOGOUT ( FIXED )
    FIX PROFILE PAGE ( FIXED )
    bikin section news
    bikin halaman admin untuk control news
    fitur lampirkan file / gambar
    TOMBOL GET SESSION PROFILE PICTURES supaya gaperlu re-login
    multi user role
    Sign up with google account, use google api
*/

// cek apakah user sudah login apa belum && kalau belum -> tampilkan kode html ini:
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TheDigity</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link rel="shortcut icon" href="logo.png" type="image/x-icon">
        <link rel="stylesheet" href="./assets/index.css">
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
                <li class="nav-item pc">
                    <a class="nav-link active" aria-current="page" href="login.php"><i class="bi bi-person-plus-fill"></i> Login</a>
                </li>
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
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="login.php"><i class="bi bi-person-plus-fill"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <span class="nv upPage shadow rounded p-2"><a onclick="window.scrollTo(0, 0);"><i class="p-2 bi bi-chevron-double-up"></i></a></span>

    <div class="col-11 mt-5 pt-5  mx-auto">
        <p class="text-center display-6">Hi There, Welcome to <span class="fw-bold">TheDigity</span></p>
        <p class="p-2 bg-info bg-opacity-10 border border-info border-start-0 border-end-0"><i class="bi bi-info-lg"></i> oop's it seems you are not logged in <a href="login.php"> login now</a></p>
    </div>
    
    <section id="content">
            <div class="container chat-area rounded mt-5 mb-5">
                <div class="d-flex navbar-chat justify-content-between border rounded px-2 py-2 mt-2">
                    <h5 class="my-auto fw-bold">TheDigity</h5>
                </div>
                <hr class="my-2">
                <div id="area-chating">
                    <div class="area-chatting shadow-sm rounded">
                        <?php
                            $numChat = $conn->query("SELECT * FROM chats");
                            $data = $conn->query("SELECT * FROM chats LIMIT 10");
                            $showing = mysqli_num_rows($data);
                            $all = mysqli_num_rows($numChat);
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

                        <p class="p-2 bg-info bg-opacity-10 border border-info border-start-0 border-end-0"><i class="bi bi-info-lg"></i> Login to be able to use TheDidity features, showing <span class="text-warning"><?= $showing ?></span> of <span class="text-warning"><?= $all ?></span> message's, <a href="login.php"> Login</a></p>


                        <!-- Area inputan pesan chatting -->
                        <form action="" name="sendChating" method="post" class="d-flex py-1 col-lg-12 col-md-12 col-11 col-sm-12">
                            <div class="col-lg-10 col-md-9 col-8 my-auto mx-lg-2 mx-lg-2 mx-0">
                                <input type="text" name="chatText" id="chatText" disabled class="form-control rounded-pill" autocomplete="off" maxlength="5000" placeholder="login to be able send message"></input>
                            </div>
                            <input type="file" name="file" id="file" disabled style="display:none;">
                            <label for="file" class="border p-2 fs-5 mx-1 px-3 rounded-circle" style="height:50px; width:50px"><i class="bi bi-link-45deg"></i></label>
                            <button type="submit" id="send" name="send" class="disabled btn btn-primary rounded-circle" style="height:50px; width:50px"><i class="bi bi-send"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

</body>
</html>