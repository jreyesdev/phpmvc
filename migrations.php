<?php
require_once __DIR__.'/vendor/autoload.php';

use App\Core\App;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new App(__DIR__);

$app->db->applyMigrations();