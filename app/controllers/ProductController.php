<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    private function auth()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_name('product_manager_session'); session_start(); }
        if (empty($_SESSION['product_user'])) { redirect('login'); exit; }
    }
    private function boot() { $this->call->database(); $this->call->model('ProductModel'); }
    private function data() { return ['product_name' => trim($_POST['product_name'] ?? ''), 'description' => trim($_POST['description'] ?? ''), 'price' => trim($_POST['price'] ?? ''), 'quantity' => trim($_POST['quantity'] ?? '')]; }
    private function valid(array $data) { return $data['product_name'] !== '' && mb_strlen($data['product_name']) <= 100 && is_numeric($data['price']) && (float) $data['price'] >= 0 && filter_var($data['quantity'], FILTER_VALIDATE_INT) !== false && (int) $data['quantity'] >= 0; }
    public function index() { $this->auth(); $this->boot(); $this->call->view('products/index', ['products' => $this->ProductModel->all()]); }
    public function create() { $this->auth(); $this->form([], 'products/create', 'Add product', ''); }
    public function store() { $this->auth(); $data = $this->data(); if (!$this->valid($data)) { $this->form($data, 'products/create', 'Add product', 'Enter a name of at most 100 characters, a non-negative price, and a non-negative whole-number quantity.'); return; } $this->boot(); $this->ProductModel->insert($data); redirect('products'); exit; }
    public function edit($id) { $this->auth(); $this->boot(); $product = $this->ProductModel->find((int) $id); if (!$product) { redirect('products'); exit; } $this->form($product, 'products/edit/' . (int) $id, 'Edit product', ''); }
    public function update($id) { $this->auth(); $data = $this->data(); if (!$this->valid($data)) { $this->form($data, 'products/edit/' . (int) $id, 'Edit product', 'Enter a name of at most 100 characters, a non-negative price, and a non-negative whole-number quantity.'); return; } $this->boot(); if (!$this->ProductModel->find((int) $id)) { redirect('products'); exit; } $this->ProductModel->update((int) $id, $data); redirect('products'); exit; }
    public function delete($id) { $this->auth(); $this->boot(); $this->ProductModel->delete((int) $id); redirect('products'); exit; }
    private function form($product, $action, $title, $error) { $this->call->view('products/form', compact('product', 'action', 'title', 'error')); }
}
