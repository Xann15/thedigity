<?php
include '../conn.php';
require '../function.php';
$data = $conn->query("SELECT * FROM postingan ORDER BY id_postingan DESC");
?>
<div id="prev-post">
    <?php while($r = mysqli_fetch_assoc($data)): ?>
    <div class="card mb-3">
        <div class="card-header d-flex bg-white">
            <div class="profile">
                <img src="../assets/userProfile/<?= $r['profile'] ?>" alt="<?= $r['profile'] ?>" class="rounded-circle" width="50" height="50">
            </div>
            <div class="user mx-2 my-auto">
                <p class="fw-bold my-auto" style="font-size: 18px;"><?= $r['username'] ?></p>
            </div>
        </div>
        <?php if($r['type'] === 'video/mp4') { ?>
            <video src="../assets/post/<?= $r['postingan'] ?>" alt="<?= $r['postingan'] ?>" controls></video>
        <?php } else { ?>
            <img src="../assets/post/<?= $r['postingan'] ?>" class="" alt="<?= $r['postingan'] ?>">
        <?php } ?>
        <div class="card-body">
            <div class="link">
                <form id="ctaForm" action="" method="post">
                    <input type="hidden" name="val" id="val" value="http://localhost/thedigity/post/#posts_<?=$r['id_postingan']?>">
                    <input type="hidden" name="id_postingan" id="id-postingan" value="<?= $r['id_postingan'] ?>">
                    <button class="btn fs-4 p-0" type="submit" name="likes" id="likes"><i class="bi bi-heart"></i></button>
                    <button class="btn fs-4 p-0 mx-3"><i class="bi bi-chat"></i></button>
                    <button class="btn fs-4 p-0" onclick="copy();"><i class="bi bi-share"></i></button>
                </form>
            </div>
            <p class="fw-bold mb-2"><?= $r['likes'] ?> suka</p>
            <p class="card-text mb-2"><span class="fw-bold"><?= $r['username'] ?></span> <?= $r['caption'] ?></p>
            <?php if($r['hastag'] != '') { ?>
                 <a href="../tags/<?= $r['hastag'] ?>" style="text-decoration:none">#<?= $r['hastag'] ?></a>
            <?php } ?>
            <p class="text-muted mb-0" style="font-size: 12px"><?= time_elapsed_string($r['createdAt']) ?></p>
        </div>
    </div>
    <?php endwhile; ?>
</div>