<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/** JSON API for the Laboratory Exercise 6 product manager. */
class ProductApiController extends Controller
{
    private $api;

    private function bootApi()
    {
        $this->api = $this->call->library('api');
    }

    private function bootDatabase()
    {
        $this->call->database();
        $this->db = lava_instance()->db;
    }

    private function authenticate()
    {
        return $this->api->require_jwt();
    }

    public function login()
    {
        $this->bootApi();
        $this->api->require_method('POST');
        $this->api->rate_limit('product_api_login', 10, 300);
        $input = $this->api->body();
        $username = (string) getenv('PRODUCT_ADMIN_USERNAME');
        $password = (string) getenv('PRODUCT_ADMIN_PASSWORD');

        if ($username === '' || $password === '') {
            $this->api->respond_error('API login is not configured.', 503);
        }

        if (!hash_equals($username, (string) ($input['username'] ?? '')) ||
            !hash_equals($password, (string) ($input['password'] ?? ''))) {
            $this->api->respond_error('Invalid username or password.', 401);
        }

        $this->bootDatabase();
        $tokens = $this->api->issue_tokens([
            'id' => 1,
            'role' => 'admin',
            'scopes' => ['products:read', 'products:write'],
        ]);
        $this->api->respond(['message' => 'Signed in.', 'tokens' => $tokens]);
    }

    public function logout()
    {
        $this->bootApi();
        $this->api->require_method('POST');
        $this->authenticate();
        $input = $this->api->body();
        $refreshToken = (string) ($input['refresh_token'] ?? '');
        if ($refreshToken !== '') {
            $this->api->revoke_refresh_token($refreshToken);
        }
        $this->api->respond(['message' => 'Signed out.']);
    }

    public function refresh()
    {
        $this->bootApi();
        $this->api->require_method('POST');
        $this->bootDatabase();
        $input = $this->api->body();
        $refreshToken = (string) ($input['refresh_token'] ?? '');
        if ($refreshToken === '') $this->api->respond_error('Refresh token is required.', 422);
        $this->api->refresh_access_token($refreshToken);
    }

    public function index()
    {
        $this->bootApi();
        $this->api->require_method('GET');
        $this->authenticate();
        $this->bootDatabase();
        $rows = $this->db->raw('SELECT id, product_name, description, price, quantity, created_at FROM products ORDER BY id DESC');
        $this->api->respond(['data' => $rows->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function store()
    {
        $this->bootApi();
        $this->api->require_method('POST');
        $this->authenticate();
        $input = $this->api->body();
        $this->requireProductCredentials($input);
        $data = $this->validatedProduct($input);
        if (isset($data['error'])) $this->api->respond_error($data['error'], 422);

        $this->bootDatabase();
        $this->db->raw(
            'INSERT INTO products (product_name, description, price, quantity) VALUES (?, ?, ?, ?)',
            [$data['product_name'], $data['description'], $data['price'], $data['quantity']]
        );
        $id = (int) $this->db->raw('SELECT LAST_INSERT_ID() AS id')->fetch(PDO::FETCH_ASSOC)['id'];
        $this->api->respond(['message' => 'Product created.', 'data' => $this->findProduct($id)], 201);
    }

    public function update($id)
    {
        $this->bootApi();
        $this->api->require_method($_SERVER['REQUEST_METHOD'] === 'PATCH' ? 'PATCH' : 'PUT');
        $this->authenticate();
        $input = $this->api->body();
        $this->requireProductCredentials($input);
        $data = $this->validatedProduct($input);
        if (isset($data['error'])) $this->api->respond_error($data['error'], 422);

        $this->bootDatabase();
        if (!$this->findProduct((int) $id)) $this->api->respond_error('Product not found.', 404);
        $this->db->raw(
            'UPDATE products SET product_name = ?, description = ?, price = ?, quantity = ? WHERE id = ?',
            [$data['product_name'], $data['description'], $data['price'], $data['quantity'], (int) $id]
        );
        $this->api->respond(['message' => 'Product updated.', 'data' => $this->findProduct((int) $id)]);
    }

    public function delete($id)
    {
        $this->bootApi();
        $this->api->require_method('DELETE');
        $this->authenticate();
        $this->requireProductCredentials($this->api->body());
        $this->bootDatabase();
        if (!$this->findProduct((int) $id)) $this->api->respond_error('Product not found.', 404);
        $this->db->raw('DELETE FROM products WHERE id = ?', [(int) $id]);
        $this->api->respond(['message' => 'Product deleted.']);
    }

    private function validatedProduct(array $input)
    {
        $name = trim((string) ($input['product_name'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $price = $input['price'] ?? null;
        $quantity = filter_var($input['quantity'] ?? null, FILTER_VALIDATE_INT);

        if ($name === '' || mb_strlen($name) > 100) return ['error' => 'Product name is required and must be at most 100 characters.'];
        if (!is_numeric($price) || (float) $price < 0 || (float) $price > 99999999.99) return ['error' => 'Price must be a non-negative amount up to 99999999.99.'];
        if ($quantity === false || $quantity < 0) return ['error' => 'Quantity must be a non-negative whole number.'];

        return [
            'product_name' => $name,
            'description' => $description,
            'price' => number_format((float) $price, 2, '.', ''),
            'quantity' => $quantity,
        ];
    }

    private function requireProductCredentials(array $input)
    {
        $reauth = $input['reauth'] ?? [];
        $username = (string) getenv('PRODUCT_ADMIN_USERNAME');
        $password = (string) getenv('PRODUCT_ADMIN_PASSWORD');

        if ($username === '' || $password === '') {
            $this->api->respond_error('API login is not configured.', 503);
        }

        if (!is_array($reauth) ||
            !hash_equals($username, (string) ($reauth['username'] ?? '')) ||
            !hash_equals($password, (string) ($reauth['password'] ?? ''))) {
            $this->api->respond_error('Please re-enter your username and password to confirm this change.', 401);
        }
    }

    private function findProduct($id)
    {
        $result = $this->db->raw(
            'SELECT id, product_name, description, price, quantity, created_at FROM products WHERE id = ? LIMIT 1',
            [$id]
        )->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
