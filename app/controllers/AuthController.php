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
            if (!verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['error'] = "Token de sécurité invalide";
                $this->redirect('login');
                return;
            }

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
        if ($this->isPost()) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['error'] = "Token de sécurité invalide";
                $this->redirect('register');
                return;
            }
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

    public function logout()
    {
        session_destroy();
        $this->redirect('');
    }
}
