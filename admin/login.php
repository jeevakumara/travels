<?php
require_once __DIR__ . '/../config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ' . $basePath . '/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid CSRF token.';
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if ($email && $password) {
            try {
                $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    // Login Success
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['last_activity'] = time();
                    
                    header('Location: ' . $basePath . '/admin/index.php');
                    exit;
                } else {
                    $error = 'Invalid credentials.';
                }
            } catch (PDOException $e) {
                error_log("Login Error: " . $e->getMessage());
                $error = 'An error occurred. Please try again.';
            }
        } else {
            $error = 'Please fill in all fields.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - NMD Travels</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/assets/css/site.css">
    <style>
        body {
            background-color: var(--color-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: var(--space-4);
        }
        .login-card {
            background: white;
            padding: var(--space-8);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--color-border);
        }
        .login-header {
            text-align: center;
            margin-bottom: var(--space-6);
        }
        .login-logo {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-lg);
            margin-bottom: var(--space-4);
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card animate-fade-in">
            <div class="login-header">
                <img src="<?php echo $basePath; ?>/assets/img/logo.jpg" alt="NMD Travels" class="login-logo">
                <h2>Admin Login</h2>
                <p class="text-muted">Secure access for administrators</p>
            </div>

            <?php if ($error): ?>
                <div class="flash-message flash-error" style="position: static; transform: none; width: 100%; margin-bottom: var(--space-6);">
                    <div class="flash-content">
                        <span class="flash-text"><?php echo htmlspecialchars($error); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="form-field">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" required autofocus placeholder="admin@example.com">
                </div>
                
                <div class="form-field">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn btn-full btn-lg">Sign In</button>
            </form>
            
            <div class="text-center mt-6">
                <a href="<?php echo $basePath; ?>/" class="text-sm text-muted hover:text-primary">Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
