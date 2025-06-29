<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

redirectIfNotLoggedIn();

if (getUserRole() !== 'student') {
    header("Location: index.php");
    exit();
}

// Check if event_id is provided
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : (isset($_GET['event_id']) ? intval($_GET['event_id']) : 0);
if (!$event_id) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Check if already registered
$stmt = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    // Already registered, redirect to event details
    header("Location: event_details.php?id=" . $event_id);
    exit();
}
$stmt->close();

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert registration for this user and event
    $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $event_id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: event_details.php?id=" . $event_id);
        exit();
    } else {
        $stmt->close();
        header("Location: register_event.php?event_id=$event_id&error=Registration failed. Please try again.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
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

<div class="container mt-5">
    <div class="form-container">
        <h1 class="form-title">Register for Event</h1>
        <?php if (isset(
            $_GET['error']) && $_GET['error']): ?>
            <div class="alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <form action="register_event.php" method="POST" class="custom-form">
            <div class="form-group">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            <button type="submit" class="btn-primary">Register</button>
        </form>
    </div>
</div>

<footer>
    <p>Made with ❤️ by Yuvraj</p>
</footer>

<script src="path/to/your/custom/scripts.js"></script>
</body>
</html>