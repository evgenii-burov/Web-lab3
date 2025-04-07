<?php
require 'header.php';

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
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // Delete old image if exists
            if ($image_path && file_exists($image_path)) {
                unlink($image_path);
            }
            $image_path = $target_path;
        }
    }
    
    // Update news
    $stmt = $pdo->prepare("UPDATE news SET title = ?, announcement = ?, detailed_text = ?, image_path = ? WHERE id = ?");
    if ($stmt->execute([$title, $announcement, $detailed_text, $image_path, $_GET['id']])) {
        $_SESSION['success'] = "News article updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update news article";
    }
    redirect('index.php');
}
?>

    <div class="container py-4">
        <div class="admin-form-container">
            <div class="admin-form-header">
                <h4><i class="bi bi-pencil-square"></i> Редактирование новости</h4>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data" class="admin-form">
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold">Заголовок</label>
                    <input type="text" class="form-control form-control-lg" id="title" name="title" 
                           value="<?= htmlspecialchars($news['title']) ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="announcement" class="form-label fw-bold">Анонс</label>
                    <textarea class="form-control" id="announcement" name="announcement" required><?= htmlspecialchars($news['announcement']) ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="detailed_text" class="form-label fw-bold">Полный текст новости</label>
                    <textarea class="form-control" id="detailed_text" name="detailed_text" required><?= htmlspecialchars($news['detailed_text']) ?></textarea>
                </div>
                
                <div class="mb-4 image-upload-area">
                    <label class="form-label fw-bold">Картинка</label>
                    <div class="mb-3">
                        <?php if ($news['image_path']): ?>
                            <img src="<?= htmlspecialchars($news['image_path']) ?>" class="current-image-preview">
                        <?php else: ?>
                            <div class="text-muted">Картинка отсутствует</div>
                        <?php endif; ?>
                    </div>
                    
                    <label for="image" class="form-label fw-bold">Изменить картинку</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    <div class="image-upload-help">Оставьте пустым чтобы не изменять картинку. Максимальный размер файла - 5 Мбайт. Поддерживаемые расширения: JPG, PNG.</div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Отмена
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Изменить новость
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Simple client-side validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const announcement = document.getElementById('announcement').value.trim();
        const detailedText = document.getElementById('detailed_text').value.trim();
        
        if (!title || !announcement || !detailedText) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
        
        return true;
    });
    </script>

<?php require 'footer.php'; ?>