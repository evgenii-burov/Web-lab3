<?php
require 'config.php';

// Only admins can edit news
if (!isAdmin()) {
    redirect('index.php');
}

if (!isset($_GET['id'])) {
    redirect('index.php');
}

// Fetch existing news
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$_GET['id']]);
$news = $stmt->fetch();

if (!$news) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $announcement = trim($_POST['announcement']);
    $detailed_text = trim($_POST['detailed_text']);
    
    // Handle image update
    $image_path = $news['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Delete old image if exists
        if ($image_path && file_exists($image_path)) {
            unlink($image_path);
        }
        
        $upload_dir = 'uploads/';
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        }
    }
    
    // Update news
    $stmt = $pdo->prepare("UPDATE news SET title = ?, announcement = ?, detailed_text = ?, image_path = ? 
                          WHERE id = ?");
    $stmt->execute([
        $title,
        $announcement,
        $detailed_text,
        $image_path,
        $_GET['id']
    ]);
    
    redirect('index.php');
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($news['title']) ?>" required>
    <textarea name="announcement" required><?= htmlspecialchars($news['announcement']) ?></textarea>
    <textarea name="detailed_text" required><?= htmlspecialchars($news['detailed_text']) ?></textarea>
    <?php if ($news['image_path']): ?>
        <img src="<?= htmlspecialchars($news['image_path']) ?>" height="100">
        <label><input type="checkbox" name="remove_image"> Remove image</label>
    <?php endif; ?>
    <input type="file" name="image" accept="image/*">
    <button type="submit">Update News</button>
</form>