<?php
require_once __DIR__ . '/../db/db_connection.php';
require_once __DIR__ . '/auth_functions.php';

class RegistrationService {
    private $conn;
    private $userInputData;
    private $sanitizedData = [];
    private $errors = [];

    public function __construct() {
        try {
            $this->conn = getDBConnection();
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            $this->handleError("Database connection failed", 500);
        }
    }

    /**
     * Handle the registration process
     * 
     * @param array $data User input data
     * @return array Response with success/error message
     */
    public function handleRegistration($data) {
        error_log("Handling registration with data: " . json_encode($data));

        try {
            // Store original data
            $this->userInputData = $data;

            // Check for required fields
            $requiredFields = ['firstName', 'lastName', 'studentId', 'email', 'password', 'confirmPassword', 'terms'];
            $receivedFields = array_keys($data);
            error_log("Received fields: " . implode(", ", $receivedFields));

            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new RuntimeException("All fields are required");
                }
            }

            // Validate input
            $this->validateInput($data);
            error_log("Input validated successfully");

            // Sanitize input
            $this->sanitizeInput($data);
            error_log("Input sanitized");

            // Hash password
            $this->sanitizedData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            error_log("Password hashed");

            // Create user account
            error_log("Creating user account");
            $this->createUserAccount($this->sanitizedData);

