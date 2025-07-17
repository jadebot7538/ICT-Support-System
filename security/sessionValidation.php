<?php

function isSessionValid($pdo)
{
    // Check session expiration
    if (!isset($_SESSION['user_id'])) {
        // Session is valid
        return false;
    } else {
        //check if exist in db
        $username = $_SESSION['user_id'];
        $sql = "SELECT * FROM admin_account WHERE username = :admin";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['admin' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            session_unset();
            session_destroy();
            return false;
        }
    }
    if (
        isset($_SESSION['last_activity']) &&
        (time() - $_SESSION['last_activity'] > 1800)
    ) {
        // Session expired after 30 minutes of inactivity
        session_unset();
        session_destroy();
        return false;
    }

    // Check for session fixation by validating browser fingerprint
    if (
        isset($_SESSION['user_agent']) &&
        $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']
    ) {
        session_unset();
        session_destroy();
        return false;
    }

    // Optional: Check for IP address changes (careful with mobile users)
    if (
        isset($_SESSION['ip_address']) &&
        $_SESSION['ip_address'] != $_SERVER['REMOTE_ADDR']
    ) {
        session_unset();
        session_destroy();
        return false;
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();
    return true;
}

// Usage
/* if (!isSessionValid()) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

 */