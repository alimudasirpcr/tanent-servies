<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Auth extends CI_Controller {
    private $secret_key = "your_secret_key"; // Secret key for JWT

    public function __construct() {
        parent::__construct();
        $this->load->library('jwt'); // JWT library we'll create
    }

    // User login endpoint
    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Static user data for demonstration (replace with DB queries)
        $users = [
            'admin' => 'password123',
            'tenant1' => 'tenantpassword',
        ];

        if (isset($users[$username]) && $users[$username] === $password) {
            // Generate JWT
            $payload = [
                'username' => $username,
                'roles' => ['auth-service'], // Example roles
                'exp' => time() + 3600, // Token expires in 1 hour
            ];
            $token = $this->jwt->encode($payload, $this->secret_key);

            echo json_encode(['status' => 'success', 'token' => $token]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }

    // Token validation endpoint
    public function validate_token() {
        $token = $this->input->post('token');

        try {
            $decoded = $this->jwt->decode($token, $this->secret_key);
            echo json_encode(['status' => 'success', 'data' => $decoded]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
        }
    }
}
?>