<?php
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Find user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        // Successful login
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'is_admin' => (bool)$user['is_admin']
        ];
        redirect('index.php');
    } else {
        $error = "Неверное имя пользователя или пароль";
    }
}
?>

    <style>
        .auth-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>

    <div class="container">
        <div class="auth-container">
            <h2 class="text-center mb-4"><i class="bi bi-box-arrow-in-right"></i> Войти</h2>
            
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
                    <i class="bi bi-box-arrow-in-right"></i> Войти
                </button>
                
                <div class="text-center">
                    <a href="signup.php" class="text-decoration-none">Не имеете аккаунт? Создать аккаунт</a>
                </div>
            </form>
        </div>
    </div>

<?php require 'footer.php';?>