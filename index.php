<?php require 'header.php'; ?>

<?php
$stmt = $pdo->query("SELECT * FROM news ORDER BY publication_timestamp DESC");
$news = $stmt->fetchAll();
$items_per_page = 10;
$total_items = count($news);
$total_pages = ceil($total_items / $items_per_page);
$current_page = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$base_url = 'index.php?page=';
?>


<!-- Main content -->
<div class="container-fluid align-items-center">
    <?php if (isset($_SESSION['user'])): ?>
        <div class="text-end mb-3">
            Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>! 
            <a href="logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
    <?php else: ?>
        <div class="text-end mb-3">
            <a href="signin.php" class="btn btn-outline-primary btn-sm">Sign In</a>
            <a href="signup.php" class="btn btn-primary btn-sm ms-2">Sign Up</a>
        </div>
    <?php endif; ?>

    <!-- Vertical Cards (first 4 news items) -->
    <div class="row justify-content-center">
        <?php foreach (array_slice($news, 10*($current_page - 1), 4) as $item): ?>
            <div class="col-10 mb-3 mb-xxl-0 col-md-5 col-xxl-2">
                <a href="news_detail.php?id=<?= $item['id'] ?>" class="text-decoration-none">
                    <div class="card h-100">
                        <?php if ($item['image_path']): ?>
                            <img class="img-fluid rounded card-img-top" src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <div class="card-title"><h4><?= htmlspecialchars($item['title']) ?></h4></div>
                            <div class="card-text overflow-hidden flex-grow-1"><?= htmlspecialchars($item['announcement']) ?></div>
                            <small class="text-body-secondary"><?= date('d F Y, H:i', strtotime($item['publication_timestamp'])) ?></small>
                            <?php if (isAdmin()): ?>
                                <div class="mt-2">
                                    <a href="edit_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Horizontal Cards (next 2 news items) -->
    <?php if (count($news) > 4): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-10 col-xxl-8 d-flex justify-content-center">
                <div class="row mt-0 mt-xxl-3 justify-content-center">
                    <?php foreach (array_slice($news, 10*($current_page - 1) + 4, 2) as $item): ?>
                        <div class="col-10 mb-3 mb-xxl-0 col-md-12 col-xxl-6">
                            <a href="news_detail.php?id=<?= $item['id'] ?>" class="text-decoration-none">
                                <div class="card h-100">
                                    <div class="row align-items-center h-100">
                                        <?php if ($item['image_path']): ?>
                                            <div class="col-12 col-md-6 col-xxl-6">
                                                <img class="img-fluid rounded" src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-12 <?= $item['image_path'] ? 'col-md-6 col-xxl-6' : '' ?>">
                                            <div class="card-body">
                                                <div class="card-title"><h4><?= htmlspecialchars($item['title']) ?></h4></div>
                                                <div class="card-text overflow-hidden"><?= htmlspecialchars($item['announcement']) ?></div>
                                                <small class="text-body-secondary"><?= date('d F Y, H:i', strtotime($item['publication_timestamp'])) ?></small>
                                                <?php if (isAdmin()): ?>
                                                    <div class="mt-2">
                                                        <a href="edit_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="delete_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Vertical Cards (first 4 news items) -->

    <?php if (count($news) > 6): ?>

        <div class="row justify-content-center">
        <?php foreach (array_slice($news, 10*($current_page - 1) + 6, 4) as $item): ?>
            <div class="col-10 mb-3 mb-xxl-0 col-md-5 col-xxl-2">
                <a href="news_detail.php?id=<?= $item['id'] ?>" class="text-decoration-none">
                    <div class="card h-100">
                        <?php if ($item['image_path']): ?>
                            <img class="img-fluid rounded card-img-top" src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <div class="card-title"><h4><?= htmlspecialchars($item['title']) ?></h4></div>
                            <div class="card-text overflow-hidden flex-grow-1"><?= htmlspecialchars($item['announcement']) ?></div>
                            <small class="text-body-secondary"><?= date('d F Y, H:i', strtotime($item['publication_timestamp'])) ?></small>
                            <?php if (isAdmin()): ?>
                                <div class="mt-2">
                                    <a href="edit_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Add News Button (for admins) -->
<?php if (isAdmin()): ?>
<div class="text-center my-4">
    <a href="add_news.php" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle"></i> Add News
    </a>
</div>
<?php endif; ?>

<!-- Pagination -->
<nav aria-label="News pagination">
  <ul class="pagination py-3 justify-content-center">
    <!-- First Page Button -->
    <li class="page-item shadow-lg <?= ($current_page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $base_url ?>1" aria-label="First">
        <span aria-hidden="true">&laquo;&laquo;</span>
      </a>
    </li>
    
    <!-- Previous Page Button -->
    <li class="page-item shadow-lg <?= ($current_page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $base_url.($current_page - 1) ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>

    <!-- Page Numbers -->
    <?php
    $start_page = max(1, min($current_page - 2, $total_pages - 4));
    $end_page = min($total_pages, $start_page + 4);
    
    for ($i = $start_page; $i <= $end_page; $i++): ?>
      <li class="page-item shadow-lg <?= ($i == $current_page) ? 'active' : '' ?>">
        <a class="page-link" href="<?= $base_url.$i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Next Page Button -->
    <li class="page-item shadow-lg <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $base_url.($current_page + 1) ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
    
    <!-- Last Page Button -->
    <li class="page-item shadow-lg <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $base_url.$total_pages ?>" aria-label="Last">
        <span aria-hidden="true">&raquo;&raquo;</span>
      </a>
    </li>
  </ul>
</nav>

<?php require 'footer.php'; ?>