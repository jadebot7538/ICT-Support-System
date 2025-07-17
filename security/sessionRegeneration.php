<?php
// Regenerate session ID on login
function secureLogin($user)
{
    // Verify credentials first

    // Then regenerate ID before setting authenticated state
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user;
    $_SESSION['last_activity'] = time();
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
}

// Regenerate periodically during session lifetime
if (
    isset($_SESSION['last_activity']) &&
    time() - $_SESSION['last_activity'] > 300
) { // Every 5 minutes
    session_regenerate_id(true);
    $_SESSION['last_activity'] = time();
}


?>