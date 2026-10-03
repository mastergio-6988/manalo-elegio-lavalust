<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/
/** @var object $router **/

$router->get('/', 'Welcome::index');
$router->get('/users', 'UsersController::index');
$router->get('/login', 'AuthController::login');
$router->post('/login', 'AuthController::authenticate');
$router->post('/logout', 'AuthController::logout');
$router->get('/products', 'ProductController::index');
$router->get('/products/create', 'ProductController::create');
$router->post('/products/create', 'ProductController::store');
$router->get('/products/edit/{id}', 'ProductController::edit')->where('id', '[0-9]+');
$router->post('/products/edit/{id}', 'ProductController::update')->where('id', '[0-9]+');
$router->post('/products/delete/{id}', 'ProductController::delete')->where('id', '[0-9]+');

// Authenticated product API used by the React laboratory frontend.
$router->post('/api/auth/login', 'ProductApiController::login');
$router->post('/api/auth/refresh', 'ProductApiController::refresh');
$router->post('/api/auth/logout', 'ProductApiController::logout');
$router->get('/api/products', 'ProductApiController::index');
$router->post('/api/products', 'ProductApiController::store');
$router->put('/api/products/{id}', 'ProductApiController::update')->where('id', '[0-9]+');
$router->patch('/api/products/{id}', 'ProductApiController::update')->where('id', '[0-9]+');
$router->delete('/api/products/{id}', 'ProductApiController::delete')->where('id', '[0-9]+');

// Internal route used only by the local PHP CLI migration command.
$router->get('/_cli/migration/{action}', 'MigrationController::run')->where('action', '[a-z-]+');
$router->get('/_cli/migration/create/{migration_name}', 'MigrationController::create')->where('migration_name', '[a-zA-Z0-9_-]+');

$router->get('/users', 'UsersController::index');
