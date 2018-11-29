<?php

require __DIR__ . '/../controllers/repository_controller.php';
require __DIR__ . '/../controllers/package_controller.php';

$app->get('/search', \RepositoryController::class . ':search');
$app->post('/import/', \RepositoryController::class . ':import');
$app->get('/top_packages', \PackageController::class . ':topPackages');

