<?php
// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/config/init.php';
require_once __DIR__ . '/../vendor/autoload.php';
// require_once __DIR__ . '/../app/helpers/url_helper.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CourseController;
use App\Controllers\CategoryController;
use App\Controllers\UserController;
use App\Controllers\DashboardController;

$router = new Router();

// Route pour la page d'accueil
$router->addRoute('GET', '/', handler: [HomeController::class, 'index']);

// Routes pour l'authentification
$router->addRoute('GET', '/login', [AuthController::class, 'loginForm']);
$router->addRoute('POST', '/login', [AuthController::class, 'login']);
$router->addRoute('GET', '/register', [AuthController::class, 'registerForm']);
$router->addRoute('POST', '/register', [AuthController::class, 'register']);
$router->addRoute('GET', '/choose_role', [AuthController::class, 'chooseRoleForm']);
$router->addRoute('POST', '/choose_role', [AuthController::class, 'chooseRole']);
$router->addRoute('GET', '/logout', [AuthController::class, 'logout']);

$router->addRoute('GET', '/dashboard', [DashboardController::class, 'index']);

$router->addRoute('GET', '/teacher/courses/create', [CourseController::class, 'create']);
$router->addRoute('POST', '/teacher/courses/store', [CourseController::class, 'store']);
$router->addRoute('POST', '/teacher/courses', [CourseController::class, 'index']);

$router->addRoute('GET', '', [CourseController::class, 'browse']);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/Khawla_Boukniter-Youdemy/public', '', $uri);
if (empty($uri)) {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];
try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    // Affichage de l'erreur
    echo '<pre>';
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
    echo '</pre>';
}
