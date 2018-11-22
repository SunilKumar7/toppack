<?php

require __DIR__ . '/../controllers/repository_controller.php';

$app->get('/search', \RepositoryController::class . ':search');
