<?php
require 'config.php';

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
        $error = "Invalid username or password";
    }
}
?>

<!-- Very simple HTML form -->
<form method="post">
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Sign In</button>
</form>
<a href="signup.php">Don't have an account? Sign Up</a>