<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once '../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_no = $_GET['id'] ?? '';
    if (isset($ref_no) && $ref_no != "") {
        try {
            $stmt = $pdo->prepare('UPDATE request SET status = :status WHERE ref_no = :id');
            $stmt->execute(['status' => 'pending', 'id' => $ref_no]);
            echo json_encode(['success' => true],);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Could not get request ID']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
