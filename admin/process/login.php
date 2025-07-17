<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "../../security/sessionRegeneration.php";
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        require_once '../../database/config.php';
        $stmt = $pdo->prepare('SELECT * FROM admin_account WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($admin && password_verify($password, $admin['password'])) {
          secureLogin($admin['username']); 
            // $_SESSION['adminId'] = $admin['id'];
            header('Location: ../index.php');
            exit;
        } else {    
            header('Location: ../login.php?error=Incorrect+username+or+password');
            exit;
        }
    } catch (PDOException $e) {
        echo "An error occurred. Please contact the administration.";
    }
} else {
    echo "Access Denied";
}
