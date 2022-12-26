<?php
session_start();
include '../conn.php';
require '../function.php';

$result = $conn->query("SELECT * FROM news ORDER BY id DESC");
?>
<div class="d-flex" id="prev-news">
    <?php
        while($row = mysqli_fetch_assoc($result)):
    ?>
        <div class="col-lg-4 m-1 col-md-6 col-12 mb-4">
            <div class="card shadow-sm" style="height:450px">
                <img src="../assets/news/assets/<?= $row['backgroundNews'] ?>" class="card-img-top" width="300" height="200" alt="<?= $row['backgroundNews'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['titleNews'] ?></h5>
                    <hr class="m-1">
                    <div class="newsArea">
                        <p class="card-text" style=""><?= $row['news'] ?></p>
                    </div>
                    <div class="card-bottom shadow-sm rounded w-100 px-3" style="position: absolute; left:0; bottom: 0">
                        <a href="?tag=<?= $row['hastag'] ?>" style="text-decoration: none;"><?= $row['hastag'] ?></a>
                        <div class="up d-flex justify-content-between">
                            <?php if($row['link'] != '') { ?>
                                <a href="<?= $row['link'] ?>" class="btn btn-dark p-1 rounded-pill my-auto px-3"><i class="bi bi-link-45deg"></i> <?= $row['nameLink'] ?></a>
                            <?php } ?>
                            <?php if(isset($_SESSION['permission'])) { ?>
                                <form action="" method="post">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="deleteNews" class="btn btn-danger p-1 rounded-pill px-3"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php } ?>
                        </div>
                        <p class="card-text mb-3"><small class="text-muted">posted by <span class="text-warning fw-bold"><?= $row['postedBy'] ?></span> at <?= time_elapsed_string($row['createdAt']) ?></small></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>