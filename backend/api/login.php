<?php
require_once 'db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['email'], $data['password'])) {
        throw new Exception('Email and password are required');
    }

    // Fetch the user data from the database
    $user = $db->fetchOne("SELECT id, name, email, role, password FROM users WHERE email = ?", [$data['email']], 's');

    if ($user) {
        // Verify password using password_verify()
        if (password_verify($data['password'], $user['password'])) {
            unset($user['password']); // Remove password from response
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            throw new Exception('Invalid credentials');
        }
    } else {
        throw new Exception('User not found');
    }

} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $db->closeConnection();
}
?>
