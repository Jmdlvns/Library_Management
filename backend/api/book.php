<?php
require_once 'db_connect.php';

class Book {
    private $table_name = "books";
    
    public $id;
    public $title;
    public $author;
    public $isbn;
    public $category;
    public $availability_status;
    
    public function read() {
        return fetchAll("SELECT * FROM {$this->table_name}");
    }
    
    public function create() {
        $sql = "INSERT INTO {$this->table_name} (title, author, isbn, category, availability_status) 
                VALUES (?, ?, ?, ?, ?)";
        executeQuery($sql, [
            $this->title,
            $this->author,
            $this->isbn,
            $this->category,
            $this->availability_status
        ], 'ssssi');
        return true;
    }
    
    public function update() {
        $sql = "UPDATE {$this->table_name} 
                SET title=?, author=?, isbn=?, category=?, availability_status=? 
                WHERE id=?";
        executeQuery($sql, [
            $this->title,
            $this->author,
            $this->isbn,
            $this->category,
            $this->availability_status,
            $this->id
        ], 'ssssii');
        return true;
    }
    
    public function delete() {
        $sql = "DELETE FROM {$this->table_name} WHERE id = ?";
        executeQuery($sql, [$this->id], 'i');
        return true;
    }
}

// API endpoint
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];
$book = new Book();

try {
    switch ($method) {
        case 'GET':
            $books = $book->read();
            echo json_encode(['success' => true, 'books' => $books]);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $book->title = $data['title'];
            $book->author = $data['author'];
            $book->isbn = $data['isbn'];
            $book->category = $data['category'];
            $book->availability_status = $data['availability_status'];
            $book->create();
            echo json_encode(['success' => true, 'message' => 'Book created successfully']);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $book->id = $data['id'];
            $book->title = $data['title'];
            $book->author = $data['author'];
            $book->isbn = $data['isbn'];
            $book->category = $data['category'];
            $book->availability_status = $data['availability_status'];
            $book->update();
            echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
            break;

        case 'DELETE':
            $book->id = $_GET['id'];
            $book->delete();
            echo json_encode(['success' => true, 'message' => 'Book deleted successfully']);
            break;

        default:
            throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

