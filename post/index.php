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


$data = $conn->query("SELECT * FROM postingan ORDER by id_postingan DESC");

function posting($data) {
    global $conn;

    $fileName = $_FILES['userUpload']['name'];
    $fileSize = $_FILES['userUpload']['size'];
    $fileError = $_FILES['userUpload']['error'];
    $fileType = $_FILES['userUpload']['type'];
    $dir = '../assets/post/';

    $profile = $_SESSION['profile'];
    $username = $_SESSION['user'];

    $caption = htmlspecialchars($_POST['caption']);
    $hastag = htmlspecialchars($_POST['hastag']);
    $datetime = date("Y-m-d H:i:s");

    if($fileError == 4) {
        echo "<script>alert('Oops, file cannot be empty')</script>";
        return false; exit;
    }

    move_uploaded_file($_FILES['userUpload']['tmp_name'], $dir.$fileName);

    $conn->query("INSERT INTO postingan(profile, username, postingan, caption, hastag, type, createdAt) VALUES('$profile','$username','$fileName','$caption','$hastag','$fileType','$datetime')");

    return mysqli_affected_rows($conn);

}

if(isset($_POST['posting'])) {
    if(posting($_POST) > 0) {
        echo "<script>alert('Success')</script>";
        header('refresh:0; url=');
    }
}

if(isset($_POST['likes'])) {
    if(!isset($_SESSION['login'])) {
        echo "<script>alert('Sorry, you must be logged in to like a post')</script>";
        header("refresh:0; url=");
        exit;
    }
    $id = $_POST['id_postingan'];
    $conn->query("UPDATE postingan SET likes = likes + 1 WHERE id_postingan = $id");
}

if(isset($_POST['deletePost'])) {
    $id = $_POST['id_postingan'];
    $query = $conn->query("DELETE FROM postingan WHERE id_postingan = $id");
    if($query) {
        echo "<script>alert('Successfully deleted post from')</script>";
        header("refresh:0; url=");
    } else {
        echo "<script>alert('Failed deleted post')</script>";
        header("refresh:0; url=");
    }
}

