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
use App\Controllers\AdminController;
use App\Controllers\CategoriesTagsController;

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
$router->addRoute('GET', '/teacher/courses/content', [CourseController::class, 'showContentForm']);
$router->addRoute('POST', '/teacher/courses/save_step1', [CourseController::class, 'saveStep1']);
$router->addRoute('POST', '/teacher/courses/store', [CourseController::class, 'store']);
$router->addRoute('GET', '/teacher/edit_course/{id}', [CourseController::class, 'edit']);
$router->addRoute('POST', '/teacher/edit_course/{id}', [CourseController::class, 'update']);
$router->addRoute('POST', '/teacher/delete_course/{id}', [CourseController::class, 'delete']);
$router->addRoute('GET', '/teacher/courses/{id}/enrollments', [CourseController::class, 'viewEnrollments']);
$router->addRoute('GET', '/users/teacher/courses', [CourseController::class, 'teacherCourses']);
$router->addRoute('POST', '/teacher/courses', [CourseController::class, 'index']);
$router->addRoute('GET', '/courses/{id}', [CourseController::class, 'show']);
$router->addRoute('GET', '/uploads/{filename}', [CourseController::class, 'serveFile']);

// Add route for browsing courses
$router->addRoute('GET', '/browse', [CourseController::class, 'browse']);
$router->addRoute('GET', '/courses/{id}', [CourseController::class, 'show']);
$router->addRoute('GET', '/search', [CourseController::class, 'search']);

$router->addRoute('GET', '/student/courses', [CourseController::class, 'studentCourses']);

$router->addRoute('POST', '/courses/{id}/enroll', [CourseController::class, 'enroll']);
$router->addRoute('POST', '/courses/{id}/unenroll', [CourseController::class, 'unenroll']);

$router->addRoute('GET', '/users/admin/inscriptions', [UserController::class, 'inscriptions']);
$router->addRoute('GET', '/users/admin/courses', [CourseController::class, 'adminCourses']);
$router->addRoute('POST', '/admin/courses/delete/{id}', [CourseController::class, 'adminDeleteCourse']);
$router->addRoute('GET', '/users/admin/categories-tags', [CategoriesTagsController::class, 'index']);
$router->addRoute('GET', '/users/admin/users', [UserController::class, 'users']);

// Admin Actions
$router->addRoute('POST', '/admin/validate-teacher', [UserController::class, 'validateTeacher']);
$router->addRoute('POST', '/admin/reject-teacher', [UserController::class, 'rejectTeacher']);

$router->addRoute('POST', '/admin/categories/add', [CategoriesTagsController::class, 'addCategory']);
$router->addRoute('POST', '/admin/categories/delete/{id}', [CategoriesTagsController::class, 'deleteCategory']);
$router->addRoute('POST', '/admin/tags/add', [CategoriesTagsController::class, 'addTags']);
$router->addRoute('POST', '/admin/tags/delete/{id}', [CategoriesTagsController::class, 'deleteTag']);

$router->addRoute('POST', '/admin/users/activate/{id}', [UserController::class, 'activateUser']);
$router->addRoute('POST', '/admin/users/suspend/{id}', [UserController::class, 'suspendUser']);
$router->addRoute('POST', '/admin/users/delete/{id}', [UserController::class, 'deleteUser']);


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
