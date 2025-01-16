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

            error_log("Tentative de connexion avec l'email: " . $data['email']);

            $user = $this->userModel->findByEmail($data['email']);

            error_log("Résultat de findByEmail: " . print_r($user, true));

            if ($user && password_verify($data['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nom'] . ' ' . $user['prenom'];

                error_log("Connexion réussie pour l'utilisateur ID: " . $user['id'] . " et rôle: " . $user['role_id'] . " et email: " . $user['email'] . " et nom: " . $user['nom'] . " et prénom: " . $user['prenom']);

                if ($user['role_id'] === 3) {
                    error_log("Redirection vers: admin/dashboard");
                    $this->redirect('dashboard');
                } else if ($user['role_id'] === 2) {
                    error_log("Redirection vers: teacher/dashboard");
                    $this->redirect('dashboard');
                } else if ($user['role_id'] === 1) {
                    error_log("Redirection vers: student/dashboard");
                    $this->redirect('dashboard');
                } else {
                    error_log("Redirection vers: choose_role");
                    $this->redirect('choose_role');
                }
                return true;
            } else {
                error_log("Échec de la connexion : email ou mot de passe incorrect pour l'email: " . $data['email']);
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
                $nom = sanitizeInput($_POST['nom'] ?? '');
                $prenom = sanitizeInput($_POST['prenom'] ?? '');
                $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';
                $role = $_POST['role_id'] ?? null;

                $_SESSION['signup_form'] = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email
                ];

                if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
                    throw new InvalidArgumentException('Tous les champs sont obligatoires.');
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException('Adresse email invalide.');
                }

                if ($this->userModel->findByEmail($email)) {
                    throw new InvalidArgumentException('L\'email est déjà utilisé.');
                }

                if (strlen($password) < 8) {
                    throw new InvalidArgumentException('Le mot de passe doit contenir au moins 8 caractères.');
                }

                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                    throw new InvalidArgumentException('Le mot de passe doit contenir au moins : 
                        - Une lettre minuscule
                        - Une lettre majuscule
                        - Un chiffre
                        - Un caractère spécial');
                }

                $role = filter_var($_POST['role_id'] ?? Role::STUDENT, FILTER_VALIDATE_INT, [
                    'options' => [
                        'max_range' => Role::ADMIN
                    ],
                    'default' => Role::STUDENT
                ]);

                error_log("Rôle : $role");

                $user = new User();
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $saveResult = $user->create([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => $hashed_password,
                    'role_id' => $role
                ]);

                error_log("Résultat de sauvegarde : " . ($saveResult ? 'Succès' : 'Échec'));

                if (!$saveResult) {
                    throw new Exception('Impossible de créer l\'utilisateur. L\'email existe peut être déjà.');
                }

                $loginResult = $this->login();
                
                error_log("Résultat de connexion : " . ($loginResult ? 'Succès' : 'Échec'));
                
                if ($loginResult) {
                    unset($_SESSION['signup_form']);
                    unset($_SESSION['signup_error']);

                    redirect('views/');
                    if ($user->role_id == Role::TEACHER) {
                        redirect('teacher/dashboard');
                    } else if ($user->role_id == Role::ADMIN) {
                        redirect('admin/dashboard');
                    } else {
                        redirect('student/dashboard');
                    }

                } else {
                    throw new Exception('Échec de la connexion après inscription.');
                }

            } catch (Exception $e) {
                $_SESSION['signup_error'] = $e->getMessage();
                redirect('auth/register');
                exit;
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
