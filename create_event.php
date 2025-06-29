<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Redirect if not logged in or not an organizer
redirectIfNotLoggedIn();
if (getUserRole() !== 'organizer') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_time = $_POST['date_time'];
    $venue = $_POST['venue'];
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $original_name = basename($_FILES['image']['name']);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $unique_name = uniqid('event_', true) . ($ext ? ('.' . $ext) : '');
        $target_path = "uploads/" . $unique_name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $image = $unique_name;
        } else {
            $_SESSION['error'] = 'Image upload failed. Please check folder permissions.';
        }
    }

    // Insert event into database
    $stmt = $conn->prepare("INSERT INTO events (title, description, date_time, venue, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $date_time, $venue, $image);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Event created successfully!";
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Error creating event. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
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
    <div class="container">
        <div class="form-container">
            <h1 class="form-title">Create Event</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <form action="create_event.php" method="POST" enctype="multipart/form-data" class="custom-form">
                <div class="form-group">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" id="title" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-input" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="date_time" class="form-label">Date & Time</label>
                    <input type="datetime-local" id="date_time" name="date_time" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" id="venue" name="venue" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Event Image</label>
                    <input type="file" id="image" name="image" class="form-input">
                </div>
                <button type="submit" class="btn-primary">Create Event</button>
            </form>
        </div>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <footer>
        <p>Made with ❤️ by Yuvraj</p>
    </footer>
</body>
</html>