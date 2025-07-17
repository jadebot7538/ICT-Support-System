<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_GET['parent_id'])) {
    require_once "../database/config.php";
    $parent_id = intval($_GET['parent_id']);
    $type = $_GET['type']; // Type: division, section, or unit

    $stmt = $pdo->prepare("SELECT l.location_id, l.location_name 
                                  FROM location l
                                  INNER JOIN location_type lt ON l.location_type_id = lt.id
                                  WHERE l.parent_location_id = ? 
                                  AND lt.name = ?
                                  AND l.is_deleted = '0'");
    $stmt->execute([$parent_id, $type]);
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($locations);
}