if(isset($_POST['addPost'])) {
    if(!isset($_SESSION['login'])) {
        echo "<script>alert('Sorry, you must be logged in to upload posts')</script>";
        header("refresh:0; url=");
        exit;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Post</title>
        <link rel="stylesheet" href="../bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
        <link rel="stylesheet" href="../assets/index.css">
        <script src="../bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    </head>
    <body>
        <div class="container mt-5 py-5">
            <div class="col-lg-10 col-md-10 col-12 p-2 mx-auto rounded shadow-sm">
                <p class="display-6 fs-2">Upload your story</p>
                <div class="preview" id="preview" width="600px" height="300px">
                    <img src="" alt="" id="prev-upload" width="450px" style="position: absolute;">
                    <video src="" id="prev-media" width="600px" height="250px" autoplay></video>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" onchange="readURL(this)" name="userUpload" id="userUpload" class="form-control mt-2 w-25">
                    <div class="mb-lg-2 mt-3 mb-1 row">
                        <label for="caption" class="col-1 col-form-label">Caption</label>
                        <div class="col-11">
                            <textarea name="caption" id="caption" cols="30" rows="3" class="w-75 form-control"></textarea>
                        </div>
                    </div>
                    <div class="mb-lg-2 mt-3 mb-1 row">
                        <label for="hastag" class="col-1 col-form-label">Hastag</label>
                        <div class="col-11">
                            <input type="text" name="hastag" id="hastag" cols="30" rows="3" class="w-25 form-control">
                        </div>
                    </div>
                    <div class="cta d-flex mt-4">
                        <button type="submit" name="posting" id="posting" class="btn btn-dark rounded"><i class="bi bi-send"></i> Post</button>
                        <a href="" class="mx-3 my-auto">back</a>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript">
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev-upload')
                            .attr('src', e.target.result);
                        $('#prev-media')
                            .attr('src', e.target.result);
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/index.css">
    <script src="../bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <style>
        @media(max-width: 885px) {
            .right { display: none }
        }
        @media(max-width: 430px) {
            .container.view { all: unset }
            .nav-item.pc { display: none }
            .upPage { margin-bottom: 40px }
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
                    <a class="nav-link active" aria-current="page" href=""><i class="bi bi-phone-fill"></i></a>
                </li>
                <li class="nav-item pc">
                    <a href="../menfess" class="nav-link"><i class="bi bi-heart"></i></a>
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
                if(isset($_SESSION['login'])) {?>
                <div class="dropdown rounded-pill">
                    <button class="btn rounded-pill p-0 px-1 d-flex" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img width="40" height="40" src="../assets/userProfile/<?= $_SESSION['profile'] ?>" alt="<?= $_SESSION['profile'] ?>"  class="rounded-circle">
                        <p class="h5 text-warning mx-1 my-auto"><?= ucwords($_SESSION['user']) ?></p>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="nav-item">
                            <a href="dashboard.php?user_profile=<?= $_SESSION['user'] ?>" class="dropdown-item"><i class="bi bi-gear"></i> Setting</a>
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
                    <a class="nav-link active" aria-current="page" href=""><i class="bi bi-phone-fill"></i></a>
                </li>
                <li class="nav-item">
                    <a href="../menfess" class="nav-link"><i class="bi bi-heart"></i></a>
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

    <div class="container view py-5 mt-4">
        <div class="text-center">
            <form action="" method="post">
                <button class="btn btn-dark rounded" type="submit" name="addPost" id="addPost"><i class="bi bi-plus"></i> Add Post</button>
            </form>
        </div>

        <div class="content mx-lg-5 mt-5">
            <div class="d-flex parent mx-lg-4">
                <div class="post-area col-lg-7 col-12" id="prev-post">
                    <?php while($r = mysqli_fetch_assoc($data)): ?>
                    <div class="card mb-2">
                        <div class="card-header d-flex bg-white">
                            <div class="profile">
                                <img src="../assets/userProfile/<?= $r['profile'] ?>" alt="<?= $r['profile'] ?>" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="user mx-2 my-auto">
                                <p class="fw-bold my-auto" style="font-size: 18px;"><?= $r['username'] ?></p>
                            </div>
                        </div>
                        <div id="media" class="media bg-black d-flex align-items-center justify-content-center" style="min-height: 150px;">
                        <?php if($r['type'] == 'video/mp4') { ?>
                            <video src="../assets/post/<?= $r['postingan'] ?>" alt="<?= $r['postingan'] ?>" class="my-auto" controls width="100%" style="max-height: 600px"></video>
                        <?php } else { ?>
                            <img src="../assets/post/<?= $r['postingan'] ?>" alt="<?= $r['postingan'] ?>" width="100%">
                        <?php } ?>
                        </div>
                        <div class="card-body">
                            <div class="link">
                                <form id="ctaForm" action="" method="post">
                                    <input type="hidden" name="val" id="val" value="http://localhost/thedigity/post/#posts_<?=$r['id_postingan']?>">
                                    <input type="hidden" name="id_postingan" id="id-postingan" value="<?= $r['id_postingan'] ?>">
                                    <button class="btn fs-4 p-0" type="button" name="likes" id="likes"><i class="bi bi-heart"></i></button>
                                    <button class="btn fs-4 p-0 mx-3"><i class="bi bi-chat"></i></button>
                                    <button class="btn fs-4 p-0" onclick="copy();"><i class="bi bi-share"></i></button>
                                    <?php if(isset($_SESSION['permission'])) { ?>
                                        <button type="submit" name="deletePost" class="float-end btn btn-danger p-1 rounded-pill px-3"><i class="bi bi-trash"></i></button>    
                                    <?php } ?>
                                </form>
                            </div>
                            <p class="fw-bold mb-2" id="prev-likes"><?= $r['likes'] ?> suka</p>
                            <p class="card-text mb-2"><span class="fw-bold"><?= $r['username'] ?></span> <?= $r['caption'] ?></p>
                            <?php if($r['hastag'] != '') { ?>
                                <a href="../tags/<?= $r['hastag'] ?>" style="text-decoration:none">#<?= $r['hastag'] ?></a>
                            <?php } ?>
                            <p class="text-muted mb-0" style="font-size: 12px"><?= time_elapsed_string($r['createdAt']) ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div class="right col-5">
                    <p class="fw-bold display-5 text-center" style="font-family:sofia">TheDigity</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.6.1.min.js"></script>
    <script>
        var container = document.getElementById("prev-post");
        var likes = document.getElementById("prev-likes");

        $('#likes').on('click', function() {
            var data = $('#ctaForm').serialize()+'&likes=likes';
            $.ajax({
                url: '',
                type: "POST",
                data: data,
                
            });

            $("#ctaForm")[0].reset();
        });

        $('#media').on('dbclick', function() {
            var data = $('#ctaForm').serialize()+'&likes=likes';
            $.ajax({
                url: '',
                type: "POST",
                data: data,
                
            });

            $("#ctaForm")[0].reset();
        });

        function load() {
            $(likes).load('../load/likesPost.php');
        }

        setInterval(function() {
            load();
        }, 1000);

        function scrollTop() {
            window.scrollTo(0, 0);
        }

        function copy() {
            navigator.clipboard.writeText($('#val').val());
        }
    </script>
    </script>
</body>
</html>