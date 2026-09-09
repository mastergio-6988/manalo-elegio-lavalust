<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    private function startSession()
    {
        if (session_status() === PHP_SESSION_ACTIVE) return;
        session_name('product_manager_session');
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => getenv('APP_ENV') === 'production', 'httponly' => true, 'samesite' => 'Strict']);
        session_start();
    }

    public function login()
    {
        $this->startSession();
        $this->call->view('auth/login', ['error' => '']);
    }

    public function authenticate()
    {
        $this->startSession();
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $configuredUsername = (string) getenv('PRODUCT_ADMIN_USERNAME');
        $configuredPassword = (string) getenv('PRODUCT_ADMIN_PASSWORD');

        if ($configuredUsername === '' || $configuredPassword === '') {
            http_response_code(503);
            $this->call->view('auth/login', ['error' => 'Product login is not configured. Set PRODUCT_ADMIN_USERNAME and PRODUCT_ADMIN_PASSWORD.']);
            return;
        }

        $now = time();
        if (($_SESSION['product_login_locked_until'] ?? 0) > $now) {
            $this->call->view('auth/login', ['error' => 'Too many sign-in attempts. Please try again later.']);
            return;
        }

        if (hash_equals($configuredUsername, $username) && hash_equals($configuredPassword, $password)) {
            session_regenerate_id(true);
            unset($_SESSION['product_login_attempts'], $_SESSION['product_login_locked_until']);
            $_SESSION['product_user'] = $username;
            redirect('products'); exit;
        }

        $_SESSION['product_login_attempts'] = (int) ($_SESSION['product_login_attempts'] ?? 0) + 1;
        if ($_SESSION['product_login_attempts'] >= 5) {
            $_SESSION['product_login_attempts'] = 0;
            $_SESSION['product_login_locked_until'] = $now + 900;
        }
        $this->call->view('auth/login', ['error' => 'Invalid username or password.']);
    }

    public function logout()
    {
        $this->startSession();
        $_SESSION = [];
        session_destroy();
        redirect('login'); exit;
    }
}
