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
        if ($_SESSION['user_role'] == 1) {

            $enrolled_courses = $this->courseModel->getEnrolledCoursesCount($_SESSION['user_id']);
            $recentCourses = $this->courseModel->getStudentRecentCourses($_SESSION['user_id']);

            $this->render('users/student/dashboard', ['enrolled_courses' => $enrolled_courses, 'recentCourses' => $recentCourses]);
        } else if ($_SESSION['user_role'] == 2) {

            $latestCourses = $this->courseModel->getLatestTeacherCourses($_SESSION['user_id'], 3);
            $stats = [
                'total_courses' => $this->courseModel->getTotalCoursesByTeacher($_SESSION['user_id']),
                'total_students' => $this->courseModel->getTotalStudentsByTeacher($_SESSION['user_id'])
            ];

            $this->render('users/teacher/dashboard', [
                'latestCourses' => $latestCourses,
                'stats' => $stats
            ]);
        } else if ($_SESSION['user_role'] == 3) {
            $stats = [
                'total_users' => $this->userModel->getTotalUsers(),
                'total_courses' => $this->courseModel->getTotalCourses(),
                'total_teachers' => $this->userModel->getTotalTeachers(),
                'pending_teachers' => $this->userModel->getPendingTeachers(),
            ];

            $topCourses = $this->courseModel->getTopCourses(5);
            $topTeachers = $this->userModel->getTopTeachers(3);
            $this->render('users/admin/dashboard', [
                'stats' => $stats,
                'topCourses' => $topCourses,
                'topTeachers' => $topTeachers
            ]);
        }
    }
}
