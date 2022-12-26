<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | TheDigity</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="../bootstrap.bundle.min.js"></script>
    <scr type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>
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


        .nv {
            background: rgb(248,249,249);
        }

        .upPage {
            position:fixed;
            bottom: 0; right: 0;
            margin: 30px;
            z-index:99;
        }

        @media(max-width: 325px) {
            .upPage { margin: 20px }
        }
        
    </style>
</head>
<body>
    <nav class="nv navbar navbar-expand-lg fixed-top">
        <div class="container px-5">
            <a class="navbar-brand fs-4 fw-bold" href="" style="font-family:sofia">TheDigity</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav col-12 justify-content-evenly me-auto mb-2 mb-lg-0">
                    <div class="search my-auto mt-2">
                        <form class="d-flex" role="search" action="" method="post">
                            <input class="form-control me-2" type="text" name="keywordsChat" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-primary" type="submit" name="searchChat">Search</button>
                        </form>
                    </div>
                    <div class="list d-flex justify-content-between fs-4 col-3 float-end">
                        <li class="nav-item">
                            <a class="nav-link" href="../"><i class="bi bi-house-fill"></i></a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="../post"><i class="bi bi-phone"></i></a>
                        </li>
                        <li class="nav-item">
                            <a href="../news" class="nav-link"><i class="bi bi-newspaper"></i></a>
                        </li>
                        <li class="nav-item mx-3">
                            <a href="../menfess" class="nav-link"><i class="bi bi-heart"></i></a>
                        </li>
                        <?php if(isset($_SESSION['permission'])) { ?>
                            <li class="nav-item">
                                <a href="../admin" class="nav-link"><i class="bi bi-code-slash"></i></a>
                            </li>
                        <?php } ?>

                        <?php if(isset($_SESSION['super'])) {?>
                            <li class="nav-item mx-3">
                                <a href="../system.php" class="nav-link"><i class="bi bi-trophy"></i></a>
                            </li>
                        <?php } ?>
                    </div>
                </ul>
            </div>
        </div>
    </nav>

    <span class="nv upPage shadow rounded p-2"><a onclick="window.scrollTo(0, 0);"><i class="p-2 bi bi-chevron-double-up"></i></a></span>

    <div class="container mt-5 py-5">
        <?php if(isset($_SESSION['login'])) { ?>
            <img src="http://localhost/thedigity/assets/404.webp" alt="404 image" width="300" class="mx-auto d-block">
            <p class="display-6">Hi <span class="text-warning"><?= ucwords($_SESSION['user']) ?></span>, it seems the page you are looking for was not found, please <a href="javascript:history.back();" class="">come back</a></p>
        <?php } else { ?>
            <img src="http://localhost/thedigity/assets/404.webp" alt="404 image" height="400" class="mx-auto d-block">
            <p class="display-6">Hi There!, it seems the page you are looking for was not found, please <a href="javascript:history.back();" class="">come back</a></p>
        <?php } ?>
    </div>
</body>
</html>