<?php
session_start();

// logic logout
if(isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    setcookie('key', '', time() - 3600);
    setcookie('num', '', time() - 3600);
    header("location: ../index.php");
}
include '../conn.php';

$data = $conn->query("SELECT * FROM menfess");

function addMenfess($data) {
    global $conn;
    
    $profile = 'anonymous.jpg';
    $to = htmlspecialchars( $_POST['messageTo']);
    $message = htmlspecialchars($_POST['message']);

    if(empty(trim($to && $message))) {
        echo "<script>alert('Cannot send empty fields')</script>";
        return false;
    }

    $conn->query("INSERT INTO menfess(profile, messageFrom, messageTo, message) VALUES('$profile','Anonymous Message','$to','$message')");

    return mysqli_affected_rows($conn);
}

if(isset($_POST['addMenfess'])) {
    if(addMenfess($_POST) > 0) {
        echo "<script>alert('Berhasil membuat pesan')</script>";
        header('refresh: 0; url=');exit;
    } else {
        echo "<script>alert('Gagal membuat pesan')</script>";
        header('refresh: 0; url=');exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menfess</title>
    <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/index.css">
    <link rel="stylesheet" href="../bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="../bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <style>
        .menfess-area {
            background: rgb(248,249,250);
            height: 61vh;
            overflow: auto;
            overflow-x: hidden;
        }
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
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-heart-fill"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="../news" class="nav-link"><i class="bi bi-newspaper"></i></a>
                </li>
                <?php if(isset($_SESSION['permission'])) { ?>
                <li class="nav-item pc">
                    <a href="../admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                </li>
                <?php }
                if(isset($_SESSION['super'])) {?>
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
                    <a href="" class="nav-link active" aria-current="page"><i class="bi bi-heart-fill"></i></a>
                </li>
                <li class="nav-item">
                    <a href="../news" class="nav-link"><i class="bi bi-newspaper"></i></a>
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

    <div class="col-11 mt-5 pt-5 mx-auto">
        <?php if(isset($_SESSION['login'])) { ?>
            <p class="text-center display-6">Hi <span class="text-warning"><?= ucwords($_SESSION['user']) ?></span>, Welcome to Menfess XRPL</p>
        <?php } else { ?>
            <p class="text-center display-6">Hi There, Welcome to Menfess XRPL</p>
        <?php } ?>
        <p class="display-6 fs-5 text-center">Send anonymous messages or messages to your crush</p>
    </div>

    <div class="container py-5 mt-5">
        <div class="border rounded d-flex p-2 justify-content-between">
            <h5 class="my-auto">Menfess</h5>
            <a href="#menfess" class="btn btn-dark p-0 px-3 rounded-pill"><i class="bi bi-box-arrow-right"> Buat Menfess</i></a>
        </div>

        <hr class="my-2">
        <section id="prev-menfess">
            <div class="menfess-area col-12 border rounded p-3" id="menfess-area">
                <?php while($r = mysqli_fetch_assoc($data)): ?>
                    <div id="menfess-fields" class="menfess-fields col-12 mx-lg-2 d-flex mb-3">
                        <img src="<?= $r['profile'] ?>" alt="<?= $r['profile'] ?>" class="rounded-circle" height="50" width="50">
                            <div class="mx-1"></div>
                            <div class="menfessInfo d-flex border border-primary shadow-sm p-2 rounded">
                                <div class="userMessage">
                                    <span class="fw-bold"><?= ucwords($r['messageFrom']) ?></span>
                                    <p class="mb-1">To: <span class="fw-bold text-primary"><?= ucwords($r['messageTo']) ?></span></p>
                                    <p class="mb-1">Message: <span class="text-break mb-0"><?= $r['message'] ?></span></p>
                                </div>
                            </div>
                        </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section id="menfess" class="py-5">
            <h1 class="text-center display-5 text-primary mt-5">Buat Menfess</h1>
            <form id="sendMenfess" action="" method="post" class="mt-4" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                <div class="mb-3 row">
                    <label for="messageTo" class="col-sm-1 col-form-label">To</label>
                    <div class="col-sm-11">
                        <input type="text" name="messageTo" id="messageTo" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="message" class="col-sm-1 col-form-label">Message</label>
                    <div class="col-sm-11">
                        <textarea name="message" id="message" cols="10" rows="5" class="form-control" placeholder="max length 5000" maxlength="5000"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <input type="button" name="addMenfess" id="addMenfess" value="Kirim Menfess" class="btn btn-success">
                    </div>
                </div>
            </form>
        </section>

    </div>

    <script src="../js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript">
        var container = document.getElementById("menfess-area");

        $('#addMenfess').on('click', function() {
            var data = $('#sendMenfess').serialize()+'&addMenfess=addMenfess';
            $.ajax({
                url: 'index.php',
                type: "POST",
                data: data,
                
            });

            $("#sendMenfess")[0].reset();
        });

        function load() {
            $(container).load('../load/menfess.php');
        }

        setInterval(function() {
            load();
        }, 1000);

        function scrollTop() {
            window.scrollTo(0, 0);
        }

    </script>

</body>
</html>