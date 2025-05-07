<?php
require_once __DIR__ . '/../db/db_connection.php';

class UserService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function generateUsername($firstName, $lastName) {
        $base = strtolower($firstName[0] . preg_replace('/[^a-z]/i', '', $lastName));
        $username = $base;
        $counter = 1;

        do {
            $stmt = $this->pdo->prepare("SELECT user_id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() === 0) {
                return $username;
            }
            
            $username = $base . $counter++;
        } while ($counter < 100);

        throw new Exception('Could not generate unique username', 500);
    }
}
?>