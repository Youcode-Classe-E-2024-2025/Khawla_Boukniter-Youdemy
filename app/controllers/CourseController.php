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
        $categorie = $this->courseModel->getCategorie();
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

    public function store()
    {
        error_log("Starting course creation process");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("POST data received: " . print_r($_POST, true));

            // Prepare course data
            $courseData = [
                'titre' => htmlspecialchars(filter_input(INPUT_POST, 'titre', FILTER_DEFAULT) ?? ''),
                'description' => htmlspecialchars(filter_input(INPUT_POST, 'description', FILTER_DEFAULT) ?? ''),
                'categorie_id' => filter_input(INPUT_POST, 'categorie_id', FILTER_VALIDATE_INT),
                'enseignant_id' => $_SESSION['user_id'],
            ];

            // Log the course data for debugging
            error_log("Processed course data: " . print_r($courseData, true));

            try {
                // Create course and get its ID
                $courseId = $this->courseModel->create($courseData);

                // Handle tags
                if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                    foreach ($_POST['tags'] as $tagId) {
                        $this->courseModel->addTag($courseId, $tagId);
                    }
                }

                $_SESSION['success'] = "Cours créé avec succès.";
            } catch (\Exception $e) {
                error_log("Error creating course: " . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de la création du cours: " . $e->getMessage();
            }

            $this->redirect('dashboard');


            // Create the course
            $courseId = $this->courseModel->create($courseData);

            // Check if the course was created successfully
            if (!$courseId) {
                $_SESSION['error'] = "Erreur lors de la création du cours.";
                $this->redirect('teacher/courses/create');
            }

            $_SESSION['success'] = "Cours créé avec succès.";
            $this->redirect('teacher/courses');

            if (isset($_FILES['attachments']) && $_FILES['attachments']['error'][0] === UPLOAD_ERR_OK) {
                foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
                    $fileName = $_FILES['attachments']['name'][$key];
                    $filePath = BASE_PATH . '/public/uploads/' . basename($fileName);

                    if (move_uploaded_file($tmpName, $filePath)) {
                        $attachmentData = [
                            'name' => $fileName,
                            'path' => $filePath,
                            'cours_id' => $courseId,
                        ];
                        $this->attachmentModel->create($attachmentData);
                    }
                }
            }
        }
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
}
