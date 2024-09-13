# Work Hours Tracker

This project is a PHP-based Work Hours Tracker application designed to help users and administrators track work hours, manage leaves, and view work-related statistics.

## Features

### User Role: 
- **Clock In/Out**: Users can clock in when starting work and clock out when finishing.
    - **Late Arrival**: Clocking in after 9:15 AM is marked as late.
    - **Early Leave**: Clocking out before 6:00 PM is marked as an early leave.
    - **Overtime**: Any work hours after 6:00 PM are counted as overtime.
- **Dashboard**: Users can view their total working hours, late arrivals, early leaves, and overtime hours.
- **Leave Requests**: Users can request leaves, view their leave status, and check all leave records for the month.
- **Profile Management**: Users can update their profiles, change their passwords and delete their accounts.

### Admin Role:
- **User Management**: Admins can add new users and manage their profiles.
- **Work Hours Monitoring**: Admins can view each user's clock-in/clock-out data, including late arrivals, early leaves, and overtime hours.
- **Leave Management**: Admins can view, approve, or reject leave requests and add new types of leave.
- **Profile Management**: Admins can edit their own profiles and those of the users, and also change passwords.

### Demo Accounts:
- **Admin Account**: 
  - Email: `admin@gmail.com`
  - Password: `admintest`
- **User Account**: 
  - Email: `user@gmail.com`
  - Password: `usertest`

## Technologies Used

- **Backend**: PHP
- **Frontend**: jQuery, Bootstrap
- **Database**: MySQL
- **UI/UX**: Bootstrap for responsive design

## Installation
- Clone the repository.
- Import the provided SQL file into your MySQL database.
- Place the project in your server's root directory (e.g., XAMPP or WAMP).
- Open your browser and go to: http://localhost/work-hours-tracker.
