<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller
{
    public function index()
    {
        $this->call->database();
        $this->call->model('UsersModel');

        $data['users'] = $this->UsersModel->all();

        $this->call->view('users', $data);
    }
}
