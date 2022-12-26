<?php
include '../conn.php';
require '../function.php';

$data = $conn->query("SELECT * FROM chats");
?>

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
<script>
    var containerChat = document.getElementById('area-chating');
</script>