# College Event Registration Portal


## The Project is live at: http://yuv-proj.rf.gd/ !! Please check it out.

## Project Overview
This project is a web-based portal for college event registration, allowing students to register for events and organizers to manage them. The portal includes features for user authentication, event management, and a responsive design.

## Technologies Used
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** Core PHP
- **Database:** MySQL

## Folder Structure
```
college-event-portal
├── index.php
├── login.php
├── register.php
├── logout.php
├── dashboard.php
├── create_event.php
├── edit_event.php
├── delete_event.php
├── event_details.php
├── register_event.php
├── my_events.php
├── participants.php
├── includes
│   ├── db.php
│   └── auth.php
├── assets
│   ├── css
│   │   └── style.css
│   ├── js
│   │   └── scripts.js
│   └── images
├── uploads
├── database.sql
└── README.md
```

## Setup Instructions
1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd college-event-portal
   ```

2. **Set Up the Database**
   - Import the `database.sql` file into your MySQL database using a tool like phpMyAdmin.
   - Ensure you have a database created and update the database connection settings in `includes/db.php`.

3. **Run the Project Locally**
   - Place the project folder in the `htdocs` directory of your XAMPP installation.
   - Start the Apache and MySQL services from the XAMPP control panel.
   - Open your web browser and navigate to `http://localhost/college-event-portal/index.php`.

## Sample Credentials 
   For hosted website
- **Organizer:** 
  - Email: `Adm@test.com`
  - Password: `123456`
  
- **Student:** 
  - Email: `student@test.com`
  - Password: `123456`

## Features
- **Student Functionality:**
  - Register and log in
  - Browse and register for events
  - View registered events

- **Organizer Functionality:**
  - Log in to manage events
  - Create, edit, and delete events
  - View participants for each event

## Security Measures
- Passwords are hashed using `password_hash()`.
- Role-based access control is implemented to restrict access to certain pages based on user roles.

## Responsive Design
The portal is designed to be mobile-responsive, ensuring a seamless experience across devices.


## Acknowledgments
This project is developed as part of ATPLC internship assignment to demonstrate full-stack web development skills.
