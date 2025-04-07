<?php 
require 'header.php';

// Only admins can add news
if (!isAdmin()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $announcement = trim($_POST['announcement']);
    $detailed_text = trim($_POST['detailed_text']);
    $copies = isset($_POST['copies']) ? max(1, min(100, (int)$_POST['copies'])) : 1;
    
    // Simple file upload handling
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
    
    // Insert news copies
    $successCount = 0;
    $stmt = $pdo->prepare("INSERT INTO news 
                          (title, announcement, detailed_text, image_path, author_id, publication_timestamp) 
                          VALUES (?, ?, ?, ?, ?, NOW())");
    
    for ($i = 0; $i < $copies; $i++) {
        if ($stmt->execute([$title, $announcement, $detailed_text, $image_path, $_SESSION['user']['id']])) {
            $successCount++;
        }
    }
    
    if ($successCount > 0) {
        $_SESSION['success'] = "Successfully published $successCount news article(s)!";
    } else {
        $_SESSION['error'] = "Failed to publish news articles";
    }
    redirect('index.php');
}
?>

    <div class="container py-4">
        <div class="admin-form-container">
            <div class="admin-form-header">
                <h4><i class="bi bi-newspaper"></i> Добавление новости</h4>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data" class="admin-form">
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold">Заголовок</label>
                    <input type="text" class="form-control form-control-lg" id="title" name="title" required>
                </div>
                
                <div class="mb-4">
                    <label for="announcement" class="form-label fw-bold">Анонс</label>
                    <textarea class="form-control" id="announcement" name="announcement" rows="3" required></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="detailed_text" class="form-label fw-bold">Полный текст новости</label>
                    <textarea class="form-control" id="detailed_text" name="detailed_text" rows="5" required></textarea>
                </div>
                
                <div class="mb-4 image-upload-area">
                    <label for="image" class="form-label fw-bold">Картинка</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    <div class="image-upload-help">Максимальный размер файла - 5 Мбайт. Поддерживаемые расширения: JPG, PNG.</div>
                </div>
                
                <div class="mb-4">
                    <label for="copies" class="form-label fw-bold">Число копий</label>
                    <div class="d-flex align-items-center">
                        <input type="number" class="form-control" id="copies" name="copies" 
                               min="1" max="100" value="1" style="width: 80px;">
                        <span class="ms-2 text-muted">(1-100)</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Отмена
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Опубликовать
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
        const image = document.getElementById('image').value.trim();
        if (!title || !announcement || !detailedText || !image) {
            e.preventDefault();
            alert('Заполните все поля');
            return false;
        }
        
        return true;
    });
    </script>

<?php require 'footer.php'; ?>