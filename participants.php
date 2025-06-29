<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

redirectIfNotLoggedIn();

// Only organizers can view participants
if (getUserRole() !== 'organizer') {
    header('Location: index.php');
    exit();
}

// Get event_id from GET or POST
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
if (!$event_id) {
    header('Location: dashboard.php');
    exit();
}

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$event) {
    header('Location: dashboard.php');
    exit();
}

// Fetch participants
$stmt = $conn->prepare("SELECT u.id, u.name, u.email FROM registrations r JOIN users u ON r.user_id = u.id WHERE r.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$participants = $stmt->get_result();
$stmt->close();

// Handle remove participant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_user_id'])) {
    $remove_user_id = intval($_POST['remove_user_id']);
    $del_stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
    $del_stmt->bind_param("ii", $remove_user_id, $event_id);
    $del_stmt->execute();
    $del_stmt->close();
    header("Location: participants.php?event_id=" . $event_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants - <?php echo htmlspecialchars($event['title']); ?></title>
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
<div class="dashboard-container">
    <h1 class="dashboard-title">Participants for: <?php echo htmlspecialchars($event['title']); ?></h1>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($participants->num_rows > 0): ?>
                <?php while ($row = $participants->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="remove_user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn-outline" onclick="return confirm('Remove this participant?');">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3">No participants registered yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn-outline">&larr; Back to Dashboard</a>
</div>
<footer>
    <p>Made with ❤️ by Yuvraj</p>
</footer>
</body>
</html>