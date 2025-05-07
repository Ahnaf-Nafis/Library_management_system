<?php
session_start();
require_once __DIR__ . '/../db/db_connection.php';

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Delete remember me cookie if it exists
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    try {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE token = ?");
        $stmt->execute([$token]);
    } catch (PDOException $e) {
        error_log("Logout error deleting remember token: " . $e->getMessage());
    }
    
    setcookie('remember_token', '', time() - 3600, '/');
}

// Force user_type to 'librarian' for this logout scenario
$user_type = 'librarian';

header("Location: ../../Frontend/index.html?logout=true&user_type=$user_type");

exit();
?>
