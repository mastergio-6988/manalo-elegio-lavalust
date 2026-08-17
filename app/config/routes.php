<?php

require_once APP_DIR . 'config/middleware.php';

$router->get('/', 'StudentController::index');
$router->get('/users', 'Users::index');
$router->get('/student', 'StudentController::index');
$router->get('/student/access', 'StudentController::grantAccess');
$router->get('/student/revoke', 'StudentController::revokeAccess');
$router->get('/student/profile', 'StudentController::profile')->middleware('student.access');
$router->get('/student/history', 'StudentController::history');
