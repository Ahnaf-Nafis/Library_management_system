<?php
session_start();
require_once '../../db/db_connection.php';

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to verify password
function verifyPassword($inputPassword, $hashedPassword) {
    return password_verify($inputPassword, $hashedPassword);
}

// Function to validate admin login
function adminLogin($username, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT u.user_id, u.password, u.user_type, ad.admin_level 
                           FROM users u 
                           LEFT JOIN admin_details ad ON u.user_id = ad.user_id 
                           WHERE u.username = ? AND u.user_type = 'admin' AND u.status = 'active'");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && verifyPassword($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['admin_level'] = $user['admin_level'] ?? 'regular';

        // Update last login
        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);

        // Log the action
        logAction($user['user_id'], 'admin_login', 'users', $user['user_id'], $_SERVER['REMOTE_ADDR'], 'Admin logged in', $pdo);

        return true;
    }
    return false;
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin';
}

// Function to check if user is a super admin
function isSuperAdmin() {
    return isLoggedIn() && isset($_SESSION['admin_level']) && $_SESSION['admin_level'] === 'super';
}

// Function to log actions into system_logs table
function logAction($user_id, $action, $table_affected, $record_id, $ip_address, $details, $pdo) {
    $stmt = $pdo->prepare("INSERT INTO system_logs (user_id, action, table_affected, record_id, ip_address, details) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $action, $table_affected, $record_id, $ip_address, $details]);
}

// Function to logout
function logout($pdo) {
    if (isLoggedIn()) {
        logAction($_SESSION['user_id'], 'admin_logout', 'users', $_SESSION['user_id'], $_SERVER['REMOTE_ADDR'], 'Admin logged out', $pdo);
        session_unset();
        session_destroy();
    }
}

// Function to redirect if not logged in
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../../../frontend/Admin/admin_login_register.html?error=Please login first");
        exit();
    }
}