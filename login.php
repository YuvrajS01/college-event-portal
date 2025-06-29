<?php
include 'includes/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College Event Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="custom-navbar">
        <div class="navbar-container">
            <a class="navbar-brand" href="index.php">College Event Portal</a>
            <input type="checkbox" id="navbar-toggle" class="navbar-toggle">
            <label for="navbar-toggle" class="navbar-icon">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <div class="navbar-links" id="navbarNav">
                <ul>
                    <?php if (isLoggedIn()): ?>
                        <li style="margin-right:1.5em; color:#3F72AF; font-weight:600; font-size:1.08em; list-style:none;">
                            Hi! <?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? 'User'); ?>
                        </li>
                        <?php if (getUserRole() == 'student'): ?>
                            <li><a href="my_events.php">My Events</a></li>
                        <?php elseif (getUserRole() == 'organizer'): ?>
                            <li><a href="dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <h1 class="form-title">Login</h1>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center" style="background:#ffeaea;color:#b30000;padding:0.7em 1em;border-radius:0.5em;margin-bottom:1em;font-size:1.05em;">
                <?php if ($_GET['error'] === 'empty'): ?>
                    Please fill in all fields.
                <?php elseif ($_GET['error'] === 'invalid'): ?>
                    Invalid email, password, or role.
                <?php elseif ($_GET['error'] === 'server'): ?>
                    Server error. Please try again later.
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <form id="loginForm" method="POST" action="includes/auth.php" class="custom-form">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-input" id="email" name="email" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-input" id="password" name="password" required autocomplete="current-password">
            </div>
            <div class="form-group">
                <label for="role" class="form-label">Role</label>
                <select class="form-input" id="role" name="role" required>
                    <option value="student">Student</option>
                    <option value="organizer">Organizer</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <div id="error-message" class="alert alert-danger mt-3" style="display:none;"></div>
        <p style="text-align:center;margin-top:1em;">New user? <a href="register.php">Register here</a></p>
    </div>

    <footer>
        <p>Made with ❤️ by Yuvraj</p>
    </footer>

    <script src="assets/js/scripts.js"></script>
</body>
</html>