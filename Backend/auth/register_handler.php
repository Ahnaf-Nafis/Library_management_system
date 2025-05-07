<?php
require_once 'auth_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $email = sanitizeInput($_POST['email']);
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $user_type = 'member'; // Default for registration

    $errors = [];

    if (empty($username) || empty($password) || empty($email) || empty($first_name) || empty($last_name)) {
        $errors[] = "All required fields must be filled.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (isEmailTaken($email, $pdo)) {
        $errors[] = "Email is already taken.";
    }

    if (isUsernameTaken($username, $pdo)) {
        $errors[] = "Username is already taken.";
    }

    if (empty($errors)) {
        $hashedPassword = hashPassword($password);
        try {
            $pdo->beginTransaction();

            // Insert into users table
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, phone, address, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $email, $first_name, $last_name, $phone, $address, $user_type]);
            $user_id = $pdo->lastInsertId();

            // Insert into member_details table
            $membership_number = 'MEM' . str_pad($user_id, 6, '0', STR_PAD_LEFT);
            $membership_type = 'regular';
            $membership_start_date = date('Y-m-d');

            $stmt = $pdo->prepare("INSERT INTO member_details (user_id, membership_number, membership_type, membership_start_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $membership_number, $membership_type, $membership_start_date]);

            $pdo->commit();
            header("Location: ../../Frontend/user/login.html?success=Registration successful. Please login.");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = "Registration failed: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        header("Location: ../../Frontend/user/register.html?error=" . urlencode(implode(", ", $errors)));
        exit();
    }
}
?>