<?php

include 'conn.php';
$data = mysqli_query($conn, "SELECT * FROM users");
$chat = mysqli_query($conn, "SELECT * FROM chats");
$news = mysqli_query($conn, "SELECT * FROM news");

function register($data) {
    global $conn;

    $imageName = $_FILES['profilePicture']['name'];
    $imageSize = $_FILES['profilePicture']['size'];
    $imageError = $_FILES['profilePicture']['error'];
    $imageType = $_FILES['profilePicture']['type'];
    $dir = "userProfile/";
    $defaultPicture = "user.png";

    $username = strtolower(stripslashes($data['username']));
    $password = mysqli_real_escape_string($conn, $data['password']);
    $password2 = mysqli_real_escape_string($conn, $data['password2']);
    $email = strtolower(stripslashes($data['email']));

    // ?Check username yang di inputkan sudah terdaftar apa belum
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if(mysqli_fetch_assoc($result)) {
        ?>
        <div class="alert alert-danger" role="alert">username already exists!</div>
        <?php
    return false;
    }

    // ?Check password verifynya match atau tidak
    if($password != $password2) {
        ?>
        <div class="alert alert-danger" role="alert">password doesn't match</div>
        <?php
    return false;
    }

    // ?Jika field username kosong
    if(empty(trim($username))) {
        ?>
        <div class="alert alert-danger" role="alert">please enter your username</div>
        <?php
    return false;
    }

    // ?Jika user tidak memilih profile picture -> set to picture default
    if($imageError === 4) {
        $imageName = $defaultPicture;
    }

    // ?Logic extention profilePicture
    $ekstensiGambarValid = ['jpg', 'gif', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $imageName);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if(!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        ?>
            <div class="alert alert-danger" role="alert">please input the image according to the supported format (jpg, jpeg, png)</div>
        <?php
        return false;
    }

    $profilePicture = $imageName;

    $password = password_hash($password, PASSWORD_BCRYPT);

    //? default role
    $role = 'user';

    move_uploaded_file($_FILES['profilePicture']['tmp_name'], $dir.$profilePicture);
    mysqli_query($conn, "INSERT INTO users(role, profilePicture, username, password, email) VALUES('$role', '$profilePicture', '$username', '$password', '$email')");
        $_SESSION['profile'] = $profilePicture;

    return mysqli_affected_rows($conn);
}

function addNews($news) {
    global $conn;

    $imageName = $_FILES['backgroundNews']['name'];
    $imageSize = $_FILES['backgroundNews']['size'];
    $imageError = $_FILES['backgroundNews']['error'];
    $imageType = $_FILES['backgroundNews']['type'];
    $dir = "../assets/news/assets/";
    
    // ?Jika background news kosong -> set to default
    $defaultImage = "default.jpg";
    if($imageError === 4) {
        $imageName = $defaultImage;
    }

    // ?Logic extention background news
    $ekstensiGambarValid = ['jpg', 'gif', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $imageName);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if(!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('please input the image according to the supported format (jpg, jpeg, png)')</script>";
        return false;
    }

    $titleNews = $_POST['titleNews'];
    $newsContent = $_POST['news'];
    $link = $_POST['link'];
    $nameLink = $_POST['nameLink'];
    $hastag = $_POST['hastag'];
    $postedBy = $_SESSION['user'];
    $datetime = date('Y-m-d H:i:s');

    // ?Jika title news / isi news kosong
    if(empty(trim($titleNews && $newsContent))) {
        echo "<script>alert('news title or news content cannot be empty')</script>";
        return false;
    }

    move_uploaded_file($_FILES['backgroundNews']['tmp_name'], $dir.$imageName);
    mysqli_query($conn, "INSERT INTO news(titleNews, news, link, nameLink, hastag, backgroundNews, postedBy, createdAt) VALUES('$titleNews', '$newsContent', '$link', '$nameLink', '$hastag', '$imageName', '$postedBy', '$datetime')");

    return mysqli_affected_rows($conn);
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>

