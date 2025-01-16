<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Course;

class DashboardController extends Controller {

    private $userModel;
    private $courseModel;

    public function __construct() {
        $this->userModel = new User();
        $this->courseModel = new Course();
    }

    public function index() {

        if ($_SESSION["user_role"] == "3") {
            error_log("Redirection vers: admin/dashboard");
            $this->render('users/admin/dashboard');
        }else if ($_SESSION["user_role"] == "2") {
            $stats = [
                'total_courses' => $this->courseModel->getTotalCoursesByTeacher($_SESSION['user_id']),
                'total_students' => $this->courseModel->getTotalStudentsByTeacher($_SESSION['user_id']),
            ];
            $courses = $this->courseModel->getPublishedCourses(['enseignant_id' => $_SESSION['user_id']]);
            error_log("Redirection vers: teacher/dashboard");
            $this->render('users/teacher/dashboard', ['stats' => $stats, 'courses' => $courses]);
        }else if ($_SESSION["user_role"] == "1") {
            error_log("Redirection vers: student/dashboard");
            $this->render('users/student/dashboard');
        } else {
            error_log("Redirection vers: choose_role");
            $this->render('choose_role');
        }
    }
}