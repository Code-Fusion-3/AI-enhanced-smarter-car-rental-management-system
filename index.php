<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
session_start();

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'controllers/Router.php';

// Create database connection
$db = new Database();
$conn = $db->connect();

$router = new Router();
$router->route();