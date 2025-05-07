<?php
require_once __DIR__ . '/../db/db_connection.php';
require_once __DIR__ . '/auth_functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    
    if (empty($email)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit();
    }
    
    $auth = new Auth();
    $result = $auth->initiatePasswordReset($email);
    
    if ($result['success']) {
        // In a real application, you would send an email with the reset link
        // For this example, we'll just return the token (don't do this in production)
        echo json_encode([
            'success' => true, 
            'message' => 'Reset link sent (simulated)',
            'reset_link' => "reset_password.html?token={$result['token']}"
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>