<?php
header('Content-Type: application/json');
require_once '../../db/db_connection.php';
require_once 'admin_auth_fun.php';

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
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

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

    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_type'] = $user['user_type'];
    
    if ($user['user_type'] === 'admin') {
        // If user is admin, fetch admin level
        $stmt = $pdo->prepare("SELECT admin_level FROM admin_details WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        $adminDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['admin_level'] = $adminDetails['admin_level'] ?? 'regular';
    }

    // Update last login
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);

    // Log the action
    logAction($user['user_id'], 'user_login', 'users', $user['user_id'], $_SERVER['REMOTE_ADDR'], 'User logged in', $pdo);

    // Determine redirect based on user type
    $redirect = '../../../Frontend/index.html';
    if ($user['user_type'] === 'admin') {
        $redirect = '../../../Frontend/Admin/dashboard.php';
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
        'redirect' => $redirect
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