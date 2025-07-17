<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status'])) {
    $status = $_GET['status'];
    header('Content-Type: application/json');
    include '../../database/config.php'; // Your DB connection file

    try {
        if ($status == 'completed') {
            $stmt = $pdo->prepare('
                SELECT 
                R.ref_no as ref_no,
                MA.remarks as remarks,
                IFNULL(SStatus.name, MA.other_status) as service_status,
                IFNULL(SS.name, R.other_category) as sub_category, 
                IFNULL(S.name, "Others") as category,
                DATE_FORMAT(R.created_at, "%M %e, %Y - %l:%i%p") as created_at, 
                DATE_FORMAT(MA.created_at, "%M %e, %Y - %l:%i%p") as finished_at, 
                CONCAT(
                    FLOOR(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at) / 86400), "d ",  
                    LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 86400) / 3600), 2, "0"), "h:",
                    LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 3600) / 60), 2, "0"), "m:",
                    LPAD(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 60), 2, "0"), "s"
                ) as duration,
                R.emp_id as requestor_id,
                R.emp_name as requestor_name, 
                CONCAT(MP.first_name, " ", MP.last_name) as maintenance_personnel,
                L.location_name
                FROM 
                request R
                LEFT JOIN sub_service SS ON R.sub_category_id = SS.id
                LEFT JOIN service S ON SS.service_id = S.id
                INNER JOIN location L ON R.location_id = L.location_id
                LEFT JOIN maintenance_activity MA ON R.id = MA.request_id
                LEFT JOIN service_status SStatus ON MA.service_status_id = SStatus.id
                INNER JOIN maintenance_personnel MP ON MA.personnel_id = MP.id
                WHERE 
                R.status = :status 
                ORDER BY 
                R.created_at DESC;
            ');
        } else {
            $stmt = $pdo->prepare('
                SELECT 
                R.ref_no as ref_no,
                IFNULL(SS.name, R.other_category) as sub_category, 
                IFNULL(S.name, "Others") as category,
                DATE_FORMAT(R.created_at, "%M %e, %Y - %l:%i%p") as created_at, 
                R.emp_id as requestor_id,
                R.emp_name as requestor_name, 
                L.location_name
                FROM 
                request R
                LEFT JOIN sub_service SS ON R.sub_category_id = SS.id
                LEFT JOIN service S ON SS.service_id = S.id
                INNER JOIN location L ON R.location_id = L.location_id
                WHERE 
                R.status = :status 
                ORDER BY 
                R.created_at DESC;
            ');
        }

        $stmt->execute(['status' => $status]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'General error: ' . $e->getMessage()]);
    }

} else {
    echo "<script>alert('Access Denied'); window.location.href = '../index.php';</script>";
    exit;
}
?>