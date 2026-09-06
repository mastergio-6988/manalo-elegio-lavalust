<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller
{
    private array $student = [
        'student_id' => 'MCC2024-00138',
        'name' => 'Gio Manalo',
        'course' => 'BS Information Technology',
        'year' => '3rd Year',
        'section' => 'F2',
        'email' => 'manalo.elegioe@minsu.edu.ph',
        'address' => 'Sitio Eastern, Brgy. Personas, Calapan City, Oriental Mindoro',
        'skills' => 'PHP, HTML, CSS, and web application development',
        'profile_description' => 'An IT student building practical, secure web applications with LavaLust.',
    ];

    public function index()
    {
        $this->call->view('student/home', [
            'student' => $this->student,
            'notice' => $_GET['notice'] ?? '',
            'has_access' => $this->hasAccess(),
        ]);
    }

    public function grantAccess()
    {
        $this->startSession();
        $_SESSION['student_access'] = true;
        $_SESSION['student_access_granted_at'] = date('c');
        redirect('student/profile');
    }

    public function revokeAccess()
    {
        $this->startSession();
        unset($_SESSION['student_access'], $_SESSION['student_access_granted_at']);
        redirect('student?notice=access-revoked');
    }

    public function profile()
    {
        $this->call->view('student/profile', [
            'student' => $this->student,
            'access_granted_at' => $_SESSION['student_access_granted_at'] ?? '',
        ]);
    }

    public function history()
    {
        $this->call->view('student/history', [
            'student' => $this->student,
        ]);
    }

    private function hasAccess(): bool
    {
        $this->startSession();
        return !empty($_SESSION['student_access']);
    }

    private function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
