<?php
require 'config.php';

// Only admins can delete news
if (!isAdmin() || !isset($_GET['id'])) {
    redirect('index.php');
}

// Fetch news to get image path
$stmt = $pdo->prepare("SELECT image_path FROM news WHERE id = ?");
$stmt->execute([$_GET['id']]);
$news = $stmt->fetch();

// Delete image if exists
if ($news && $news['image_path'] && file_exists($news['image_path'])) {
    unlink($news['image_path']);
}

// Delete news from database
$stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
$stmt->execute([$_GET['id']]);

redirect('index.php');
?>