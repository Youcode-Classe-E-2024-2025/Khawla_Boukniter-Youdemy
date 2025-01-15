<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class DashboardController extends Controller {

    public function index() {

        if ($_SESSION["user_role"] == "3") {
            error_log("Redirection vers: admin/dashboard");
            $this->render('users/admin/dashboard');
        }else if ($_SESSION["user_role"] == "2") {
            error_log("Redirection vers: professeur/dashboard");
            $this->render('users/professeur/dashboard');
        }else if ($_SESSION["user_role"] == "1") {
            error_log("Redirection vers: etudiant/dashboard");
            $this->render('users/etudiant/dashboard');
        } else {
            error_log("Redirection vers: choose_role");
            $this->render('choose_role');
        }
    }
}