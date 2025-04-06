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
    $copies = isset($_POST['copies']) ? max(1, (int)$_POST['copies']) : 1;
    
    // Enhanced file upload handling
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Validate file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($_FILES['image']['type'], $allowed_types) && 
            $_FILES['image']['size'] <= $max_size) {
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $target_path = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = $target_path;
            }
        } else {
            $_SESSION['error'] = "Invalid image file. Please upload JPG, PNG, or GIF under 2MB.";
            redirect('add_news.php');
        }
    }
    
    // Insert news copies
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO news 
                              (title, announcement, detailed_text, image_path, author_id, publication_timestamp) 
                              VALUES (?, ?, ?, ?, ?, NOW())");
        
        for ($i = 0; $i < $copies; $i++) {
            $stmt->execute([
                $title,
                $announcement,
                $detailed_text,
                $image_path,
                $_SESSION['user']['id']
            ]);
        }
        
        $pdo->commit();
        $_SESSION['success'] = "Successfully published $copies news article(s)!";
        redirect('index.php');
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        redirect('add_news.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 60px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .copies-input {
            width: 80px;
            display: inline-block;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-newspaper"></i> Add News Article</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>
                        
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">Title*</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                    <div class="invalid-feedback">Please provide a title.</div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="announcement" class="form-label">Short Announcement*</label>
                                    <textarea class="form-control" id="announcement" name="announcement" rows="3" required></textarea>
                                    <div class="invalid-feedback">Please provide a short summary.</div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="detailed_text" class="form-label">Detailed Content*</label>
                                    <textarea class="form-control" id="detailed_text" name="detailed_text" rows="5" required></textarea>
                                    <div class="invalid-feedback">Please provide the full content.</div>
                                </div>
                                
                                <div class="col-md-12 mb-4">
                                    <label for="image" class="form-label">Featured Image</label>
                                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                                    <div class="form-text">Optional. Max 2MB. Allowed formats: JPG, PNG, GIF.</div>
                                </div>
                                
                                <div class="col-md-12 mb-4">
                                    <div class="d-flex align-items-center">
                                        <label for="copies" class="form-label me-3">Number of copies:</label>
                                        <input type="number" class="form-control copies-input" id="copies" name="copies" min="1" max="100" value="1">
                                        <span class="ms-2 text-muted">(1-100)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Publish Article(s)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Form validation
    (function () {
        'use strict'
        
        // Fetch all forms we want to apply custom validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>
</html>
<?php require 'footer.php'; ?>