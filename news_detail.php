<?php
require 'header.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$_GET['id']]);
$news = $stmt->fetch();

if (!$news) {
    redirect('index.php');
}
?>


<!--Main content-->
<div class="container-fluid">
  <div class="row mt-5 justify-content-center">
    <div class="col-10">
      <h1 class="display "><?= htmlspecialchars($news['title']) ?></h1>
      <h3 class="display py-3"><?= htmlspecialchars($news['announcement']) ?></h3>
      <div class="row">
        <p class="mb-3"><small>Опубликовано: <?= $news['publication_timestamp'] ?></small></p>
      </div>
        <?php if ($news['image_path']): ?>
            <img class="img-fluid rounded mb-3" src="<?= htmlspecialchars($news['image_path']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
        <?php endif; ?>
      <div class="mt-3"><?= nl2br(htmlspecialchars($news['detailed_text'])) ?></div>    
    </div>
  </div>
</div>



<?php require 'footer.php'; ?>