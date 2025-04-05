<?php
require 'config.php';

// Only admins can add news
if (!isAdmin()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $announcement = trim($_POST['announcement']);
    $detailed_text = trim($_POST['detailed_text']);
    
    // Simple file upload handling (in a real app, you'd want more security)
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        }
    }
    
    // Insert news
    $stmt = $pdo->prepare("INSERT INTO news (title, announcement, detailed_text, image_path, author_id) 
                          VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $title,
        $announcement,
        $detailed_text,
        $image_path,
        $_SESSION['user']['id']
    ]);
    
    redirect('index.php');
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="announcement" placeholder="Short announcement" required></textarea>
    <textarea name="detailed_text" placeholder="Detailed text" required></textarea>
    <input type="file" name="image" accept="image/*">
    <button type="submit">Add News</button>
</form>