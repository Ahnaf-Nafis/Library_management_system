<?php
session_start();
require_once '../../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $admin_level = $_POST['admin_level'];
    $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    if ($admin_level !== 'super' && $admin_level !== 'regular') {
        die("Invalid admin level");
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, phone, address, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, 'admin')");
        $stmt->execute([$username, $password, $email, $first_name, $last_name, $phone, $address]);
        $user_id = $pdo->lastInsertId();

        // Insert into admin_details table
        $stmt = $pdo->prepare("INSERT INTO admin_details (user_id, admin_level, department) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $admin_level, $department]);

        // Log the action in system_logs
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $stmt = $pdo->prepare("INSERT INTO system_logs (user_id, action, table_affected, record_id, ip_address, details) VALUES (?, 'admin_registration', 'users', ?, ?, 'Admin registered')");
        $stmt->execute([$user_id, $user_id, $ip_address]);

        // Commit transaction
        $pdo->commit();

        // Redirect to login page on success
        header("Location: ../../../frontend/Admin/admin_login_register.html?success=Admin registered successfully");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Registration failed: " . $e->getMessage());
    }
} else {
    die("Invalid request method");
}
?>