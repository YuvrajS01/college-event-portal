<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    header('Location: index.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$registered = false;

$stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->fetch_assoc()) {
    $registered = true;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?php echo htmlspecialchars($event['title']); ?> - Event Details</title>
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
    <div class="event-details-container">
        <div class="event-details-card">
            <div class="event-details-row">
                <div class="event-details-info">
                    <h1><?php echo htmlspecialchars($event['title']); ?></h1>
                    <hr>
                    <p><span class="label">Description:</span><br><span> <?php echo nl2br(htmlspecialchars($event['description'])); ?></span></p>
                    <p><span class="label">Date and Time:</span> <span><?php echo htmlspecialchars($event['date_time']); ?></span></p>
                    <p><span class="label">Venue:</span> <span><?php echo htmlspecialchars($event['venue']); ?></span></p>
                    <?php if ($registered): ?>
                        <span class="badge-success">You are registered for this event</span>
                    <?php else: ?>
                        <form action="register_event.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">
                            <button type="submit" class="btn-success">Register for Event</button>
                        </form>
                    <?php endif; ?>
                    <a href="index.php" class="btn-outline">&larr; Back to Events</a>
                </div>
                <div class="event-details-image">
                    <?php if ($event['image']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                    <?php else: ?>
                        <img src="assets/images/placeholder.jpg" alt="Event Image">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>Made with ❤️ by Yuvraj</p>
    </footer>
</body>
</html>