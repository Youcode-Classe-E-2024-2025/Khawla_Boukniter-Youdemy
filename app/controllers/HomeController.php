<?php

namespace App\Controllers;

use App\Core\Controller;
use Models\Course;

class HomeController extends Controller {
    private Course $courseModel;

    public function __construct() {
        // try {
        //     $this->courseModel = new Course();
        // } catch (\Exception $e) {
        //     error_log("Erreur de connexion à la base de données : " . $e->getMessage());
        // }
    }

    public function index() {
        try {
            // Récupérer les projets publics
            // $Courses = $this->courseModel->findAll(['is_active' => '1']);
            
            // Calculer quelques statistiques
            // $stats = [
            //     'total_courses' => count($publicCourses),
            //     'active_courses' => array_reduce($publicCourses, function($count, $course) {
            //         return $count + ($course['status'] === 'active' ? 1 : 0);
            //     }, 0),
            //     'completed_courses' => array_reduce($publicCourses, function($count, $course) {
            //         return $count + ($course['status'] === 'completed' ? 1 : 0);
            //     }, 0)
            // ];

            $this->render('index', [
                // 'courses' => $Courses,
                // 'stats' => $stats,
                'pageTitle' => 'Accueil'
            ]);
        } 
        catch (\Exception $e) {
            error_log("Erreur lors du chargement de la page d'accueil : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors du chargement de la page.";
            // $this->render('home/index', [
            //     'courses' => [],
            //     'stats' => [
            //         'total_courses' => 0,
            //         'active_courses' => 0,
            //         // 'completed_courses' => 0
            //     ],
            //     'pageTitle' => 'Accueil'
            // ]);
        }
    }
}
