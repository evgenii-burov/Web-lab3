<?php require 'config.php'; ?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>News from far beyond</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="styles.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="logo.png">
    </head>

    <body>
    
<!--Navbar-->
<nav class="navbar shadow-lg fixed-top">
  <div class="row container-fluid justify-content-center justify-content-xxl-start">
    <div class="col-11 text-start">
        <a class="navbar-brand display-1" href="index.php">News from far beyond</a>
    </div>
    <!-- Profile dropdown -->
    <div class="col-1">
      <div class="dropdown">
        <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#66b3ff" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
          </svg>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <?php if (isset($_SESSION['user'])): ?>
            <li class="dropdown-item-text">
              Добро пожаловать, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="logout.php">Выйти</a>
            </li>
          <?php else: ?>
            <li>
              <a class="dropdown-item" href="signin.php">Войти</a>
            </li>
            <li>
              <a class="dropdown-item" href="signup.php">Создать аккаунт</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</nav>
