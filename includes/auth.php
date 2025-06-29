<?php
session_start();
include_once 'db.php';

// Handle login POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'], $_POST['role'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate input
    if (empty($email) || empty($password) || empty($role)) {
        header('Location: ../login.php?error=empty');
        exit();
    }

    try {
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND role = ? LIMIT 1');
        $stmt->bind_param('ss', $email, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Redirect based on role
            // All users go to home page
            header('Location: ../index.php');
            exit();
        } else {
            header('Location: ../login.php?error=invalid');
            exit();
        }
    } catch (Exception $e) {
        header('Location: ../login.php?error=server');
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotOrganizer() {
    if (getUserRole() !== 'organizer') {
        header("Location: index.php");
        exit();
    }
}

function redirectIfNotStudent() {
    if (getUserRole() !== 'student') {
        header("Location: index.php");
        exit();
    }
}
?>