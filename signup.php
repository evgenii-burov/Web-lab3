<?php
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Username and password are required";
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $error = "Пользователь с таким логином уже существует";
        } else {
            // Create user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            
            // Automatically log in
            $userId = $pdo->lastInsertId();
            $_SESSION['user'] = [
                'id' => $userId,
                'username' => $username,
                'is_admin' => false
            ];
            
            redirect('index.php');
        }
    }
}
?>


    <style>
        .auth-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: rgb(231, 231, 231);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>

    <div class="container">
        <div class="auth-container">
            <h2 class="text-center mb-4"><i class="bi bi-person-plus"></i>Создание аккаунта</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Логин</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-person-plus"></i> Создать аккаунт
                </button>
                
                <div class="text-center">
                    <a href="signin.php" class="text-decoration-none">Уже имеете аккаунт? Войти</a>
                </div>
            </form>
        </div>
    </div>

    <?php
require 'footer.php';?>