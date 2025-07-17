<?php

require_once "../database/config.php";
if (isset($_GET['service_id'])) {
    $serviceId = $_GET['service_id'];

    $stmt = $pdo->prepare("SELECT id, name FROM sub_service WHERE service_id = :service_id AND is_deleted = '0'");
    $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
    $stmt->execute();

    $subServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($subServices);
} else {
    echo json_encode([]);
}
