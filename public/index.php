<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('VIEW_PATH', dirname(__DIR__) . '/resources/views/');
define('BASE_TEMPLATE', dirname(__DIR__) . '/resources/views/layouts/base-template.php');
define('AUTH_LAYOUT', dirname(__DIR__) . '/resources/views/layouts/auth-layout.php');
define('css', dirname(__DIR__) . '/resources/css/app.css');
define('js', dirname(__DIR__) . '/resources/js/app.js');

require_once BASE_PATH . '/vendor/autoload.php';

use App\Database\Database;
use App\Framework\Http\Kernel;
use App\Framework\Http\Request;

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$databaseConfig = [
    'host' => $_ENV['DB_HOST'],
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'pass' => $_ENV['DB_PASS'],
];

$database = new Database($databaseConfig);
$request = Request::createFromGlobals();
$kernel = new Kernel($database);

$response = $kernel->handle($request);
$response->send();
