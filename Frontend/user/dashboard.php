<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /library_management_system/Frontend/user/login.html');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome User!</h1>
    <!-- Dashboard content here -->
</body>
</html>