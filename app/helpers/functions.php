<?php

function base_url($path = '')
{
    $baseUrl = '/Khawla_Boukniter-Youdemy/public';
    return $baseUrl . '/' . ltrim($path, '/');
}

function asset_url($path)
{
    return base_url('assets/' . ltrim($path, '/'));
}

function current_user()
{
    return $_SESSION['user_id'] ?? null;
}

function user_name()
{
    return $_SESSION['user_name'] ?? 'Utilisateur';
}

function user_role()
{
    return $_SESSION['user_role'] ?? '';
}

function is_authenticated()
{
    return isset($_SESSION['user_id']);
}

function is_teacher()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'teacher';
}

function is_student()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student';
}

function is_admin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function format_date($date)
{
    if (empty($date)) return 'Non définie';
    return date('d/m/Y', strtotime($date));
}

function get_status_color($status)
{
    return match ($status) {
        'todo' => 'warning',
        'doing' => 'info',
        'done' => 'success',
        default => 'light'
    };
}

function get_status_label($status)
{
    return match ($status) {
        'todo' => 'À faire',
        'doing' => 'En cours',
        'done' => 'Terminé',
        default => 'Inconnu'
    };
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf_token($token)
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function escape_html($text)
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function set_flash_message($type, $message)
{
    $_SESSION[$type] = $message;
}

function get_flash_message($type)
{
    $message = $_SESSION[$type] ?? '';
    unset($_SESSION[$type]);
    return $message;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}