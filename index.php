<?php
require 'header.php';

?>

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

<!-- News Slider -->
<?php if (!empty($news)): ?>
<div class="row justify-content-center mb-3">  <!-- Outer row for centering -->
    <div class="col-10">  <!-- Responsive column constraints -->
        <div class="slider-container">
            <div class="slider">
                <?php foreach (array_slice($news, 10*($current_page - 1), 5) as $item): ?>
                    <?php if ($item['image_path']): ?>
                        <div class="slide">
                            <a href="news_detail.php?id=<?= $item['id'] ?>" class="text-decoration-none">
                                <div class="slide-content">
                                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="slide-image">
                                    <div class="slide-overlay">
                                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                                        <p><?= htmlspecialchars($item['announcement']) ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <!-- Navigation Arrows -->
            <button class="slider-arrow prev-arrow" aria-label="Previous slide">&#10094;</button>
            <button class="slider-arrow next-arrow" aria-label="Next slide">&#10095;</button>
            <!-- Navigation Dots -->
            <div class="slider-dots"></div>
        </div>
    </div>
</div>
<?php endif; ?>
<style>
    .slider-container {
        position: relative;
        overflow: hidden;
    }
    
    .slider {
        display: flex;
        transition: transform 0.5s ease-in-out;
        height: 400px;
    }
    
    .slide {
        min-width: 100%;
        position: relative;
    }
    
    .slide-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .slide-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        padding: 2rem;
    }
    
    .slide-overlay h3 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }
    
    .slide-overlay p {
        font-size: 1rem;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        padding: 1rem;
        z-index: 10;
        transition: background 0.3s;
    }
    
    .slider-arrow:hover {
        background: rgba(0,0,0,0.8);
    }
    
    .prev-arrow {
        left: 0;
        border-radius: 0 5px 5px 0;
    }
    
    .next-arrow {
        right: 0;
        border-radius: 5px 0 0 5px;
    }
    
    .slider-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
    }
    
    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        border: none;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .slider-dot.active {
        background: white;
    }
    
    @media (max-width: 768px) {
        .slider {
            height: 300px;
        }
        
        .slide-overlay {
            padding: 1rem;
        }
        
        .slide-overlay h3 {
            font-size: 1.4rem;
        }
    }
</style>

<script src="slider.js"></script>

    <!-- Vertical Cards (first 4 news items) -->
    <div class="row justify-content-center">
        <?php foreach (array_slice($news, 10*($current_page - 1) + 5, 4) as $item): ?>
            <div class="col-10 col-md-5 col-xxl-2 mb-3">
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
                                    <a href="edit_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Редактировать</a>
                                    <a href="delete_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Удалить</a>
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
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-xxl-8 d-flex justify-content-center">
                <div class="row justify-content-center">
                    <?php foreach (array_slice($news, 10*($current_page - 1) + 9, 2) as $item): ?>
                        <div class="col-10 col-md-12 col-xxl-6 mb-3">
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
                                                        <a href="edit_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Редактировать</a>
                                                        <a href="delete_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Удалить</a>
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
        <?php foreach (array_slice($news, 10*($current_page - 1) + 11, 4) as $item): ?>
            <div class="col-10 col-md-5 col-xxl-2 mb-3">
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
                                    <a href="edit_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Редактировать</a>
                                    <a href="delete_news.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Удалить</a>
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
        <i class="bi bi-plus-circle"></i> Добавить новости
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

<?php
require 'footer.php';

?>