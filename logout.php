<?php
require 'config.php';

// Destroy session
session_destroy();
redirect('index.php');
?>