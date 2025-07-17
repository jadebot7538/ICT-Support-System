<?php
header('Content-Type: application/json');
require_once "../../database/config.php";
// Replace with your actual logic to count new employee requests
$count = $pdo->query("SELECT COUNT(*) FROM new_employee WHERE status = 'pending'")->fetchColumn();
echo json_encode(['count' => (int) $count]);