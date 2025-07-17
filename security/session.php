<?php
// Prevent direct access to this file
/* if (!defined('APP_INITIALIZED')) {
    header('HTTP/1.0 403 Forbidden');
    exit('Direct access to this file is not allowed.');
}
 */
// Set secure session configurations before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.gc_maxlifetime', 3600); // Session timeout (1 hour)
ini_set('session.cookie_lifetime', 0); // Until browser is closed


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Log session initialization for security auditing
/* if (defined('ENABLE_SECURITY_LOGGING')) {
    error_log("Session initialized for user: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest'));
} */

?>