<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Tag;

class CategoriesTagsController extends Controller
{
    private $categoryModel;
    private $tagModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 3) {
            $_SESSION['error'] = "Accès non autorisé";
            $this->redirect('login');
        }
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
    }

    public function index()
    {
        $categories = $this->categoryModel->getAllWithCount();
        $tags = $this->tagModel->getAll();

        $this->render('users/admin/categories_tags', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function addCategory()
    {
        if ($this->isPost()) {
            $name = $_POST['name'];
            if ($this->categoryModel->create(['name' => $name])) {
                $_SESSION['success'] = "Catégorie ajoutée avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de la catégorie";
            }
            $this->redirect('users/admin/categories-tags');
        }
    }

    public function deleteCategory($id)
    {
        $result = $this->categoryModel->delete($id);
        if ($result === 'has_courses') {
            $_SESSION['error'] = "Impossible de supprimer cette catégorie car elle contient des cours";
        } else if ($result) {
            $_SESSION['success'] = "Catégorie supprimée avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de la catégorie";
        }
        $this->redirect('users/admin/categories-tags');
    }

    public function addTags()
    {
        if ($this->isPost()) {
            $tagsInput = $_POST['tags'];
            $tagsArray = json_decode($tagsInput, true);

            if (is_array($tagsArray)) {
                foreach ($tagsArray as $tag) {
                    if (!empty($tag['value'])) {
                        $this->tagModel->create(['name' => $tag['value']]);
                    }
                }
                $_SESSION['success'] = "Tags ajoutés avec succès";
            }

            $this->redirect('users/admin/categories-tags');
        }
    }


    public function deleteTag($id)
    {
        if ($this->tagModel->delete($id)) {
            $_SESSION['success'] = "Tag supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du tag";
        }
        $this->redirect('users/admin/categories-tags');
    }
}