            // Return success response
            return [
                'success' => true,
                'message' => 'Registration successful. You can now login.',
                'redirect' => 'login.html'
            ];
        } catch (PDOException $e) {
            error_log("PDO Error in handleRegistration: " . $e->getMessage());

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
                error_log("Transaction rolled back");
            }

            // Check for duplicate entry
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                if (strpos($e->getMessage(), 'email') !== false) {
                    return $this->handleError("Email already registered");
                } elseif (strpos($e->getMessage(), 'username') !== false) {
                    return $this->handleError("Username already taken");
                } elseif (strpos($e->getMessage(), 'student_id') !== false) {
                    return $this->handleError("Student ID already registered");
                }
            }

            return $this->handleError("Database error occurred");
        } catch (RuntimeException $e) {
            error_log("Error in handleRegistration: " . $e->getMessage());
            return $this->handleError($e->getMessage());
        } catch (Exception $e) {
            error_log("Unexpected error in handleRegistration: " . $e->getMessage());
            return $this->handleError("An unexpected error occurred");
        }
    }

    /**
     * Validate user input
     * 
     * @param array $data User input data
     * @throws RuntimeException If validation fails
     */
    private function validateInput($data) {
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Invalid email format";
        }

        // Validate password length and complexity
        if (strlen($data['password']) < 8) {
            $this->errors['password'] = "Password must be at least 8 characters";
        } elseif (!preg_match('/[A-Z]/', $data['password'])) {
            $this->errors['password'] = "Password must contain at least one uppercase letter";
        } elseif (!preg_match('/[a-z]/', $data['password'])) {
            $this->errors['password'] = "Password must contain at least one lowercase letter";
        } elseif (!preg_match('/[0-9]/', $data['password'])) {
            $this->errors['password'] = "Password must contain at least one number";
        }

        // Check password confirmation
        if ($data['password'] !== $data['confirmPassword']) {
            $this->errors['confirmPassword'] = "Passwords do not match";
        }

        // Validate terms acceptance
        if ($data['terms'] !== true) {
            $this->errors['terms'] = "You must accept the terms and conditions";
        }

        // Throw exception if there are validation errors
        if (!empty($this->errors)) {
            throw new RuntimeException("Validation failed");
        }
    }

    /**
     * Sanitize user input
     * 
     * @param array $data User input data
     */
    private function sanitizeInput($data) {
        $this->sanitizedData = [
            'first_name' => sanitizeInput($data['firstName']),
            'last_name' => sanitizeInput($data['lastName']),
            'student_id' => sanitizeInput($data['studentId']),
            'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            'password' => $data['password'],
            'username' => $this->generateUsername($data['firstName'], $data['lastName'])
        ];
    }

    /**
     * Generate username from first and last name
     * 
     * @param string $firstName First name
     * @param string $lastName Last name
     * @return string Generated username
     */
    private function generateUsername($firstName, $lastName) {
        $baseUsername = strtolower(substr($firstName, 0, 1) . $lastName);
        $username = $baseUsername;
        $counter = 1;

        // Check if username exists, if so append a number
        while (isUsernameExists($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        error_log("Generated username: $username");
        return $username;
    }

    /**
     * Create user account in database
     * 
     * @param array $data Sanitized user data
     * @throws RuntimeException If user creation fails
     */
    private function createUserAccount($data) {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // Check if email exists
            if (isEmailExists($data['email'])) {
                error_log("Email already registered: " . $data['email']);
                throw new RuntimeException("Email already registered");
            }
            error_log("Email check executed");

            // Check if studentId exists
            if (isStudentIdExists($data['student_id'])) {
                error_log("Student ID already registered: " . $data['student_id']);
                throw new RuntimeException("Student ID already registered");
            }

            // Generate username if not already done
            $username = $data['username'];
            error_log("Generated username: $username");

            // Ensure the status column exists (for older db versions)
            $this->ensureStatusColumnExists();
            error_log("Status column exists in users table");

            // Insert user
            error_log("Preparing to insert user");
            $stmt = $this->conn->prepare("
                INSERT INTO users 
                (username, email, password, first_name, last_name, user_type, registration_date, status) 
                VALUES (?, ?, ?, ?, ?, 'member', NOW(), 'active')
            ");
            
            error_log("User insert SQL: " . $stmt->queryString);
            
            if (!$stmt->execute([
                $username,
                $data['email'],
                $data['password'],
                $data['first_name'],
                $data['last_name']
            ])) {
                throw new RuntimeException("Failed to create user account");
            }
            
            $userId = (int)$this->conn->lastInsertId(); // Convert to integer explicitly
            error_log("User inserted successfully");
            error_log("New user ID: $userId");

            // Generate membership number
            $membershipNumber = $this->generateMembershipNumber($userId);
            error_log("Generated membership number: $membershipNumber");

            // Insert member details
            $stmt = $this->conn->prepare("
                INSERT INTO member_details 
                (user_id, membership_number, membership_type, membership_start_date, 
                membership_end_date, max_books_allowed, student_id) 
                VALUES (?, ?, 'student', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 5, ?)
            ");
            
            if (!$stmt->execute([
                $userId,
                $membershipNumber,
                $data['student_id']
            ])) {
                throw new RuntimeException("Failed to create member details");
            }
            
            error_log("Member details inserted successfully");

            // Commit transaction
            $this->conn->commit();
            error_log("Transaction committed");
            
            return true;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
                error_log("Transaction rolled back");
            }
            error_log("Error in createUserAccount: " . $e->getMessage());
            throw $e;
        } catch (RuntimeException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
                error_log("Transaction rolled back");
            }
            error_log("Error in createUserAccount: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate membership number
     * 
     * @param int $userId User ID
     * @return string Membership number
     */
    private function generateMembershipNumber(int $userId) {
        // Use explicit type declaration and return a formatted membership number
        return 'MEM' . str_pad((string)$userId, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Ensure status column exists in users table
     */
    private function ensureStatusColumnExists() {
        $stmt = $this->conn->prepare("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'users' 
            AND COLUMN_NAME = 'status'
        ");
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            // Add status column if it doesn't exist
            $this->conn->exec("
                ALTER TABLE users 
                ADD COLUMN status ENUM('active', 'inactive', 'suspended') DEFAULT 'active'
            ");
        }
    }

    /**
     * Ensure student_id column exists in member_details table
     */
    private function ensureStudentIdColumnExists() {
        $stmt = $this->conn->prepare("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'member_details' 
            AND COLUMN_NAME = 'student_id'
        ");
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            error_log("Student ID column does not exist in member_details table. Adding it.");
            // Add student_id column if it doesn't exist
            $this->conn->exec("
                ALTER TABLE member_details 
                ADD COLUMN student_id VARCHAR(50) DEFAULT NULL,
                ADD UNIQUE (student_id)
            ");
            error_log("Student ID column added");
        }
    }

    /**
     * Handle error response
     * 
     * @param string $message Error message
     * @param int $code HTTP status code
     * @return array Error response
     */
    private function handleError($message, $code = 400) {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }
        
        http_response_code($code);
        return $response;
    }
}

// Process registration request
header('Content-Type: application/json');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Log request information
error_log("Registration request received: " . date('Y-m-d H:i:s'));
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("Content-Type: " . $_SERVER['CONTENT_TYPE']);

// Get raw input
$rawInput = file_get_contents('php://input');
error_log("Raw request body: " . $rawInput);

// Parse input based on content type
$data = [];
if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // JSON input
    error_log("Starting registration process");
    error_log("Content-Type: application/json");
    error_log("Raw input: " . $rawInput);
    $data = json_decode($rawInput, true);
    error_log("Decoded input: " . json_encode($data));
} else {
    // Form data
    $data = [
        'firstName' => $_POST['first_name'] ?? null,
        'lastName' => $_POST['last_name'] ?? null,
        'studentId' => $_POST['student_id'] ?? null,
        'email' => $_POST['email'] ?? null,
        'password' => $_POST['password'] ?? null,
        'confirmPassword' => $_POST['confirm_password'] ?? null,
        'terms' => isset($_POST['terms']) && ($_POST['terms'] === 'on' || $_POST['terms'] === 'true' || $_POST['terms'] === '1'),
    ];
}

// Create registration service and handle request
$registrationService = new RegistrationService();
$response = $registrationService->handleRegistration($data);

// Send response
echo json_encode($response);
?>