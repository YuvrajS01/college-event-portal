<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Event Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/auth.php'; ?>
    <?php include 'includes/db.php'; ?>
    
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

    <header class="hero-banner">
        <h1>Discover Upcoming Events</h1>
    </header>

    <main>
        <div class="events-grid">
            <?php
            $events = $conn->query("SELECT * FROM events ORDER BY date_time ASC");
            while ($event = $events->fetch_assoc()):
            ?>
                <div class="event-card">
                    <img src="<?php echo (!empty($event['image']) && file_exists('uploads/' . $event['image'])) ? 'uploads/' . htmlspecialchars($event['image']) : 'assets/images/placeholder.jpg'; ?>" alt="Event Image">
                    <div class="event-card-body">
                        <h5><?php echo htmlspecialchars($event['title']); ?></h5>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="event-meta"><strong>Date & Time:</strong> <?php echo date('Y-m-d H:i', strtotime($event['date_time'])); ?></div>
                        <div class="event-meta"><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></div>
                        <a href="event_details.php?id=<?php echo $event['id']; ?>" class="btn-primary">View Details</a>
                        <?php if (isLoggedIn() && getUserRole() == 'student'): ?>
                            <form action="register_event.php" method="POST" style="display:inline;">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="btn-success">Register</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="text-center mt-4">
        <p>Made with ❤️ by Yuvraj</p>
    </footer>

    <script src="assets/js/scripts.js"></script>
</body>
</html>