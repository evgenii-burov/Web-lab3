<?php
require 'config.php';

// Fetch all news articles
$stmt = $pdo->query("SELECT * FROM news ORDER BY publication_timestamp DESC");
$news = $stmt->fetchAll();
?>

<!-- Simple news listing -->
<?php if (isset($_SESSION['user'])): ?>
    <p>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>! 
       <a href="logout.php">Logout</a></p>
    <?php if (isAdmin()): ?>
        <a href="add_news.php">Add News</a>
    <?php endif; ?>
<?php else: ?>
    <a href="signin.php">Sign In</a> | <a href="signup.php">Sign Up</a>
<?php endif; ?>

<?php foreach ($news as $item): ?>
    <div class="news-card">
        <h2><?= htmlspecialchars($item['title']) ?></h2>
        <?php if ($item['image_path']): ?>
            <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
        <?php endif; ?>
        <p><?= htmlspecialchars($item['announcement']) ?></p>
        <p><small>Published: <?= $item['publication_timestamp'] ?></small></p>
        <a href="news_detail.php?id=<?= $item['id'] ?>">Read more</a>
        
        <?php if (isAdmin()): ?>
            <a href="edit_news.php?id=<?= $item['id'] ?>">Edit</a>
            <a href="delete_news.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>