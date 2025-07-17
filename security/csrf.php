<?php

// Generate CSRF token
function generateCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token): bool
{
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        // Invalid token
        /*  http_response_code(403);
         die('CSRF token validation failed'); */
        return false;
    }
    return true;
}

// Usage in a form
/* $token = generateCSRFToken();


<form method="post" action="process.php">
    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    <!-- Form fields -->
    <button type="submit">Submit</button>
</form>

<?php
// Usage in form processing (process.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token']);
    // Process form data
} */
