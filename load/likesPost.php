<?php
include '../conn.php';
$data = $conn->query("SELECT * FROM postingan ORDER by id_postingan DESC");
$r = mysqli_fetch_assoc($data)
?>
<p id="prev-likes"><?= $r['likes'] ?> suka</p>