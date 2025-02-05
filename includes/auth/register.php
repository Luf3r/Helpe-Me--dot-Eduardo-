<?php
// includes/auth/sign-up.php

require_once __DIR__ . '/../../app/Config/config.php';
require_once __DIR__ . '/../../app/Models/User.php';
require_once __DIR__ . '/../../app/Helpers/functions.php';
require_once __DIR__ . '/../../app/Helpers/sanitize.php';

session_start();

// Redirect logged-in users
if (isLoggedIn()) {
    redirect('/public/user/dashboard.php');
}

$error = '';
$username = '';
$email = '';

// Process registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = Sanitizer::sanitizeString($_POST['username']);
    $email = Sanitizer::sanitizeEmail($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        try {
            $user = new User();
            if ($user->register($username, $email, $password)) {
                redirect('/public/auth/login.php?success=registered');
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $error = 'Username or email already exists';
            } else {
                $error = 'Registration error: ' . $e->getMessage();
            }
        }
    }
}

include __DIR__ . '/../../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Register</h2>
            
            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo Sanitizer::output($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo Sanitizer::output($username); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo Sanitizer::output($email); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
                <a href="/public/auth/login.php" class="btn btn-link">Already have an account? Login</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>