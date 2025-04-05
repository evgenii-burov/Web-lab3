<?php
require 'config.php';

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

<h1><?= htmlspecialchars($news['title']) ?></h1>
<?php if ($news['image_path']): ?>
    <img src="<?= htmlspecialchars($news['image_path']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
<?php endif; ?>
<p><small>Published: <?= $news['publication_timestamp'] ?></small></p>
<div><?= nl2br(htmlspecialchars($news['detailed_text'])) ?></div>
<a href="index.php">Back to news</a>