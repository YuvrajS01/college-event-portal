-- SQL script to create the necessary tables and insert sample data for the College Event Registration Portal

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'organizer') NOT NULL
);

-- Create events table
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    date_time DATETIME NOT NULL,
    venue VARCHAR(200) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
);

-- Create registrations table
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

-- Insert sample data into users table
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@college.edu', '$2y$10$EIX1gF1Z8g9Z5g5g5g5g5Oa5g5g5g5g5g5g5g5g5g5g5g5g5g5g5', 'organizer'), -- password: admin123
('Student User', 'student@college.edu', '$2y$10$EIX1gF1Z8g9Z5g5g5g5g5Oa5g5g5g5g5g5g5g5g5g5g5g5g5g5g5', 'student'); -- password: student123

-- Insert sample data into events table
INSERT INTO events (title, description, date_time, venue, image) VALUES
('Tech Conference 2023', 'Join us for a day of tech talks and networking.', '2023-11-15 09:00:00', 'Main Auditorium', NULL),
('Art Exhibition', 'Explore the creativity of our talented students.', '2023-12-01 18:00:00', 'Gallery Hall', NULL),
('Sports Day', 'Participate in various sports and activities.', '2023-12-10 10:00:00', 'Sports Complex', NULL);

-- Insert sample data into registrations table
INSERT INTO registrations (user_id, event_id) VALUES
(2, 1), -- Student User registers for Tech Conference
(2, 2); -- Student User registers for Art Exhibition