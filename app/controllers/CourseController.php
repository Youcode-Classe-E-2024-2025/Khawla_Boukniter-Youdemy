<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Attachment;
use App\Models\User;

class CourseController extends Controller
{

    private $attachmentModel;
    private $courseModel;

    private $userModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->attachmentModel = new Attachment();
        $this->userModel = new User();
    }

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $filters = [];

        $courses = $this->courseModel->getPublishedCourses($filters, $page, $limit);

        $total = $this->courseModel->getTotalCourses();

        $pages = ceil($total / $limit);

        $categories = $this->courseModel->getCategories();

        $this->render('index', ['courses' => $courses, 'categories' => $categories, 'pages' => $pages, 'page' => $page]);
    }

    public function browse()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $filters = [];

        $courses = $this->courseModel->getPublishedCourses($filters, $page, $limit);
        $total = $this->courseModel->getTotalCourses();
        $pages = ceil($total / $limit);
        $categories = $this->courseModel->getCategories();

        $this->render('courses/index', [
            'courses' => $courses,
            'categories' => $categories,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    public function show($id)
    {
        $course = $this->courseModel->getWithDetails($id);
        if (!$course) {
            $this->redirect('courses');
        }
        $categorie = $this->courseModel->getCourseCategory($id);
        $tags = $this->courseModel->getCourseTags($id);
        $attachments = $this->attachmentModel->getCourseAttachment($id);

        $isEnrolled = false;
        if (isset($_SESSION['user_id'])) {
            $isEnrolled = $this->courseModel->isEnrolled($_SESSION['user_id'], $id);
        }

        $this->render('courses/show', ['course' => $course, 'categorie' => $categorie, 'tags' => $tags, 'attachments' => $attachments, 'isEnrolled' => $isEnrolled]);
    }

    public function create()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 2) {
            $_SESSION['error'] = "Vous devez être un enseignant pour créer un cours.";
            $this->redirect('login');
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user['is_validated']) {
            $_SESSION['error'] = "Votre compte est en attente de validation par l'administrateur. Vous ne pouvez pas créer de cours pour le moment.";
            $this->redirect('dashboard');
            return;
        }

        $categories = $this->courseModel->getCategories();
        $tags = $this->courseModel->getTags();

        $this->render('users/teacher/create_course', ['categories' => $categories, 'tags' => $tags]);
    }

    public function saveStep1()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];

            if (empty($_POST['titre'])) {
                $errors[] = "Le titre du cours est requis";
            }
            if (empty($_POST['description'])) {
                $errors[] = "La description du cours est requise";
            }
            if (empty($_POST['categorie_id'])) {
                $errors[] = "La catégorie est requise";
            }
            if (empty($_POST['tags'])) {
                $errors[] = "Sélectionnez au moins un tag";
            }
            if (empty($_POST['content_type'])) {
                $errors[] = "Le type de contenu est requis";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $this->redirect('teacher/courses/create');
                return;
            }


            $_SESSION['course_data'] = [
                'titre' => $_POST['titre'],
                'description' => $_POST['description'],
                'categorie_id' => $_POST['categorie_id'],
                'content_type' => $_POST['content_type'],
                'tags' => $_POST['tags'] ?? []
            ];

            $this->redirect('teacher/courses/content');
        }
    }

    public function showContentForm()
    {
        if (!isset($_SESSION['course_data'])) {
            $this->redirect('teacher/courses/create');
        }

        $this->render('users/teacher/course_content');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['course_data'])) {
            error_log("Content type received: " . $_SESSION['course_data']['content_type']);


            $courseData = [
                'titre' => $_SESSION['course_data']['titre'],
                'description' => $_SESSION['course_data']['description'],
                'categorie_id' => $_SESSION['course_data']['categorie_id'],
                'enseignant_id' => $_SESSION['user_id'],
                'content_type' => $_SESSION['course_data']['content_type']
            ];

            error_log("Creating course with data: " . print_r($courseData, true));
            $courseId = $this->courseModel->create($courseData);

            if (!empty($_SESSION['course_data']['tags'])) {
                foreach ($_SESSION['course_data']['tags'] as $tagId) {
                    $this->courseModel->addTag($courseId, $tagId);
                }
            }

            if ($_SESSION['course_data']['content_type'] === 'video' && isset($_FILES['content_file'])) {
                $fileName = time() . '_' . $_FILES['content_file']['name'];
                $uploadDir = BASE_PATH . '/public/uploads/';
                $filePath = 'uploads/' . $fileName;

                if (move_uploaded_file($_FILES['content_file']['tmp_name'], $uploadDir . $fileName)) {
                    $attachmentData = [
                        'name' => $_FILES['content_file']['name'],
                        'path' => $filePath,
                        'cours_id' => $courseId
                    ];
                    $this->attachmentModel->create($attachmentData);
                }
            } elseif ($_SESSION['course_data']['content_type'] === 'document') {
                $content = $_POST['content_file'];
                $fileName = time() . '_' . str_replace([' ', '/', '\\'], '-', $courseData['titre']) . '.md';
                $uploadDir = BASE_PATH . '/public/uploads/';
                $filePath = 'uploads/' . $fileName;

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                file_put_contents($uploadDir . $fileName, $content);

                $attachmentData = [
                    'name' => $fileName,
                    'path' => $filePath,
                    'cours_id' => $courseId
                ];
                $this->attachmentModel->create($attachmentData);
            }


            unset($_SESSION['course_data']);
            $_SESSION['success'] = "Cours créé avec succès.";
            $this->redirect('dashboard');
        }
    }

    public function edit($id)
    {
        error_log("Editing course with ID: " . $id);

        $course = $this->courseModel->getWithDetails($id);
        error_log("Course data: " . print_r($course, true));

        if (!$course) {
            error_log("Course not found in database");
        }

        $categories = $this->courseModel->getCategories();
        $tags = $this->courseModel->getTags();
        $courseTags = $this->courseModel->getCourseTags($id);

        $this->render('users/teacher/edit_course', [
            'course' => $course,
            'categories' => $categories,
            'tags' => $tags,
            'courseTags' => $courseTags
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseData = [
                'titre' => $_POST['titre'],
                'description' => $_POST['description'],
                'categorie_id' => $_POST['categorie_id'],
                'content_type' => $_POST['content_type']
            ];

            $this->courseModel->update($id, $courseData);

            if (!empty($_POST['tags'])) {
                $this->courseModel->removeTags($id);
                foreach ($_POST['tags'] as $tagId) {
                    $this->courseModel->addTag($id, $tagId);
                }
            }

            $_SESSION['success'] = "Cours mis à jour avec succès.";
            $this->redirect('dashboard');
        }
    }

    public function delete($id)
    {
        $this->courseModel->removeTags($id);
        $this->courseModel->delete($id);
        $_SESSION['success'] = "Cours supprimé avec succès.";
        $this->redirect('dashboard');
    }

    public function viewEnrollments($courseId)
    {
        $course = $this->courseModel->getWithDetails($courseId);
        $enrollments = $this->courseModel->getEnrollments($courseId);

        $this->render('users/teacher/course_enrollments', [
            'course' => $course,
            'enrollments' => $enrollments
        ]);
    }

    public function enroll($courseId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 1) {
            $_SESSION['error'] = "Vous devez être un étudiant pour vous inscrire à ce cours.";
            $this->redirect('login');
        }

        $this->courseModel->enroll($_SESSION['user_id'], $courseId);
        $_SESSION['success'] = "Vous êtes maintenant inscrit au cours.";
        $this->redirect("courses/$courseId");
    }

    public function teacherCourses()
    {
        $courses = $this->courseModel->getTeacherCourses($_SESSION['user_id']);

        foreach ($courses as &$course) {
            $course['category'] = $this->courseModel->getCourseCategory($course['id']);
            $course['tags'] = $this->courseModel->getCourseTags($course['id']);
        }

        $this->render('users/teacher/courses', ['courses' => $courses]);
    }

    public function dashboard()
    {
        $latestCourses = $this->courseModel->getLatestTeacherCourses($_SESSION['user_id'], 3);
        $stats = [
            'total_courses' => $this->courseModel->getTotalCoursesByTeacher($_SESSION['user_id']),
            'total_students' => $this->courseModel->getTotalStudentsByTeacher($_SESSION['user_id'])
        ];

        $this->render('users/teacher/dashboard', [
            'latestCourses' => $latestCourses,
            'stats' => $stats
        ]);
    }

    public function studentCourses()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 1) {
            $this->redirect('login');
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $enrolledCourses = $this->courseModel->getStudentCourses($_SESSION['user_id'], $limit, $offset);
        $total_courses = $this->courseModel->getEnrolledCoursesCount($_SESSION['user_id']);
        $total_pages = ceil($total_courses / $limit);

        $this->render('users/student/courses', [
            'enrolledCourses' => $enrolledCourses,
            'current_page' => $page,
            'total_pages' => $total_pages
        ]);
    }

    public function unenroll($courseId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 1) {
            $_SESSION['error'] = "Vous devez être connecté en tant qu'étudiant.";
            $this->redirect('login');
        }

        $this->courseModel->unenroll($_SESSION['user_id'], $courseId);
        $_SESSION['success'] = "Vous vous êtes désinscrit du cours avec succès.";
        $this->redirect('student/courses');
    }

    public function search()
    {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;

        $results = empty($keyword) ?
            $this->courseModel->getPublishedCourses([], $page, $limit) :
            $this->courseModel->search($keyword, $page, $limit);

        $total = empty($keyword) ?
            $this->courseModel->getTotalCourses() :
            $this->courseModel->getTotalSearchResults($keyword);

        $total_pages = ceil($total / $limit);
        $categories = $this->courseModel->getCategories();

        $this->render('courses/search', [
            'courses' => $results,
            'keyword' => $keyword,
            'categories' => $categories,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total' => $total
        ]);
    }

    public function adminCourses()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $offset = ($currentPage - 1) * $perPage;

        $totalCourses = $this->courseModel->getTotalCourses();
        $courses = $this->courseModel->getAllCoursesWithDetails($perPage, $offset);

        $totalPages = ceil($totalCourses / $perPage);
        $startCount = $offset + 1;
        $endCount = min($offset + $perPage, $totalCourses);

        $this->render('users/admin/courses', [
            'courses' => $courses,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalCourses' => $totalCourses,
            'startCount' => $startCount,
            'endCount' => $endCount
        ]);
    }

    public function adminDeleteCourse($id)
    {
        if ($this->courseModel->delete($id)) {
            $_SESSION['success'] = "Cours supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du cours";
        }
        $this->redirect('users/admin/courses');
    }
}
