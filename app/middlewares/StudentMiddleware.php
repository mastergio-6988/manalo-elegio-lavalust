<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentMiddleware
{
    public function handle(Closure $next)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['student_access'])) {
            header('Location: ' . rtrim(config_item('base_url'), '/') . '/student?notice=profile-locked', true, 302);
            exit;
        }

        return $next();
    }
}
