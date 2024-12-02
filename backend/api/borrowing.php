<?php
require_once 'db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $sql = "SELECT b.*, bk.title as book_title, u.name as user_name FROM borrowings b 
                    JOIN books bk ON b.book_id = bk.id 
                    JOIN users u ON b.user_id = u.id";
            $borrowings = fetchAll($sql);
            echo json_encode(['success' => true, 'borrowings' => $borrowings]);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "INSERT INTO borrowings (book_id, user_id, borrow_date, due_date) VALUES (?, ?, ?, ?)";
            executeQuery($sql, [$data['book_id'], $data['user_id'], $data['borrow_date'], $data['due_date']], 'iiss');
            echo json_encode(['success' => true, 'message' => 'Borrowing added successfully']);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "UPDATE borrowings SET return_date=?, fine=? WHERE id=?";
            executeQuery($sql, [$data['return_date'], $data['fine'], $data['id']], 'sdi');
            echo json_encode(['success' => true, 'message' => 'Borrowing updated successfully']);
            break;

        default:
            throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

