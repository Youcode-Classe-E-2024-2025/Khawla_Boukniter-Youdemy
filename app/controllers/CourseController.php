<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Attachment;

class CourseController extends Controller
{

    private $attachmentModel;
    private $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->attachmentModel = new Attachment();
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

    public function show($id)
    {
        $course = $this->courseModel->getWithDetails($id);
        if (!$course) {
            $this->redirect('courses');
        }
        $categorie = $this->courseModel->getCourseCategory($id);
        $tags = $this->courseModel->getCourseTags($id);

        $this->render('courses/show', ['course' => $course, 'categorie' => $categorie, 'tags' => $tags]);
    }

    public function create()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 2) {
            $_SESSION['error'] = "Vous devez être un enseignant pour créer un cours.";
            $this->redirect('login');
        }

        $categories = $this->courseModel->getCategories();
        $tags = $this->courseModel->getTags();

        $this->render('users/teacher/create_course', ['categories' => $categories, 'tags' => $tags]);
    }

    public function saveStep1()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                $courseData = array_merge($_SESSION['course_data'], [
                    'enseignant_id' => $_SESSION['user_id'],
                ]);

                if (
                    empty($courseData['tags']) || empty($courseData['description']) ||
                    empty($courseData['titre']) || empty($courseData['categorie_id']) ||
                    empty($courseData['content_type'])
                ) {
                    $_SESSION['error'] = "Veuillez remplir tous les champs.";
                    $this->redirect('teacher/courses/create');
                    return;
                }
                $courseId = $this->courseModel->create($courseData);

                if ($courseData['content_type'] === 'video' && isset($_FILES['video_content'])) {
                    $file = $_FILES['video_content'];

                    $fileName = time() . '_' . $file['name'];
                    $uploadPath = PUBLIC_PATH . '/uploads/videos/' . $fileName;

                    if (!is_dir(PUBLIC_PATH . '/uploads/videos/')) {
                        mkdir(PUBLIC_PATH . '/uploads/videos/', 0777, true);
                    }

                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $attachmentData = [
                            'name' => $file['name'],
                            'path' => $uploadPath,
                            'cours_id' => $courseId
                        ];
                        $result = $this->attachmentModel->create($attachmentData);
                    }
                } else if ($courseData['content_type'] === 'document' && isset($_POST['document_content'])) {
                    $fileName = 'document_' . time() . '.md';
                    $filePath = PUBLIC_PATH . '/uploads/documents/' . $fileName;

                    if (!is_dir(PUBLIC_PATH . '/uploads/documents/')) {
                        mkdir(PUBLIC_PATH . '/uploads/documents/', 0777, true);
                    }

                    file_put_contents($filePath, $_POST['document_content']);

                    $attachmentData = [
                        'name' => $fileName,
                        'path' => $filePath,
                        'cours_id' => $courseId
                    ];
                    $this->attachmentModel->create($attachmentData);
                }

                if (!empty($courseData['tags'])) {
                    foreach ($courseData['tags'] as $tagId) {
                        $this->courseModel->addTag($courseId, $tagId);
                    }
                }

                unset($_SESSION['course_data']);
                $_SESSION['success'] = "Cours créé avec succès.";
            } catch (\Exception $e) {
                $_SESSION['error'] = "Erreur lors de la création du cours: " . $e->getMessage();
            }

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
}
