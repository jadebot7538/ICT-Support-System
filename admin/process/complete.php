<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once '../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_GET['id'] ?? '';
    /*   echo $requestId; */
    try {
        $stmt = $pdo->prepare('UPDATE request SET status = :status WHERE id = :id');
        $stmt->execute(['status' => 'completed', 'id' => $requestId]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
