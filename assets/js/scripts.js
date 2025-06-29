// This file contains JavaScript for client-side validation and dynamic UI interactions.

// Function to validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Function to validate password match
function validatePasswordMatch(password, confirmPassword) {
    return password === confirmPassword;
}

// Function to show alert messages
function showAlert(message, type) {
    const alertBox = document.createElement('div');
    alertBox.className = `alert alert-${type}`;
    alertBox.textContent = message;
    document.body.appendChild(alertBox);
    setTimeout(() => {
        alertBox.remove();
    }, 3000);
}

// Event listener for login form submission
document.getElementById('loginForm').addEventListener('submit', function(event) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!validateEmail(email)) {
        event.preventDefault();
        showAlert('Please enter a valid email address.', 'danger');
    }
});

// Event listener for registration form submission
document.getElementById('registerForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (!validatePasswordMatch(password, confirmPassword)) {
        event.preventDefault();
        showAlert('Passwords do not match.', 'danger');
    }
});

// Function to handle countdown timer for events
function startCountdown(endTime) {
    const countdownElement = document.getElementById('countdown');
    const interval = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            clearInterval(interval);
            countdownElement.textContent = "Event has started!";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }, 1000);
}

// Example usage of countdown timer
// startCountdown(new Date("YYYY-MM-DDTHH:MM:SS").getTime()); // Replace with actual event date/time