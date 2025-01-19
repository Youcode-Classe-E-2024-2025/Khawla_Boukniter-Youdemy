<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware\Auth;
use App\Models\User;

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // public function index()
    // {
    //     $users = $this->userModel->findAll();
    //     $this->render("users/index", ["users" => $users]);
    // }

    public function store()
    {
        $data = $_POST;
        $data['is_validated'] = ($data['role_id'] == 2) ? 0 : 1;
        $this->userModel->create($data);
        $this->redirect("users");
    }

    public function deleteUser($id)
    {
        if ($this->isPost()) {
            if ($this->userModel->delete($id)) {
                $_SESSION['success'] = "Utilisateur supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur";
            }
            $this->redirect('users/admin/users');
        }
    }

    public function suspendUser($id)
    {
        if ($this->userModel->updateStatus($id, 0)) {
            $_SESSION['success'] = "Utilisateur suspendu avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suspension de l'utilisateur";
        }
        $this->redirect('users/admin/users');
    }

    public function activateUser($id)
    {
        if ($this->userModel->updateStatus($id, 1)) {
            $_SESSION['success'] = "Utilisateur activé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de l'activation de l'utilisateur";
        }
        $this->redirect('users/admin/users');
    }

    public function inscriptions()
    {
        Auth::checkRole([3]);
        $pendingTeachers = $this->userModel->getPendingTeachersDetails();
        $this->render('users/admin/inscriptions', ['pendingTeachers' => $pendingTeachers]);
    }

    public function validateTeacher()
    {
        Auth::checkRole([3]);
        if ($this->isPost()) {
            $userId = $_POST['user_id'];
            if ($this->userModel->validateTeacher($userId)) {
                $_SESSION['success'] = "Enseignant validé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la validation de l'enseignant";
            }
            $this->redirect('users/admin/inscriptions');
        }
    }

    public function rejectTeacher()
    {
        Auth::checkRole([3]);
        if ($this->isPost()) {
            $userId = $_POST['user_id'];
            if ($this->userModel->rejectTeacher($userId)) {
                $_SESSION['success'] = "Enseignant rejeté avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors du rejet de l'enseignant";
            }
            $this->redirect('users/admin/inscriptions');
        }
    }

    public function users()
    {
        $users = $this->userModel->getAllUsersWithDetails();
        $this->render('users/admin/users', ['users' => $users]);
    }
}
