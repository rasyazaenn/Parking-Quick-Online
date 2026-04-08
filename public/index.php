<?php
require '../config.php';
require '../app/core/Database.php';
require '../app/core/Controller.php';
require '../app/core/Model.php';

$url = $_GET['url'] ?? 'dashboard';

if ($url == 'auth') {
    require '../app/controllers/AuthController.php';
    $controller = new AuthController;
    $controller->index();
}
elseif ($url == 'auth/login') {
    require '../app/controllers/AuthController.php';
    $controller = new AuthController;
    $controller->login();
}
else {
    require '../app/controllers/DashboardController.php';
    $controller = new DashboardController;
    $controller->index();
}
