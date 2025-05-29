# PHP Singleton CRUD API

A PHP project implementing a CRUD API with singleton pattern, logging, and email functionality.

## Features

- Singleton pattern implementation for Database, Logger, and Emailer
- CRUD API endpoints for User management
- Logging using Monolog
- Email functionality using PHPMailer
- Environment-based configuration
- PDO for database operations

## Requirements

- PHP 7.4 or higher
- Composer
- MySQL/MariaDB
- SMTP server for email functionality

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Create a `.env` file in the root directory with the following variables:
   ```
   # Database Configuration
   DB_HOST=localhost
   DB_NAME=your_database
   DB_USER=your_username
   DB_PASS=your_password

   # SMTP Configuration
   SMTP_HOST=smtp.example.com
   SMTP_PORT=587
   SMTP_USER=your_smtp_username
   SMTP_PASS=your_smtp_password
   MAIL_FROM_ADDRESS=noreply@example.com
   MAIL_FROM_NAME=Your App Name
   ```
4. Create the database and users table:
   ```sql
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

## API Endpoints

### Users

- `GET /users` - Get all users
- `GET /users/{id}` - Get a specific user
- `POST /users` - Create a new user
- `PUT /users/{id}` - Update a user
- `DELETE /users/{id}` - Delete a user

### Example Usage

Create a new user:
```bash
curl -X POST http://localhost/users \
  -H "Content-Type: application/json" \
  -d '{"name": "John Doe", "email": "john@example.com", "password": "secret123"}'
```

Get all users:
```bash
curl http://localhost/users
```

## Project Structure

```
├── src/
│   ├── Core/
│   │   ├── Database.php
│   │   ├── Logger.php
│   │   └── Emailer.php
│   └── Models/
│       └── User.php
├── public/
│   └── index.php
├── logs/
├── vendor/
├── .env
├── composer.json
└── README.md
```

## Security Notes

- Passwords are hashed using PHP's password_hash()
- Database credentials are stored in environment variables
- Input validation and sanitization is implemented
- CORS headers are configured for API access

## License

MIT 