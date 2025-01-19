<?php

namespace App\Controllers;

use App\Core\Controller;
use InvalidArgumentException;
use App\Models\User;
use App\Models\Role;
use Exception;

require_once __DIR__ . '/../helpers/url_helper.php';

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginForm()
    {
        include VIEW_PATH . '/auth/login.php';
    }

    public function login()
    {
        if ($this->isPost()) {
            $data = $this->getPostData();

            if (empty($data['email']) || empty($data['password'])) {
                $_SESSION['error'] = "Tous les champs sont obligatoires";
                $this->redirect('login');
                return;
            }

            $user = $this->userModel->findByEmail($data['email']);
            error_log("Login attempt - Raw password: " . $data['password']);
            error_log("Login attempt - Stored hash: " . ($user ? $user['password'] : 'No user found'));
            error_log("Password verify result: " . (password_verify($data['password'], $user['password']) ? 'true' : 'false'));

            if ($user && password_verify($data['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nom'] . ' ' . $user['prenom'];

                $this->redirect('dashboard');

                return true;
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect";
                $this->redirect('login');
                return false;
            }
        }
    }

    public function registerForm()
    {
        include VIEW_PATH . '/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                error_log("Password before hash: " . $_POST['password']);
                $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                error_log("Password after hash: " . $hashedPassword);

                $data = [
                    'nom' => sanitizeInput($_POST['nom'] ?? ''),
                    'prenom' => sanitizeInput($_POST['prenom'] ?? ''),
                    'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
                    'password' => $hashedPassword,
                    'role_id' => $_POST['role_id'] ?? null,
                    'is_validated' => ($_POST['role_id'] == 2) ? 0 : 1
                ];
                error_log("Registration data prepared: " . print_r($data, true));

                // $_SESSION['signup_form'] = [
                //     'nom' => $nom,
                //     'prenom' => $prenom,
                //     'email' => $email
                // ];

                if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['password'])) {
                    throw new InvalidArgumentException('Tous les champs sont obligatoires.');
                }

                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException('Adresse email invalide.');
                }

                if ($this->userModel->findByEmail($_POST['email'])) {
                    throw new InvalidArgumentException('L\'email est déjà utilisé.');
                }

                if (strlen($_POST['password']) < 8) {
                    throw new InvalidArgumentException('Le mot de passe doit contenir au moins 8 caractères.');
                }

                // if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                //     throw new InvalidArgumentException('Le mot de passe doit contenir au moins : 
                //         - Une lettre minuscule
                //         - Une lettre majuscule
                //         - Un chiffre
                //         - Un caractère spécial');
                // }


                $result = $this->userModel->create($data);

                if ($result) {
                    error_log("User created successfully");
                    $_SESSION['success'] = ($data['role_id'] == 2)
                        ? "Compte créé avec succès. En attente de validation par l'administrateur."
                        : "Compte créé avec succès.";
                    error_log("Redirecting to login");
                    $this->redirect('login');
                    return;
                }
                error_log("Failed to create user");
                throw new Exception('Erreur lors de la création du compte.');
            } catch (Exception $e) {
                error_log("Registration error: " . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
                redirect('register');
            }
        }
    }

    public function chooseRoleForm()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
            return;
        }
        $this->render('auth/choose_role', ['pageTitle' => 'Choisir un Rôle']);
    }

    public function chooseRole()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('login');
            return;
        }

        if ($this->isPost()) {
            $data = $this->getPostData();
            $role = $data['role'] ?? null;

            if ($role && in_array($role, ['manager', 'member'])) {
                $this->userModel->setRole($_SESSION['user_id'], $role);
                $_SESSION['user_role'] = $role;
                $_SESSION['success'] = "Rôle défini avec succès.";
                $this->redirect('dashboard');
            } else {
                $_SESSION['error'] = "Rôle invalide.";
                $this->redirect('choose_role');
            }
        }

        $this->render('auth/choose_role', ['pageTitle' => 'Choisir un Rôle']);
    }

    public function manageRoles()
    {
        if (!$this->isAuthenticated() || $_SESSION['user_role'] !== 3) {
            $_SESSION['error'] = "Vous n'avez pas les permissions nécessaires.";
            $this->redirect('dashboard');
            return;
        }

        if ($this->isPost()) {
            $data = $this->getPostData();
            $userId = $data['user_id'];
            $role = $data['role'];

            if ($this->userModel->updateRole($userId, $role)) {
                $_SESSION['success'] = "Rôle mis à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du rôle.";
            }

            $this->redirect('manage_roles');
        }

        // Afficher la vue de gestion des rôles
        $this->render('auth/manage_roles', ['pageTitle' => 'Gérer les Rôles']);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('');
    }
}
