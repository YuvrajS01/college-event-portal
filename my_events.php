<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

// Check if user is logged in and is a student
redirectIfNotLoggedIn();
if (getUserRole() !== 'student') {
    header('Location: index.php');
    exit();
}

// Fetch registered events for the logged-in student
$user_id = $_SESSION['user_id'];
$query = "SELECT events.id, events.title, events.date_time, events.venue, registrations.timestamp 
          FROM registrations 
          JOIN events ON registrations.event_id = events.id 
          WHERE registrations.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>My Registered Events</title>
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
        <div class="dashboard-container">
            <h1 class="dashboard-title">My Registered Events</h1>
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Date/Time</th>
                                <th>Venue</th>
                                <th>Registration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($row['date_time']))); ?></td>
                                    <td><?php echo htmlspecialchars($row['venue']); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($row['timestamp']))); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">You haven’t registered for any events yet!</div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>Made with ❤️ by Yuvraj</p>
    </footer>
</body>
</html>