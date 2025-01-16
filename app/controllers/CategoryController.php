<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Course;

class CategoryController extends Controller
{
    private Category $categoryModel;
    private Course $courseModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->courseModel = new Course();
    }

    public function index(int $courseId)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $course = $this->courseModel->findById($courseId);
        if (!$course) {
            $this->redirect('/courses');
        }

        $isManager = $_SESSION['user_role'] === 'manager' &&
            $course['manager_id'] === $_SESSION['user_id'];

        $categories = $this->categoryModel->getByCourse($courseId);

        $this->render('categories/index', [
            'course' => $course,
            'categories' => $categories,
            'isManager' => $isManager
        ]);
    }

    public function create(int $courseId)
    {
        if (!$this->isAuthenticated() || $_SESSION['user_role'] !== 'manager') {
            $this->redirect('/courses');
        }

        $course = $this->courseModel->findById($courseId);
        if (!$course || $course['manager_id'] !== $_SESSION['user_id']) {
            $this->redirect('/courses');
        }

        if ($this->isPost()) {
            $data = $this->getPostData();

            if (empty($data['name'])) {
                $_SESSION['error'] = "Le nom de la catégorie est requis";
                $this->redirect("/courses/$courseId/categories");
            }

            $data['course_id'] = $courseId;

            if ($this->categoryModel->create($data)) {
                $_SESSION['success'] = "Catégorie créée avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la création de la catégorie";
            }

            $this->redirect("/courses/$courseId/categories");
        }

        $this->render('categories/create', ['course' => $course]);
    }

    public function edit(int $courseId, int $categoryId)
    {
        if (!$this->isAuthenticated() || $_SESSION['user_role'] !== 'manager') {
            $this->redirect('/courses');
        }

        $course = $this->courseModel->findById($courseId);
        if (!$course || $course['manager_id'] !== $_SESSION['user_id']) {
            $this->redirect('/courses');
        }

        $category = $this->categoryModel->findById($categoryId);
        if (!$category || $category['course_id'] !== $courseId) {
            $this->redirect("/courses/$courseId/categories");
        }

        if ($this->isPost()) {
            $data = $this->getPostData();

            if (empty($data['name'])) {
                $_SESSION['error'] = "Le nom de la catégorie est requis";
                $this->redirect("/courses/$courseId/categories/$categoryId/edit");
            }

            if ($this->categoryModel->update($categoryId, $data['name'], $courseId)) {
                $_SESSION['success'] = "Catégorie mise à jour avec succès";
                $this->redirect("/courses/$courseId/categories");
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour de la catégorie";
                $this->redirect("/courses/$courseId/categories/$categoryId/edit");
            }
        }

        $this->render('categories/edit', [
            'course' => $course,
            'category' => $category
        ]);
    }

    public function delete(int $projectId, int $categoryId)
    {
        if (
            !$this->isAuthenticated() ||
            $_SESSION['user_role'] !== 'manager' ||
            !$this->isPost()
        ) {
            $this->redirect('/courses');
        }

        $course = $this->courseModel->findById($courseId);
        if (!$course || $course['manager_id'] !== $_SESSION['user_id']) {
            $this->redirect('/courses');
        }

        $category = $this->categoryModel->findById($categoryId);
        if (!$category || $category['course_id'] !== $courseId) {
            $this->redirect("/courses/$courseId/categories");
        }

        if ($this->categoryModel->delete($categoryId, $courseId)) {
            $_SESSION['success'] = "Catégorie supprimée avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de la catégorie";
        }

        $this->redirect("/projects/$projectId/categories");
    }

    public function show(int $projectId, int $categoryId)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $course = $this->courseModel->findById($courseId);
        if (!$course) {
            $this->redirect('/courses');
        }

        $category = $this->categoryModel->findById($categoryId);
        if (!$category || $category['course_id'] !== $courseId) {
            $this->redirect("/courses/$courseId/categories");
        }

        $tasks = $this->categoryModel->getTasksByCategory($categoryId);
        $isManager = $_SESSION['user_role'] === 'manager' &&
            $course['manager_id'] === $_SESSION['user_id'];

        $this->render('categories/show', [
            'course' => $course,
            'category' => $category,
            'tasks' => $tasks,
            'isManager' => $isManager
        ]);
    }
}
