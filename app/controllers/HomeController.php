<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;

class HomeController extends Controller
{
    private Course $courseModel;

    public function __construct()
    {
        try {
            $this->courseModel = new Course();
        } catch (\Exception $e) {
            error_log("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function index()
    {
        $courseModel = new Course();
        $data = [
            'topCourses' => $courseModel->getTopCourses(8),
            'categories' => $courseModel->getCategories(),
            'recentCourses' => $courseModel->getPublishedCourses([], 1, 8)
        ];

        $this->render('home', $data);
    }
}
