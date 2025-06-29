<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

redirectIfNotLoggedIn();
if (getUserRole() !== 'organizer') {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $eventId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();

    if (!$event) {
        echo "Event not found!";
        exit();
    }
} else {
    echo "No event ID provided!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Always get eventId from POST for update
    $eventId = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    if (!$eventId) {
        echo "No event ID provided!";
        exit();
    }
    // Fetch event again for image fallback
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
    if (!$event) {
        echo "Event not found!";
        exit();
    }
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_time = $_POST['date_time'];
    $venue = $_POST['venue'];
    $image = $event['image']; // Default to existing image

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $original_name = basename($_FILES['image']['name']);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $unique_name = uniqid('event_', true) . ($ext ? ('.' . $ext) : '');
        $target_path = "uploads/" . $unique_name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $image = $unique_name;
        }
    }

    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, date_time = ?, venue = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $title, $description, $date_time, $venue, $image, $eventId);
    $stmt->execute();
    $stmt->close();

    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
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
            <h1 class="form-title">Edit Event</h1>
            <form action="edit_event.php?id=<?php echo $event['id']; ?>" method="POST" enctype="multipart/form-data" class="custom-form">
                <div class="form-group">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" id="title" name="title" class="form-input" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-input" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="date_time" class="form-label">Date & Time</label>
                    <input type="datetime-local" id="date_time" name="date_time" class="form-input" value="<?php echo date('Y-m-d\TH:i', strtotime($event['date_time'])); ?>" required>
                </div>
                <div class="form-group">
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" id="venue" name="venue" class="form-input" value="<?php echo htmlspecialchars($event['venue']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Event Image</label>
                    <input type="file" id="image" name="image" class="form-input">
                    <?php if (!empty($event['image'])): ?>
                        <div style="margin-top:0.5em;">
                            <img src="uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="Current Event Image" style="max-width:120px; border-radius:0.5em; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                            <span style="font-size:0.95em; color:#555; margin-left:0.5em;">Current image</span>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                <button type="submit" class="btn-primary">Update Event</button>
            </form>
        </div>
    </div>
    <footer>
        <p>Made with ❤️ by Yuvraj</p>
    </footer>
</body>
</html>