<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Models\User;

class UserController extends Controller {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }


    public function index() {
        $users = $this->userModel->findAll();
        $this->render("users/index", ["users" => $users]);
    }

    public function store() {
        $data = $_POST;
        $this->userModel->create($data);
        $this->redirect("users");
    }

    public function edit($id) {
        error_log("Editing user with ID: " . $id);
        error_log("Calling findById with ID: " . $id);
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            error_log("User not found for ID: " . $id);
            echo "User not found.";
            exit;
        }
        $this->render("users/edit", ["user"=> $user]);
    }

    public function update($id) {
        $data = $_POST;
        $this->userModel->update($id, $data);
        $this->redirect('users'); 
        exit;
    }

    public function delete($id) {
        $this->userModel->delete($id);
        $this->redirect('users'); 
        exit;
    }

    public function manageRole($id) {
        $user = $this->userModel->findById($id);
        if (!$user) {
            echo "User not found.";
            exit;
        }
        $this->render("users", ["user" => $user]);
    }

    public function updateRole($id) {
        $data = $_POST;
        $this->userModel->setRole($id, $data["role"]);
        $this->redirect("users");
        exit;
    }

    
}
