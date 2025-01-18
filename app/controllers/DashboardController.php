<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Course;

class DashboardController extends Controller
{

    private $userModel;
    private $courseModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->courseModel = new Course();
    }

    public function index()
    {
        $courseModel = new Course();
        $latestCourses = $courseModel->getLatestTeacherCourses($_SESSION['user_id'], 3);
        $stats = [
            'total_courses' => $courseModel->getTotalCoursesByTeacher($_SESSION['user_id']),
            'total_students' => $courseModel->getTotalStudentsByTeacher($_SESSION['user_id'])
        ];

        $this->render('users/teacher/dashboard', [
            'latestCourses' => $latestCourses,
            'stats' => $stats
        ]);
    }
}
