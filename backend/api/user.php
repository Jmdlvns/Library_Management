<?php
require_once 'db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $users = fetchAll("SELECT id, name, email, role FROM users");
            echo json_encode(['success' => true, 'users' => $users]);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            executeQuery($sql, [$data['name'], $data['email'], $hashedPassword, $data['role']], 'ssss');
            echo json_encode(['success' => true, 'message' => 'User added successfully']);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "UPDATE users SET name=?, email=?, role=? WHERE id=?";
            executeQuery($sql, [$data['name'], $data['email'], $data['role'], $data['id']], 'sssi');
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
            break;

        case 'DELETE':
            $id = $_GET['id'];
            $sql = "DELETE FROM users WHERE id=?";
            executeQuery($sql, [$id], 'i');
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
            break;

        default:
            throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

