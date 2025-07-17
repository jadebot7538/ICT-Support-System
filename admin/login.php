<?php
require_once "../security/session.php";
require_once "../security/sessionRegeneration.php";
require_once "../security/sessionValidation.php";
require_once "../database/config.php";

if (isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: index.php');
    exit;
}
// Display error message if login failed
$error_message = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
// Clear the error message after displaying it
if (isset($_SESSION['login_error'])) {
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal | Support System</title>
    <link rel="icon" href="../img/website-logo.svg" type="image/png">
    <link rel="shortcut icon" href="../img/website-logo.svg" type="image/png">
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #1a3b5d;
            --secondary-color: #0d6efd;
            --accent-color: #00b8d4;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 8px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
            background-color: white;
            position: relative;
            transition: var(--transition);
        }

        .login-container:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
        }

        .login-header {
            background: linear-gradient(to right, var(--primary-color), #2c5282);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--accent-color), var(--secondary-color));
        }

        .login-body {
            padding: 2.5rem 2rem;
        }

        .form-control {
            padding: 0.8rem 1rem;
            border-radius: var(--border-radius);
            border: 1px solid #e0e0e0;
            font-size: 1rem;
            transition: var(--transition);
            height: auto;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            border-top-left-radius: var(--border-radius);
            border-bottom-left-radius: var(--border-radius);
            color: #6c757d;
        }

        .input-group-text.right {
            border-left: none;
            border-right: 1px solid #e0e0e0;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .btn-login {
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
            border: none;
            padding: 0.9rem;
            font-weight: 600;
            transition: var(--transition);
            color: white;
            letter-spacing: 0.5px;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-login:hover {
            background: linear-gradient(to right, var(--accent-color), var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
            font-weight: 600;
        }

        .forgot-password:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        .alert {
            border-radius: var(--border-radius);
            border-left: 4px solid #dc3545;
            font-size: 0.95rem;
        }

        .form-check-input:checked {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .input-with-icon .form-control {
            border-left: none;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .copyright {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .brand-icon {
            background: rgba(255, 255, 255, 0.15);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* Mobile optimizations */
        @media (max-width: 576px) {
            .login-body {
                padding: 2rem 1.5rem;
            }

            .login-header {
                padding: 1.5rem 1rem;
            }

            .form-control,
            .btn-login {
                padding: 0.75rem;
            }

            .brand-icon {
                width: 60px;
                height: 60px;
            }

            .login-container {
                margin: 0 10px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <div class="brand-icon">
                <i class="bi bi-shield-lock fs-1"></i>
            </div>
            <h4 class="mb-0 fw-bold">ADMIN PORTAL</h4>
        </div>

        <div class="login-body">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?php echo $error_message; ?></div>
                </div>
            <?php endif; ?>
            <?php
            if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">

                    <?php echo $_GET['error'];
                    ?>
                </div>
            <?php } else {
                ?>
                <p class="text-muted text-center mb-4">Sign in to access the admin dashboard</p>

                <?php
            } ?>

            <form id="loginForm" action="process/login.php" method="POST" novalidate>
                <div class="mb-4">
                    <label class="form-label" for="username">Username</label>
                    <div class="input-group input-with-icon">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input class="form-control" type="text" id="username" name="username"
                            placeholder="Enter your username" required autocomplete="username">
                    </div>
                    <div id="usernameError" class="invalid-feedback"></div>
                </div>

                <div class="mb-4">
                    <!--  <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0" for="password">Password</label>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div> -->
                    <div class="input-group input-with-icon">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input class="form-control" type="password" id="password" name="password"
                            placeholder="Enter your password" required autocomplete="current-password">
                        <span class="input-group-text right bg-transparent" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                    <div id="passwordError" class="invalid-feedback"></div>
                </div>

                <!--        <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div> -->

                <button class="btn btn-login w-100 mb-4 d-flex align-items-center justify-content-center" type="submit">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    <span>Sign In</span>
                </button>

                <div class="text-center">
                    <p class="copyright mb-0">© 2025 Support System | All rights reserved</p>
                </div>
            </form>
        </div>
    </div>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Enhanced form validation
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            let isValid = true;
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const usernameError = document.getElementById('usernameError');
            const passwordError = document.getElementById('passwordError');

            // Reset errors
            username.classList.remove('is-invalid');
            password.classList.remove('is-invalid');

            if (!username.value.trim()) {
                event.preventDefault();
                username.classList.add('is-invalid');
                usernameError.textContent = 'Username is required';
                usernameError.style.display = 'block';
                isValid = false;
            }

            if (!password.value.trim()) {
                event.preventDefault();
                password.classList.add('is-invalid');
                passwordError.textContent = 'Password is required';
                passwordError.style.display = 'block';
                isValid = false;
            }

            return isValid;
        });
    </script>
</body>

</html>