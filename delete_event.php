<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in and is an organizer
redirectIfNotLoggedIn();
if (getUserRole() !== 'organizer') {
    header('Location: index.php');
    exit();
}

// Check if event ID is provided
if (isset($_GET['id'])) {
    $eventId = intval($_GET['id']);

    // Prepare a statement to delete the event
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {
        // Event deleted successfully
        $_SESSION['message'] = "Event deleted successfully.";
    } else {
        // Error deleting event
        $_SESSION['message'] = "Error deleting event. Please try again.";
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "No event ID provided.";
}

// Redirect to the dashboard
header('Location: dashboard.php');
exit();
?>