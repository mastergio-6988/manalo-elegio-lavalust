<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Users extends Controller
{
    public function index()
    {
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - LavaLust</title>
    <style>
        body {
            margin: 0;
            background: #0a0a0b;
            color: #f4f4f5;
            font-family: Arial, sans-serif;
            display: grid;
            place-items: center;
            min-height: 100vh;
        }
        .card {
            background: #111113;
            border: 1px solid rgba(221,72,20,.35);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.4);
        }
        h1 { color: #dd4814; }
        a {
            color: white;
            text-decoration: none;
            background: #dd4814;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Users Controller Works!</h1>
        <p>LavaLust routing is working correctly.</p>
        <p><strong>GET /users</strong> successfully reached Users::index().</p>
        <a href="/">← Back Home</a>
    </div>
</body>
</html>';
    }
}
