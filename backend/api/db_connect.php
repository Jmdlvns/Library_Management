<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'library_management';

class Database {
    private $conn;

    public function __construct($host, $username, $password, $database) {
        try {
            $this->conn = new mysqli($host, $username, $password, $database);

            if ($this->conn->connect_error) {
                throw new Exception('Database connection failed: ' . $this->conn->connect_error);
            }

        } catch (Exception $e) {
            // Send JSON error response and log the error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            error_log($e->getMessage());
            exit;
        }
    }

    // Execute a query (INSERT, UPDATE, DELETE)
    public function executeQuery($sql, $params = [], $types = '') {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Query preparation failed: ' . $this->conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception('Query execution failed: ' . $stmt->error);
        }

        return $stmt;
    }

    // Fetch all rows from a query (SELECT multiple rows)
    public function fetchAll($sql, $params = [], $types = '') {
        $stmt = $this->executeQuery($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch a single row from a query (SELECT one row)
    public function fetchOne($sql, $params = [], $types = '') {
        $stmt = $this->executeQuery($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Close the database connection
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Instantiate the Database class
$db = new Database($host, $username, $password, $database);
?>
