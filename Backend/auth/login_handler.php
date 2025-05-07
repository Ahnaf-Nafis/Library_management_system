<?php
header('Content-Type: application/json');
require_once '../db/db_connection.php';
require_once 'auth_functions.php';

try {
    // Get the raw POST data
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['email']) || !isset($input['password'])) {
        throw new Exception('Email and password are required');
    }

    $email = sanitizeInput($input['email']);
    $password = $input['password'];
    $remember = isset($input['remember']) ? $input['remember'] : false;

    // Query the users table for the email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception('Invalid email or password');
    }

    // Verify the password
    if (!verifyPassword($password, $user['password'])) {
        throw new Exception('Invalid email or password');
    }

    // Check if user is active
    if ($user['status'] !== 'active') {
        throw new Exception('Account is not active');
    }

    // Prepare the response
    $response = [
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => $user['email'],
            'role' => $user['user_type']
        ],
        'redirect' => '../../Frontend/index.html'
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>