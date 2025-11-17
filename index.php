<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './controllers/c_index.php';
$index = new Index();
$index->base();

// echo password_hash('1234', PASSWORD_DEFAULT);
?>