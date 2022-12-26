<?php
include '../conn.php';
$data = $conn->query("SELECT * FROM menfess");
?>

<div id="menfess-area">
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