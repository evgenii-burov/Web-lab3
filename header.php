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
    <div class="container-fluid row justify-content-evenly justify-content-xxl-between">
        <div class="col-3 text-center">
            <!-- Modified part: Wrapped in flex container -->
            <a class="navbar-brand display-1 d-flex flex-wrap justify-content-center" href="index.php">
                <span class="hover-underline me-1">News from</span>
                <span class="hover-underline">far beyond</span>
            </a>
        </div>

        <div class="col-3 text-center">
            <div class="container-fluid row justify-content-center">
                <div class="col-12 col-xxl-6 text-center">
                    <a class="navbar-brand display-2" href="index.php">Sign in</a>
                </div>
                <div class="col-12 col-xxl-6 text-center">
                    <a class="navbar-brand display-2" href="index.php">Sign up</a>
                </div>
            </div>
        </div>
    </div>
</nav>
